-- ====================================
-- HR1 MASTER DATABASE SCHEMA
-- ====================================

DROP DATABASE IF EXISTS hr1;
CREATE DATABASE hr1;
USE hr1;

-- ====================================
-- 1. DEPARTMENTS
-- ====================================
CREATE TABLE departments (
    department_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    manager_id INT NULL
);

-- ====================================
-- 2. JOBS (with vacancies & filled slots)
-- ====================================
CREATE TABLE jobs (
    job_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    department_id INT,
    description TEXT,
    employment_type ENUM('Full-time', 'Part-time', 'Contract') NOT NULL,
    created_by INT,
    status ENUM('Open', 'Closed') DEFAULT 'Open',
    vacancies INT NOT NULL DEFAULT 1,
    filled_positions INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
);

-- ====================================
-- 3. EMPLOYEES
-- ====================================
CREATE TABLE employees (
    employee_id INT PRIMARY KEY AUTO_INCREMENT,
    applicant_id INT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20),
    hire_date DATE,
    job_id INT,
    department_id INT,
    status ENUM('Active','Inactive','Resigned') DEFAULT 'Active',
    FOREIGN KEY (job_id) REFERENCES jobs(job_id),
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
);

-- ====================================
-- 4. USERS (Login/Signup for employees)
-- ====================================
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('Admin','HR','Manager','Employee') DEFAULT 'Employee',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);

-- ====================================
-- 5. APPLICANTS
-- ====================================
CREATE TABLE applicants (
    applicant_id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20),
    resume_path VARCHAR(255),
    application_date DATE,
    status ENUM('In Progress','Hired','Rejected') DEFAULT 'In Progress'
);

-- ====================================
-- 6. JOB REQUISITIONS
-- ====================================
CREATE TABLE job_requisitions (
    requisition_id INT PRIMARY KEY AUTO_INCREMENT,
    job_id INT NOT NULL,
    requested_by INT NOT NULL,
    approval_status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
    open_date DATE,
    close_date DATE,
    FOREIGN KEY (job_id) REFERENCES jobs(job_id),
    FOREIGN KEY (requested_by) REFERENCES employees(employee_id)
);

-- ====================================
-- 7. APPLICATIONS
-- ====================================
CREATE TABLE applications (
    application_id INT PRIMARY KEY AUTO_INCREMENT,
    applicant_id INT NOT NULL,
    job_id INT NOT NULL,
    stage ENUM('Screening','Interview','Offer','Hired','Rejected') DEFAULT 'Screening',
    status ENUM('Active','Hired','Rejected') DEFAULT 'Active',
    applied_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id),
    FOREIGN KEY (job_id) REFERENCES jobs(job_id)
);

-- ====================================
-- 8. INTERVIEWS
-- ====================================
CREATE TABLE interviews (
    interview_id INT PRIMARY KEY AUTO_INCREMENT,
    application_id INT NOT NULL,
    interviewer_id INT NOT NULL,
    schedule_date DATETIME NOT NULL,
    result ENUM('Pass','Fail','Pending') DEFAULT 'Pending',
    notes TEXT,
    FOREIGN KEY (application_id) REFERENCES applications(application_id),
    FOREIGN KEY (interviewer_id) REFERENCES employees(employee_id)
);

-- ====================================
-- 9. APPLICANT ASSESSMENTS
-- ====================================
CREATE TABLE applicant_assessments (
    assessment_id INT PRIMARY KEY AUTO_INCREMENT,
    applicant_id INT NOT NULL,
    type ENUM('Technical','Aptitude','Psychometric') NOT NULL,
    score DECIMAL(5,2),
    result ENUM('Pass','Fail') DEFAULT 'Fail',
    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id)
);

-- ====================================
-- 10. APPLICANT COMMUNICATION
-- ====================================
CREATE TABLE applicant_communication (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    applicant_id INT NOT NULL,
    sent_by VARCHAR(50),
    message_type ENUM('Email','SMS') NOT NULL,
    subject VARCHAR(255),
    body TEXT,
    sent_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES applicants(applicant_id)
);

-- ====================================
-- 11. ONBOARDING TASKS
-- ====================================
CREATE TABLE onboarding_tasks (
    task_id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    task_name VARCHAR(255) NOT NULL,
    assigned_to INT,
    due_date DATE,
    status ENUM('Pending','Completed') DEFAULT 'Pending',
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id),
    FOREIGN KEY (assigned_to) REFERENCES employees(employee_id)
);

-- ====================================
-- 12. ONBOARDING DOCUMENTS
-- ====================================
CREATE TABLE onboarding_documents (
    document_id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    doc_type VARCHAR(100),
    file_path VARCHAR(255),
    verified ENUM('Yes','No') DEFAULT 'No',
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);

-- ====================================
-- 13. GOALS
-- ====================================
CREATE TABLE goals (
    goal_id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    goal_description TEXT,
    kpi VARCHAR(255),
    start_date DATE,
    end_date DATE,
    status ENUM('In Progress','Completed') DEFAULT 'In Progress',
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);

-- ====================================
-- 14. APPRAISALS
-- ====================================
CREATE TABLE appraisals (
    appraisal_id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    period_start DATE,
    period_end DATE,
    rating DECIMAL(3,2),
    comments TEXT,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id),
    FOREIGN KEY (reviewer_id) REFERENCES employees(employee_id)
);

-- ====================================
-- 15. FEEDBACK
-- ====================================
CREATE TABLE feedback (
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    given_by INT NOT NULL,
    feedback_text TEXT,
    feedback_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id),
    FOREIGN KEY (given_by) REFERENCES employees(employee_id)
);

-- ====================================
-- 16. RECOGNITION
-- ====================================
CREATE TABLE recognition (
    recognition_id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    given_by INT NOT NULL,
    recognition_type ENUM('Peer','Manager','Award') NOT NULL,
    message TEXT,
    date_given TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    points_awarded INT DEFAULT 0,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id),
    FOREIGN KEY (given_by) REFERENCES employees(employee_id)
);

-- ====================================
-- 17. REWARDS
-- ====================================
CREATE TABLE rewards (
    reward_id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    reward_name VARCHAR(100),
    points_used INT,
    date_redeemed TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);

-- ====================================
-- TRIGGER: Auto-update job slots
-- ====================================
DELIMITER //
CREATE TRIGGER trg_update_filled_positions
AFTER UPDATE ON applications
FOR EACH ROW
BEGIN
    -- When an application is marked as Hired
    IF NEW.stage = 'Hired' AND NEW.status = 'Hired' THEN
        UPDATE jobs
        SET filled_positions = filled_positions + 1
        WHERE job_id = NEW.job_id;

        -- Close job automatically if vacancies are filled
        UPDATE jobs
        SET status = 'Closed'
        WHERE job_id = NEW.job_id
          AND filled_positions >= vacancies;
    END IF;
END;
//
DELIMITER ;

-- ====================================
-- VIEWS
-- ====================================

-- View 1: Open Jobs with Available Slots
CREATE VIEW open_jobs_view AS
SELECT 
    j.job_id,
    j.title,
    d.name AS department_name,
    j.employment_type,
    j.vacancies,
    j.filled_positions,
    (j.vacancies - j.filled_positions) AS available_slots,
    j.status,
    j.created_at
FROM jobs j
LEFT JOIN departments d ON j.department_id = d.department_id
WHERE j.status = 'Open'
  AND (j.vacancies - j.filled_positions) > 0;

-- View 2: Hiring Progress (% Filled)
CREATE VIEW job_hiring_progress_view AS
SELECT 
    j.job_id,
    j.title,
    d.name AS department_name,
    j.vacancies,
    j.filled_positions,
    ROUND((j.filled_positions / j.vacancies) * 100, 2) AS progress_percentage,
    j.status
FROM jobs j
LEFT JOIN departments d ON j.department_id = d.department_id;
