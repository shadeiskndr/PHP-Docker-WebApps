<?php
function getBasePath() {
    // Get the absolute path to document root
    $docRoot = $_SERVER['DOCUMENT_ROOT'];
    // Get the current script's absolute path
    $scriptPath = dirname($_SERVER['SCRIPT_FILENAME']);
    // Remove document root from script path to get web root relative path
    $webPath = str_replace($docRoot, '', $scriptPath);
    // Count directory levels from web root
    $levelsUp = substr_count($webPath, DIRECTORY_SEPARATOR);
    // Return path to root with correct number of "../"
    return $levelsUp > 0 ? str_repeat('../', $levelsUp) : './';
}

// Use this base path for all navigation items
$baseUrl = '/'; // This will always point to web root

$navItems = [
    'main' => [
        ['url' => $baseUrl . 'index.php', 'text' => 'Home', 'icon' => 'fas fa-home']
    ],
    'calculators' => [
        ['url' => $baseUrl . 'quiz1/puteriClothes.php', 'text' => 'Puteri Clothes Calculator', 'icon' => 'fas fa-tshirt'],
        ['url' => $baseUrl . 'Assignment1/airform2.php', 'text' => 'Air Conditioner Calculator', 'icon' => 'fas fa-fan'],
        ['url' => $baseUrl . 'LabWorkX8/Discount.php', 'text' => 'Discount Calculator', 'icon' => 'fas fa-tags'],
        ['url' => $baseUrl . 'LabWork3/speed_converter.php', 'text' => 'Speed Converter', 'icon' => 'fas fa-tachometer-alt'],
        ['url' => $baseUrl . 'LabWork3/tax_form.php', 'text' => 'Tax Calculator', 'icon' => 'fas fa-calculator'],
        ['url' => $baseUrl . 'LabWork3/BMI_form_sticky.php', 'text' => 'BMI Calculator', 'icon' => 'fas fa-weight'],
        ['url' => $baseUrl . 'LabWork4/biggest_num.php', 'text' => 'Biggest Number', 'icon' => 'fas fa-sort-numeric-up'],
        ['url' => $baseUrl . 'LabWork1/integers.php', 'text' => 'Add 3 Integers', 'icon' => 'fas fa-plus']
    ],
    'databases' => [
        ['url' => $baseUrl . 'updateInventory/updateInventory.php', 'text' => 'Boutique Inventory', 'icon' => 'fas fa-warehouse'],
        ['url' => $baseUrl . 'CinemaTicketing/admin_listMovie.php', 'text' => 'Movies I Watched', 'icon' => 'fas fa-film'],
        ['url' => $baseUrl . 'CarForSale/view_carList.php', 'text' => 'Cars Database', 'icon' => 'fas fa-car'],
        ['url' => $baseUrl . 'VehicleRentalProject/homepage.php', 'text' => 'Vehicle Rental Project', 'icon' => 'fas fa-car-side']
    ]
];
?>

<!-- Sidebar starts -->
<div class="w-64 fixed bg-gray-800 shadow min-h-screen h-screen flex-col justify-between hidden sm:flex overflow-y-auto border-r border-gray-700">
    <div class="px-8">
        <div class="h-16 w-full flex items-center">
            <!-- Logo or Title -->
            <h2 class="text-2xl font-bold text-white">Navigation</h2>
        </div>
        <!-- Search Bar -->
        <div class="flex justify-center mt-6 w-full">
            <div class="relative">
                <div class="text-gray-300 absolute ml-2.5 mt-2 inset-0 w-1 h-1">
                    <i class="fas fa-search text-lg"></i>
                </div>
                <input class="bg-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-100 rounded w-full text-sm text-gray-300 placeholder-gray-400 pl-10 py-2" type="text" placeholder="Search" />
            </div>
        </div>
        <!-- Navigation Menu -->
        <ul class="mt-12">
            <?php foreach($navItems['main'] as $item): ?>
                <li class="flex w-full justify-between text-gray-300 hover:text-gray-500 cursor-pointer items-center mb-6">
                    <a href="<?= htmlspecialchars($item['url']) ?>" class="flex items-center focus:outline-none focus:ring-2 focus:ring-white">
                        <i class="<?= htmlspecialchars($item['icon']) ?> text-lg"></i>
                        <span class="text-sm ml-2"><?= htmlspecialchars($item['text']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>

            <!-- Calculators Accordion -->
            <li class="mb-6">
                <button class="accordion-btn flex w-full justify-between text-gray-300 hover:text-gray-500 cursor-pointer items-center">
                    <div class="flex items-center">
                        <i class="fas fa-calculator text-lg"></i>
                        <span class="text-sm ml-2">Calculators</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-300"></i>
                </button>
                <ul class="accordion-content hidden mt-4">
                    <?php foreach($navItems['calculators'] as $item): ?>
                        <li class="mb-4">
                            <a href="<?= htmlspecialchars($item['url']) ?>" class="flex items-center text-gray-300 hover:text-gray-500">
                                <i class="<?= htmlspecialchars($item['icon']) ?> text-lg"></i>
                                <span class="text-sm ml-2"><?= htmlspecialchars($item['text']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>

            <!-- Databases Accordion -->
            <li class="mb-6">
                <button class="accordion-btn flex w-full justify-between text-gray-300 hover:text-gray-500 cursor-pointer items-center">
                    <div class="flex items-center">
                        <i class="fas fa-database text-lg"></i>
                        <span class="text-sm ml-2">Databases</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-300"></i>
                </button>
                <ul class="accordion-content hidden mt-4">
                    <?php foreach($navItems['databases'] as $item): ?>
                        <li class="mb-4">
                            <a href="<?= htmlspecialchars($item['url']) ?>" class="flex items-center text-gray-300 hover:text-gray-500">
                                <i class="<?= htmlspecialchars($item['icon']) ?> text-lg"></i>
                                <span class="text-sm ml-2"><?= htmlspecialchars($item['text']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
    </div>
    <div class="px-8 border-t border-gray-700">
        <ul class="w-full flex items-center justify-between bg-gray-800">
            <!-- Dark Mode Toggle -->
            <li class="cursor-pointer text-white pt-5 pb-3">
                <button 
                    id="darkModeToggle"
                    aria-label="Toggle dark mode" 
                    class="focus:outline-none focus:ring-2 rounded focus:ring-gray-300">
                    <i id="darkIcon" class="inline dark:hidden fas fa-sun text-lg text-yellow-400"></i>
                    <i id="lightIcon" class="hidden dark:inline fas fa-moon text-lg text-blue-600"></i>
                </button>
            </li>
            <!-- Existing footer icons -->
            <li class="cursor-pointer text-white pt-5 pb-3">
                <button aria-label="show notifications" class="focus:outline-none focus:ring-2 rounded focus:ring-gray-300">
                    <i class="fas fa-bell text-lg"></i>
                </button>
            </li>
            <li class="cursor-pointer text-white pt-5 pb-3">
                <button aria-label="open messages" class="focus:outline-none focus:ring-2 rounded focus:ring-gray-300">
                    <i class="fas fa-envelope text-lg"></i>
                </button>
            </li>
            <li class="cursor-pointer text-white pt-5 pb-3">
                <button aria-label="open settings" class="focus:outline-none focus:ring-2 rounded focus:ring-gray-300">
                    <i class="fas fa-cog text-lg"></i>
                </button>
            </li>
        </ul>
    </div>
</div>
<!-- Sidebar ends -->

<!-- Mobile Navigation Button -->
<div class="fixed sm:hidden bottom-4 right-4 z-50">
    <button id="openSideBar" class="bg-gray-800 text-white p-3 rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-300 border border-gray-700">
        <i class="fas fa-bars text-xl"></i>
    </button>
    <button id="closeSideBar" class="hidden bg-gray-800 text-white p-3 rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-300 border border-gray-700">
        <i class="fas fa-times text-xl"></i>
    </button>
</div>

<!-- Mobile Navigation Sidebar -->
<div id="mobile-nav" class="fixed sm:hidden w-64 bg-gray-800 shadow h-screen flex-col justify-between transform transition-transform duration-300 ease-in-out z-40 border-r border-gray-700">
    <div class="px-8 mb-32">
        <div class="h-16 w-full flex items-center">
            <!-- Logo or Title -->
            <h2 class="text-2xl font-bold text-white">Navigation</h2>
        </div>
        <!-- Search Bar -->
        <div class="flex justify-center mt-6 w-full">
            <div class="relative">
                <div class="text-gray-300 absolute ml-2.5 mt-2 inset-0 w-1 h-1">
                    <i class="fas fa-search text-lg"></i>
                </div>
                <input class="bg-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-100 rounded w-full text-sm text-gray-300 placeholder-gray-400 pl-10 py-2" type="text" placeholder="Search" />
            </div>
        </div>
        <!-- Navigation Menu -->
        <ul class="mt-12">
            <?php foreach($navItems['main'] as $item): ?>
                <li class="flex w-full justify-between text-gray-300 hover:text-gray-500 cursor-pointer items-center mb-6">
                    <a href="<?= htmlspecialchars($item['url']) ?>" class="flex items-center focus:outline-none focus:ring-2 focus:ring-white">
                        <i class="<?= htmlspecialchars($item['icon']) ?> text-lg"></i>
                        <span class="text-sm ml-2"><?= htmlspecialchars($item['text']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>

            <!-- Calculators Accordion -->
            <li class="mb-6">
                <button class="accordion-btn flex w-full justify-between text-gray-300 hover:text-gray-500 cursor-pointer items-center">
                    <div class="flex items-center">
                        <i class="fas fa-calculator text-lg"></i>
                        <span class="text-sm ml-2">Calculators</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-300"></i>
                </button>
                <ul class="accordion-content hidden mt-4">
                    <?php foreach($navItems['calculators'] as $item): ?>
                        <li class="mb-4">
                            <a href="<?= htmlspecialchars($item['url']) ?>" class="flex items-center text-gray-300 hover:text-gray-500">
                                <i class="<?= htmlspecialchars($item['icon']) ?> text-lg"></i>
                                <span class="text-sm ml-2"><?= htmlspecialchars($item['text']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>

            <!-- Databases Accordion -->
            <li class="mb-6">
                <button class="accordion-btn flex w-full justify-between text-gray-300 hover:text-gray-500 cursor-pointer items-center">
                    <div class="flex items-center">
                        <i class="fas fa-database text-lg"></i>
                        <span class="text-sm ml-2">Databases</span>
                    </div>
                    <i class="fas fa-chevron-down transition-transform duration-300"></i>
                </button>
                <ul class="accordion-content hidden mt-4">
                    <?php foreach($navItems['databases'] as $item): ?>
                        <li class="mb-4">
                            <a href="<?= htmlspecialchars($item['url']) ?>" class="flex items-center text-gray-300 hover:text-gray-500">
                                <i class="<?= htmlspecialchars($item['icon']) ?> text-lg"></i>
                                <span class="text-sm ml-2"><?= htmlspecialchars($item['text']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
    </div>
    <div class="px-8 border-t border-r border-gray-700 fixed bottom-0 w-64 bg-gray-800">
        <ul class="w-full flex items-center justify-between bg-gray-800">
            <!-- Dark Mode Toggle -->
            <li class="cursor-pointer text-white pt-5 pb-3">
                <button 
                    id="mobileDarkModeToggle"
                    aria-label="Toggle dark mode" 
                    class="focus:outline-none focus:ring-2 rounded focus:ring-gray-300">
                    <i id="mobileDarkIcon" class="inline dark:hidden fas fa-sun text-lg text-yellow-400"></i>
                    <i id="mobileLightIcon" class="hidden dark:inline fas fa-moon text-lg text-blue-600"></i>
                </button>
            </li>
            <!-- Mobile footer icons -->
            <li class="cursor-pointer text-white pt-5 pb-3">
                <button aria-label="show notifications" class="focus:outline-none focus:ring-2 rounded focus:ring-gray-300">
                    <i class="fas fa-bell text-lg"></i>
                </button>
            </li>
            <li class="cursor-pointer text-white pt-5 pb-3">
                <button aria-label="open messages" class="focus:outline-none focus:ring-2 rounded focus:ring-gray-300">
                    <i class="fas fa-envelope text-lg"></i>
                </button>
            </li>
            <li class="cursor-pointer text-white pt-5 pb-3">
                <button aria-label="open settings" class="focus:outline-none focus:ring-2 rounded focus:ring-gray-300">
                    <i class="fas fa-cog text-lg"></i>
                </button>
            </li>
        </ul>
    </div>
</div>
