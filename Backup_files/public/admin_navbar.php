<style>
/* Hide scrollbar but keep scroll functionality */
.scrollbar-hide::-webkit-scrollbar {
  display: none; /* Chrome, Safari, Edge */
}
.scrollbar-hide {
  -ms-overflow-style: none;  /* IE + Edge */
  scrollbar-width: none;     /* Firefox */
}
</style>

<div class="bg-gray-800 px-4 py-3 flex flex-wrap md:flex-nowrap gap-2 text-sm font-medium text-white rounded-b-md overflow-x-auto relative scrollbar-hide">

    <!-- Add Employee -->
    <a href="addemployee.php"
       class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 text-white">
       <i data-lucide="user-plus" class="w-4 h-4"></i>
       <span>Add Employee</span>
    </a>

    <!-- Employee List -->
    <a href="employee.php"
       class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 text-white">
       <i data-lucide="users" class="w-4 h-4"></i>
       <span>Employee List</span>
    </a>

    <!-- Recruitment -->
    <a href="recruitment.php"
       class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 text-white">
       <i data-lucide="briefcase" class="w-4 h-4"></i>
       <span>Recruitment</span>
    </a>

    <!-- Applicants -->
    <a href="applicants.php"
       class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 text-white">
       <i data-lucide="file-text" class="w-4 h-4"></i>
       <span>Applicants</span>
    </a>

    <!-- Onboarding -->
    <a href="onboarding.php"
       class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 text-white">
       <i data-lucide="clipboard-check" class="w-4 h-4"></i>
       <span>Onboarding</span>
    </a>

    <!-- Performance -->
    <a href="performance.php"
       class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 text-white">
       <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
       <span>Performance</span>
    </a>

    <!-- Recognition -->
    <a href="recognition.php"
       class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 text-white">
       <i data-lucide="award" class="w-4 h-4"></i>
       <span>Recognition</span>
    </a>

    <!-- Configure (Dropdown) -->
    <div class="relative inline-block text-left">
        <button id="configBtn" type="button"
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700 text-white">
            <i data-lucide="settings" class="w-4 h-4"></i>
            <span>Configure</span>
            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" id="configArrow"></i>
        </button>

        <!-- Dropdown Menu -->
        <div id="configMenu"
             class="hidden fixed bg-gray-800 border border-gray-700 rounded-lg shadow-lg p-2 space-y-2 w-48 z-50"
             style="top:0; left:0;">
        
            <a href="statusType.php"
               class="block px-3 py-2 rounded hover:bg-gray-700 text-white">
               Status Type
            </a>
            <a href="roles.php"
               class="block px-3 py-2 rounded hover:bg-gray-700 text-white">
               Roles
            </a>
            <a href="departments.php"
               class="block px-3 py-2 rounded hover:bg-gray-700 text-white">
               Departments
            </a>
        </div>
    </div>

</div>

<script>
const btn = document.getElementById("configBtn");
const menu = document.getElementById("configMenu");
const arrow = document.getElementById("configArrow");

btn.addEventListener("click", (e) => {
    e.preventDefault();
    menu.classList.toggle("hidden");
    arrow.classList.toggle("rotate-180");

    // Position dropdown below the button
    const rect = btn.getBoundingClientRect();
    menu.style.top = rect.bottom + window.scrollY + "px";
    menu.style.left = rect.left + window.scrollX + "px";
});

document.addEventListener("click", (e) => {
    if (!btn.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.add("hidden");
        arrow.classList.remove("rotate-180");
    }
});

// Initialize Lucide icons
if (typeof lucide !== "undefined" && lucide.createIcons) {
    lucide.createIcons();
}
</script>
