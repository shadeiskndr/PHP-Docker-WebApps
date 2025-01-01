<?php
// Define navigation items in a structured array
$navItems = [
    ['url' => 'index.php', 'text' => 'Home'],
    ['url' => 'quiz1/puteriClothes.php', 'text' => 'Puteri Clothes Calculator'],
    ['url' => 'Assignment1/airform2.php', 'text' => 'Air Conditioner Calculator'],
    ['url' => 'LabWorkX8/Discount.php', 'text' => 'Discount Calculator'],
    ['url' => 'LabWork3/speed_converter.php', 'text' => 'Speed Converter'],
    ['url' => 'LabWork3/tax_form.php', 'text' => 'Tax Calculator'],
    ['url' => 'LabWork3/BMI_form_sticky.php', 'text' => 'BMI Calculator'],
    ['url' => 'LabWork4/biggest_num.php', 'text' => 'Biggest Number'],
    ['url' => 'LabWork1/integers.php', 'text' => 'Add 3 Integers'],
    ['url' => 'updateInventory/updateInventory.php', 'text' => 'Mawar Boutique Inventory'],
    ['url' => 'CinemaTicketing/admin_listMovie.php', 'text' => 'Movies I Watched'],
    ['url' => 'CarForSale/view_carList.php', 'text' => 'Cars Database'],
    ['url' => 'VehicleRentalProject/homepage.php', 'text' => 'Vehicle Rental Project']
];
?>
<nav class="w-64 bg-gray-800 text-white min-h-screen p-4">
    <h2 class="text-2xl font-bold mb-6">Navigation</h2>
    <!-- Dark mode toggle -->
    <button 
      id="darkModeToggle" 
      aria-label="Toggle dark mode"
      class="mb-6 px-4 py-2 bg-gray-700 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 flex items-center">
      <span id="darkIcon" class="inline dark:hidden fas fa-moon text-xl text-yellow-500 mr-2"></span>
      <span id="lightIcon" class="hidden dark:inline fas fa-sun text-xl text-yellow-400 mr-2"></span>
      <span class="text-white">Toggle Dark Mode</span>
    </button>
    <!-- Rest of navigation -->
    <ul class="space-y-4">
        <?php foreach($navItems as $item): ?>
            <li>
                <a href="<?= htmlspecialchars($item['url']) ?>" 
                   class="block py-2 px-4 rounded hover:bg-blue-500">
                    <?= htmlspecialchars($item['text']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
