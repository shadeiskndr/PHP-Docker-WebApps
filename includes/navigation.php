<?php
// Define navigation items with associated Font Awesome icons
$navItems = [
    ['url' => 'index.php', 'text' => 'Home', 'icon' => 'fas fa-home'],
    ['url' => 'quiz1/puteriClothes.php', 'text' => 'Puteri Clothes Calculator', 'icon' => 'fas fa-tshirt'],
    ['url' => 'Assignment1/airform2.php', 'text' => 'Air Conditioner Calculator', 'icon' => 'fas fa-fan'],
    ['url' => 'LabWorkX8/Discount.php', 'text' => 'Discount Calculator', 'icon' => 'fas fa-tags'],
    ['url' => 'LabWork3/speed_converter.php', 'text' => 'Speed Converter', 'icon' => 'fas fa-tachometer-alt'],
    ['url' => 'LabWork3/tax_form.php', 'text' => 'Tax Calculator', 'icon' => 'fas fa-calculator'],
    ['url' => 'LabWork3/BMI_form_sticky.php', 'text' => 'BMI Calculator', 'icon' => 'fas fa-weight'],
    ['url' => 'LabWork4/biggest_num.php', 'text' => 'Biggest Number', 'icon' => 'fas fa-sort-numeric-up'],
    ['url' => 'LabWork1/integers.php', 'text' => 'Add 3 Integers', 'icon' => 'fas fa-plus'],
    ['url' => 'updateInventory/updateInventory.php', 'text' => 'Boutique Inventory', 'icon' => 'fas fa-warehouse'],
    ['url' => 'CinemaTicketing/admin_listMovie.php', 'text' => 'Movies I Watched', 'icon' => 'fas fa-film'],
    ['url' => 'CarForSale/view_carList.php', 'text' => 'Cars Database', 'icon' => 'fas fa-car'],
    ['url' => 'VehicleRentalProject/homepage.php', 'text' => 'Vehicle Rental Project', 'icon' => 'fas fa-car-side']
];
?>

<!-- Sidebar starts -->
<div class="w-64 fixed bg-gray-800 shadow min-h-screen h-screen flex-col justify-between hidden sm:flex overflow-y-auto">
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
            <?php foreach($navItems as $item): ?>
                <li class="flex w-full justify-between text-gray-300 hover:text-gray-500 cursor-pointer items-center mb-6">
                    <a href="<?= htmlspecialchars($item['url']) ?>" class="flex items-center focus:outline-none focus:ring-2 focus:ring-white">
                        <!-- Font Awesome Icon -->
                        <i class="<?= htmlspecialchars($item['icon']) ?> text-lg"></i>
                        <span class="text-sm ml-2"><?= htmlspecialchars($item['text']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
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
                    <i id="darkIcon" class="inline dark:hidden fas fa-moon text-lg text-yellow-500"></i>
                    <i id="lightIcon" class="hidden dark:inline fas fa-sun text-lg text-yellow-400"></i>
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
    <button id="openSideBar" class="bg-gray-800 text-white p-3 rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
        <i class="fas fa-bars text-xl"></i>
    </button>
    <button id="closeSideBar" class="hidden bg-gray-800 text-white p-3 rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
        <i class="fas fa-times text-xl"></i>
    </button>
</div>

<!-- Mobile Navigation Sidebar -->
<div id="mobile-nav" class="fixed sm:hidden w-64 bg-gray-800 shadow h-screen flex-col justify-between transform transition-transform duration-300 ease-in-out z-40">
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
            <?php foreach($navItems as $item): ?>
                <li class="flex w-full justify-between text-gray-300 hover:text-gray-500 cursor-pointer items-center mb-6">
                    <a href="<?= htmlspecialchars($item['url']) ?>" class="flex items-center focus:outline-none focus:ring-2 focus:ring-white">
                        <!-- Font Awesome Icon -->
                        <i class="<?= htmlspecialchars($item['icon']) ?> text-lg"></i>
                        <span class="text-sm ml-2"><?= htmlspecialchars($item['text']) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
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
                    <i id="darkIcon" class="inline dark:hidden fas fa-moon text-lg text-yellow-500"></i>
                    <i id="lightIcon" class="hidden dark:inline fas fa-sun text-lg text-yellow-400"></i>
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
