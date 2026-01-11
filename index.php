<?php
require_once "connections.php";

/* Fetch OPEN jobs from hr1 database */
$jobs = [];

$stmt = $connections->prepare("
    SELECT job_id,
           job_title,
           description,
           employment_type,
           vacancies,
           filled_positions
    FROM jobs
    WHERE status = 'Open'
    ORDER BY created_at DESC
");

if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $jobs[] = $row;
    }
    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atiera: Hotel and Restaurant Careers | Join Our Luxury Team</title>
    <!-- AOS Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <!-- Navigation -->
<nav class="navbar">
    <div class="container nav-container">
        <a href="#" class="logo">
            <div class="logo-icon">HR</div>
            <div>
                <div class="logo-text">Atiera</div>
                <div class="logo-subtext">Hotel & Restaurant Management</div>
            </div>
        </a>
        <div class="mobile-toggle" id="mobileToggle">
            <i class="fas fa-bars"></i>
        </div>
        <ul class="nav-links" id="navLinks">
            <li><a href="#home">Home</a></li>
            <li><a href="#" id="aboutLink">About</a></li>
            <li><a href="#careers">Careers</a></li>
            <li><a href="#contact">Contact</a></li>
            <li><a href="login.php" class="signup-btn">Login</a></li>            
        </ul>
    </div>
</nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content" data-aos="fade-right" data-aos-duration="1000">
            <div class="hiring-badge">We're hiring — Front & Back of House</div>
            <h1>Join Our Team at <span>Atiera</span></h1>
            <p>Hospitality careers that grow with you</p>
            <p>Atiera connects talented individuals with great opportunities in hotels and restaurants. Apply online — fast application, interview scheduling, and easy onboarding.</p>
            <div class="hero-buttons">
                <a href="#careers" class="explore-btn">Explore Openings</a>
                <a href="#" class="why-work-btn">Why Work With Us</a>
            </div>
            <!-- <div class="quick-apply-form">
                <input type="text" class="form-input" placeholder="Your full name">
                <select class="position-select">
                    <option value="">Position...</option>
                    <option value="chef">Executive Chef</option>
                    <option value="manager">Front Office Manager</option>
                    <option value="sommelier">Sommelier</option>
                    <option value="therapist">Spa Therapist</option>
                </select>
                <button class="quick-apply-btn">Quick Apply</button>
            </div> -->
        </div>
        <div class="hero-image" data-aos="fade-left" data-aos-duration="1000">
            <div class="image-container">
                <div class="image-placeholder"></div>
                <div class="apply-overlay">
                    <a href="#careers" class="apply-button">Apply — Open Positions</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Work With Us Modal -->
    <div id="whyWorkModal" class="modal">
        <div class="modal-content" data-aos="fade-up" data-aos-duration="800">
            <span class="close-btn">&times;</span>
            <div class="section-title">
                <h2>Why Work With Us</h2>
                <p style="color:#666; margin-top:10px;">
                    Discover what makes Atiera one of the most rewarding places to build a career in hospitality.
                </p>
            </div>
            <div class="about-content" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px; align-items: center;">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <img src="https://images.unsplash.com/photo-1600880292089-90a7e086ee0c?auto=format&fit=crop&w=1000&q=80"
                         alt="HR1 Team"
                         style="width:100%; border-radius:15px; box-shadow:0 8px 25px rgba(0,0,0,0.1);">
                </div>
                <div data-aos="fade-left" data-aos-duration="1000">
                    <h3 style="color:var(--blue); margin-bottom:15px;">Culture of Excellence</h3>
                    <p style="margin-bottom:20px; color:#555;">
                        At Atiera Hotel and Restaurant, we believe in empowering our people. Every team member — from kitchen to concierge — plays
                        an essential role in delivering exceptional guest experiences. We cultivate a culture where your
                        ideas matter, growth is celebrated, and hospitality is a shared passion.
                    </p>
                    <h3 style="color:var(--blue); margin-bottom:15px;">Growth and Opportunities</h3>
                    <p style="margin-bottom:20px; color:#555;">
                        We invest in your development with hands-on training, leadership programs, and international placements.
                        Many of our leaders started their journey as entry-level staff and grew into department heads and managers.
                    </p>
                    <h3 style="color:var(--blue); margin-bottom:15px;">Work-Life Harmony</h3>
                    <p style="color:#555;">
                        Enjoy a supportive work environment that values your well-being, with competitive benefits, flexible
                        schedules, and access to Atiera-exclusive employee perks.
                    </p>
                    <a href="#careers" class="explore-btn" style="margin-top:25px; display:inline-block;">
                        View Job Openings
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Jobs -->
<section id="careers">
    <div class="container">
        <div class="section-title" data-aos="fade-up" data-aos-duration="800">
            <h2>Featured Opportunities</h2>
        </div>

        <div class="jobs-container">

            <?php if (empty($jobs)): ?>
                <p style="text-align:center; color:#666;">
                    No open positions available at the moment.
                </p>
            <?php else: ?>
                <?php foreach ($jobs as $index => $job): ?>
                    <?php
                        // Calculate available vacancies
                        $available = max(
                            0,
                            ($job['vacancies'] ?? 0) - ($job['filled_positions'] ?? 0)
                        );
                    ?>
                    <div class="job-card"
                         data-aos="zoom-in"
                         data-aos-duration="800"
                         data-aos-delay="<?= ($index + 1) * 100 ?>">

                        <div class="card-header">
                            <h3><?= htmlspecialchars($job['job_title']) ?></h3>
                            <span class="department">
                                <?= htmlspecialchars($job['employment_type']) ?>
                            </span>
                        </div>

                        <div class="card-body">
                            <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>

                            <!-- ✅ AVAILABLE VACANCIES -->
                            <p style="margin-top:8px; font-weight:600; color:#16a34a;">
                                Available Vacancies: <?= $available ?>
                            </p>

                            <div class="job-card-buttons">
                                <a href="#" class="btn apply-now-btn">Apply Now</a>
                                <a href="#" class="btn view-more-btn">View More</a>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>



    <!-- Benefits Section -->
    <section style="background-color: var(--light-gray);">
        <div class="container">
            <div class="section-title" data-aos="fade-up" data-aos-duration="800">
                <h2>Why Join Atiera Team?</h2>
            </div>
            <div class="benefits-container">
                <div class="benefit-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="benefit-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h3>Industry Excellence</h3>
                    <p>Work with world-class chefs and hospitality professionals in award-winning establishments.</p>
                </div>
                <div class="benefit-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <div class="benefit-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3>Global Opportunities</h3>
                    <p>Career advancement across our international portfolio of luxury hotels and restaurants.</p>
                </div>
                <div class="benefit-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <div class="benefit-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Professional Growth</h3>
                    <p>Comprehensive training programs and mentorship to develop your hospitality expertise.</p>
                </div>
                <div class="benefit-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                    <div class="benefit-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Employee Benefits</h3>
                    <p>Competitive compensation, health coverage, and exclusive discounts across our properties.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section>
        <div class="container">
            <div class="section-title" data-aos="fade-up" data-aos-duration="800">
                <h2>Employee Stories</h2>
            </div>
            <div class="testimonials-container">
                <div class="testimonial" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <p class="testimonial-text">"Starting as a line cook at Atiera, I've grown into an Executive Chef role through their mentorship program. The culture of excellence pushes you to become your best self every day."</p>
                    <div class="testimonial-author">
                        <img src="pictures/jamess.jpg" alt="Sabandal, James Kneechtel DL." class="author-img">
                        <div class="author-info">
                            <h4>Sabandal, James Kneechtel DL.</h4>
                            <p>Executive Chef, Grand Plaza Hotel</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-container">
                <div class="footer-col" data-aos="fade-right" data-aos-duration="800">
                    <h3>Atiera Careers</h3>
                    <p>Join our team of hospitality professionals dedicated to creating unforgettable guest experiences in luxury settings.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="footer-col" data-aos="fade-up" data-aos-duration="800">
                    <h3>Quick Links</h3>
                    <a href="#home">Home</a>
                    <a href="#" id="aboutLinkFooter">About Us</a>
                    <a href="#careers">Career Opportunities</a>
                    <a href="#" class="benefits-link">Employee Benefits</a>
                </div>
                <div class="footer-col" data-aos="fade-left" data-aos-duration="800">
                    <h3>Contact Us</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Luxury Avenue, Metro City</p>
                    <p><i class="fas fa-phone"></i> +1 (555) 123-4567</p>
                    <p><i class="fas fa-envelope"></i> atierahotelandrestaurant@gmail.com</p>
                </div>
            </div>
            <div class="copyright" data-aos="fade-up" data-aos-duration="800">
                <p>&copy; 2025 ATIERA:Hotel & Restaurant Management. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Employee Benefits Modal -->
    <div id="benefitsModal" class="modal">
        <div class="modal-content" data-aos="fade-up" data-aos-duration="800">
            <span class="close-benefits">&times;</span>
            <div class="section-title">
                <h2>Employee Benefits</h2>
                <p style="color:#666; margin-top:10px;">
                    Atiera Hotel and Restaurant values its people — here are the benefits we provide to support your growth and well-being.
                </p>
            </div>
            <div class="benefits-content" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-top: 30px;">
                <div data-aos="fade-up" data-aos-duration="900" style="text-align:center;">
                    <img src="https://img.icons8.com/ios-filled/100/004AAD/money-bag.png" alt="Competitive Salary">
                    <h3 style="color:var(--blue); margin-top:10px;">Competitive Salary</h3>
                    <p style="color:#555;">We offer industry-leading compensation to reward your hard work and dedication.</p>
                </div>
                <div data-aos="fade-up" data-aos-duration="1100" style="text-align:center;">
                    <img src="https://img.icons8.com/ios-filled/100/004AAD/health-book.png" alt="Health Coverage">
                    <h3 style="color:var(--blue); margin-top:10px;">Health & Wellness</h3>
                    <p style="color:#555;">Comprehensive medical, dental, and wellness programs for you and your family.</p>
                </div>
                <div data-aos="fade-up" data-aos-duration="1300" style="text-align:center;">
                    <img src="https://img.icons8.com/ios-filled/100/004AAD/school.png" alt="Training and Development">
                    <h3 style="color:var(--blue); margin-top:10px;">Training & Development</h3>
                    <p style="color:#555;">We support your professional growth through workshops and leadership programs.</p>
                </div>
                <div data-aos="fade-up" data-aos-duration="1500" style="text-align:center;">
                    <img src="https://img.icons8.com/ios-filled/100/004AAD/travel.png" alt="Travel Discounts">
                    <h3 style="color:var(--blue); margin-top:10px;">Travel Perks</h3>
                    <p style="color:#555;">Enjoy exclusive discounts on hotel stays, dining, and partner establishments.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- About Us Modal -->
    <div id="aboutModal" class="modal">
        <div class="modal-content" data-aos="fade-up" data-aos-duration="800">
            <span class="close-btn close-about">&times;</span>
            <div class="section-title">
                <h2>About Atiera</h2>
                <p style="color:#666; margin-top:10px;">
                    Learn more about Atiera Hotel and Restaurant commitment to luxury, hospitality, and people-first service.
                </p>
            </div>
            <div class="about-content" style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:40px; align-items:center;">
                <div data-aos="fade-right" data-aos-duration="1000">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80"
                         alt="Luxury Hotel"
                         style="width:100%; border-radius:15px; box-shadow:0 8px 25px rgba(0,0,0,0.1);">
                </div>
                <div data-aos="fade-left" data-aos-duration="1000">
                    <h3 style="color:var(--blue); margin-bottom:15px;">Our Mission</h3>
                    <p style="margin-bottom:20px; color:#555;">
                        Atiera exists to redefine hospitality recruitment — connecting passionate individuals with top-tier hotels and restaurants across the country. We empower every applicant to grow, lead, and succeed.
                    </p>
                    <h3 style="color:var(--blue); margin-bottom:15px;">Our Vision</h3>
                    <p style="margin-bottom:20px; color:#555;">
                        To be the Philippines’ most trusted name in hospitality talent solutions — where careers flourish and service excellence thrives.
                    </p>
                    <h3 style="color:var(--blue); margin-bottom:15px;">Our Core Values</h3>
                    <ul style="color:#555; margin-left:20px; margin-bottom:20px;">
                        <li>Integrity in every interaction</li>
                        <li>Respect for people and culture</li>
                        <li>Commitment to growth and learning</li>
                        <li>Passion for hospitality and service</li>
                    </ul>
                    <a href="#careers" class="explore-btn" style="margin-top:25px; display:inline-block;">
                        Explore Careers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Apply Now Modal -->
<div id="applyNowModal" class="modal">
    <div class="modal-content" data-aos="fade-up" data-aos-duration="800">
        <span class="close-btn close-apply">&times;</span>
        <div class="section-title">
            <h2>Apply for This Position</h2>
            <p style="color:#666; margin-top:10px;">Please fill out the form below to submit your application.</p>
        </div>
        <form id="applicationForm" style="display: grid; gap: 20px; margin-top: 20px;" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px;">
                <div>
                    <label for="appFirstName" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">First Name <span style="color:red">*</span></label>
                    <input type="text" id="appFirstName" name="First_Name" required style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif;">
                </div>
                <div>
                    <label for="appMiddleInitial" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">Middle Initial</label>
                    <input type="text" id="appMiddleInitial" name="Middle_Initial" maxlength="1" style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif; text-transform: uppercase;">
                </div>
                <div>
                    <label for="appLastName" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">Last Name <span style="color:red">*</span></label>
                    <input type="text" id="appLastName" name="Last_Name" required style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
    <label for="appJobPosition" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">Job Position <span style="color:red">*</span></label>
    <input type="text" id="appJobPosition" name="Job_Position" readonly required
           style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif; background-color:#f5f5f5;">
</div>
                <div>
                    <label for="appGender" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">Gender <span style="color:red">*</span></label>
                    <select id="appGender" name="Gender" required style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif;">
                        <option value="">Select...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div>
                    <label for="appBirthday" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">Birthday <span style="color:red">*</span></label>
                    <input type="date" id="appBirthday" name="Birthday" required style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif;">
                </div>
            </div>

            <div>
                <label for="appEmail" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">Email <span style="color:red">*</span></label>
                <input type="email" id="appEmail" name="Email" required style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif;">
            </div>

            <div>
                <label for="appPassword" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">Password <span style="color:red">*</span></label>
                <input type="password" id="appPassword" name="Password" minlength="8" required style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif;">
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <div>
                    <label for="appPhone" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">Phone Number <span style="color:red">*</span></label>
                    <input type="tel" id="appPhone" name="Phone_Number" required style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif;">
                </div>
                <div>
                    <label for="appContactInfo" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">Contact Information</label>
                    <input type="text" id="appContactInfo" name="Contact_Information" placeholder="e.g., Messenger, Viber" style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif;">
                </div>
            </div>

            <div>
                <label for="appAddress" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">Address <span style="color:red">*</span></label>
                <textarea id="appAddress" name="Address" rows="2" required style="width:100%; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif;"></textarea>
            </div>

            <div>
                <label for="appResume" style="display:block; margin-bottom:6px; font-weight:500; color:var(--dark-blue);">Upload Resume (PDF only) <span style="color:red">*</span></label>
                <input type="file" id="appResume" name="resume" accept=".pdf" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px; font-size:16px; font-family:'Poppins', sans-serif;">
                <p style="font-size:13px; color:#666; margin-top:5px;">Only PDF files up to 5MB are accepted.</p>
            </div>

            <button type="submit" class="btn" style="background:var(--blue); color:var(--white); padding:14px; border:none; border-radius:8px; font-size:16px; font-weight:600; cursor:pointer; font-family:'Poppins', sans-serif; transition:var(--transition);">
                Submit Application
            </button>
        </form>
    </div>
</div>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            easing: 'ease-out-cubic'
        });

        // Mobile Navigation Toggle
        const mobileToggle = document.getElementById('mobileToggle');
        const navLinks = document.getElementById('navLinks');
        mobileToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            const icon = mobileToggle.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - 70,
                        behavior: 'smooth'
                    });
                    // Close mobile menu if open
                    if (navLinks.classList.contains('active')) {
                        navLinks.classList.remove('active');
                        const icon = mobileToggle.querySelector('i');
                        icon.classList.add('fa-bars');
                        icon.classList.remove('fa-times');
                    }
                }
            });
        });

        // Modal Handlers
        document.addEventListener('DOMContentLoaded', function () {
            // Why Work Modal
            const whyWorkModal = document.getElementById('whyWorkModal');
            const whyWorkBtn = document.querySelector('.why-work-btn');
            const whyWorkClose = whyWorkModal.querySelector('.close-btn');
            whyWorkBtn.addEventListener('click', e => {
                e.preventDefault();
                whyWorkModal.style.display = 'block';
            });
            whyWorkClose.addEventListener('click', () => whyWorkModal.style.display = 'none');
            window.addEventListener('click', e => {
                if (e.target === whyWorkModal) whyWorkModal.style.display = 'none';
            });

            // Benefits Modal
            const benefitsModal = document.getElementById('benefitsModal');
            const benefitsBtn = document.querySelector('.benefits-link');
            const benefitsClose = document.querySelector('.close-benefits');
            if (benefitsBtn) {
                benefitsBtn.addEventListener('click', e => {
                    e.preventDefault();
                    benefitsModal.style.display = 'block';
                    benefitsModal.querySelector('.modal-content').style.animation = 'fadeIn 0.4s ease';
                });
            }
            if (benefitsClose) {
                benefitsClose.addEventListener('click', () => {
                    benefitsModal.querySelector('.modal-content').style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => benefitsModal.style.display = 'none', 300);
                });
            }
            window.addEventListener('click', e => {
                if (e.target === benefitsModal) {
                    benefitsModal.querySelector('.modal-content').style.animation = 'fadeOut 0.3s ease';
                    setTimeout(() => benefitsModal.style.display = 'none', 300);
                }
            });

            // About Modal
            const aboutModal = document.getElementById('aboutModal');
            const aboutLink = document.getElementById('aboutLink');
            const aboutLinkFooter = document.getElementById('aboutLinkFooter');
            const aboutClose = document.querySelector('.close-about');

            const openAboutModal = e => {
                e.preventDefault();
                aboutModal.style.display = 'block';
            };

            aboutLink.addEventListener('click', openAboutModal);
            if (aboutLinkFooter) aboutLinkFooter.addEventListener('click', openAboutModal);

            aboutClose.addEventListener('click', () => aboutModal.style.display = 'none');
            window.addEventListener('click', e => {
                if (e.target === aboutModal) aboutModal.style.display = 'none';
            });
        });

        // Sign Up Modal
// const signupModal = document.getElementById('signupModal');
// const signupBtn = document.querySelector('.signup-btn');
// const closeSignup = document.querySelector('.close-signup');

// if (signupBtn) {
//     signupBtn.addEventListener('click', function(e) {
//         e.preventDefault();
//         signupModal.style.display = 'block';
//     });
// }

// if (closeSignup) {
//     closeSignup.addEventListener('click', function() {
//         signupModal.style.display = 'none';
//     });
// }

// window.addEventListener('click', function(e) {
//     if (e.target === signupModal) {
//         signupModal.style.display = 'none';
//     }
// });

// Form validation
document.getElementById('signupForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const pass = document.getElementById('password').value;
    const confirmPass = document.getElementById('confirmPassword').value;
    
    if (pass !== confirmPass) {
        alert('Passwords do not match!');
        return;
    }
    
    alert('Account created successfully! (This will connect to backend later)');
    signupModal.style.display = 'none';
    this.reset();
});

// Apply Now Modal Logic
const applyNowModal = document.getElementById('applyNowModal');
const applyNowButtons = document.querySelectorAll('.apply-now-btn');
const closeApply = document.querySelector('.close-apply');
const jobPositionInput = document.getElementById('appJobPosition');

// Open modal and set job position
applyNowButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        // Find the job title from the parent card
        const jobCard = btn.closest('.job-card');
        const jobTitle = jobCard.querySelector('.card-header h3').textContent;
        jobPositionInput.value = jobTitle;
        applyNowModal.style.display = 'block';
    });
});

// Close modal
if (closeApply) {
    closeApply.addEventListener('click', () => {
        applyNowModal.style.display = 'none';
    });
}

// Close if clicked outside
window.addEventListener('click', function(e) {
    if (e.target === applyNowModal) {
        applyNowModal.style.display = 'none';
    }
});

// Form submission (optional: add real backend later)
document.getElementById('applicationForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation already handled by HTML5 "required"
    // You can add extra checks here if needed
    
    alert('Application submitted successfully! (In production, this will be sent to the server.)');
    applyNowModal.style.display = 'none';
    this.reset();
});

    </script>

</body>
</html>