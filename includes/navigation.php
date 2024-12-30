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
    <ul class="space-y-4">
        <?php foreach($navItems as $item): ?>
            <li>
                <a href="<?= htmlspecialchars($item['url']) ?>" 
                   class="block py-2 px-4 rounded hover:bg-blue-700">
                    <?= htmlspecialchars($item['text']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
