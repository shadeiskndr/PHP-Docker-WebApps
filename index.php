<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="output.css" rel="stylesheet">
    <title>My PHP Docker Web App</title>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="flex">
        <!-- Vertical Navigation Bar -->
        <nav class="w-64 bg-gray-800 text-white min-h-screen p-4">
            <h2 class="text-2xl font-bold mb-6">Navigation</h2>
            <ul class="space-y-4">
                <li><a href="index.php" class="block py-2 px-4 rounded hover:bg-blue-700">Home</a></li>
                <li><a href="quiz1/puteriClothes.php" class="block py-2 px-4 rounded hover:bg-blue-700">Puteri Clothes Calculator</a></li>
                <li><a href="Assignment1/airform2.php" class="block py-2 px-4 rounded hover:bg-blue-700">Air Conditioner Calculator</a></li>
                <li><a href="LabWorkX8/Discount.php" class="block py-2 px-4 rounded hover:bg-blue-700">Discount Calculator</a></li>
                <li><a href="LabWork3/speed_converter.php" class="block py-2 px-4 rounded hover:bg-blue-700">Speed Converter</a></li>
                <li><a href="LabWork3/tax_form.php" class="block py-2 px-4 rounded hover:bg-blue-700">Tax Calculator</a></li>
                <li><a href="LabWork3/BMI_form_sticky.php" class="block py-2 px-4 rounded hover:bg-blue-700">BMI Calculator</a></li>
                <li><a href="LabWork4/biggest_num.php" class="block py-2 px-4 rounded hover:bg-blue-700">Biggest Number</a></li>
                <li><a href="LabWork1/integers.php" class="block py-2 px-4 rounded hover:bg-blue-700">Add 3 Integers</a></li>
                <li><a href="updateInventory/updateInventory.php" class="block py-2 px-4 rounded hover:bg-blue-700">Mawar Boutique Inventory</a></li>
                <li><a href="CinemaTicketing/admin_listMovie.php" class="block py-2 px-4 rounded hover:bg-blue-700">Movies I Watched</a></li>
                <li><a href="CarForSale/view_carList.php" class="block py-2 px-4 rounded hover:bg-blue-700">Cars Database</a></li>
                <li><a href="VehicleRentalProject/homepage.php" class="block py-2 px-4 rounded hover:bg-blue-700">Vehicle Rental Project</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <header class="bg-gray-800 text-white p-4 rounded">
                <h1 class="text-4xl font-bold">
                    Welcome to My PHP Docker Web App
                </h1>
            </header>
            <main class="mt-8">
                <section class="my-8">
                    <h2 class="text-3xl font-semibold mb-4">
                        Hello world!
                    </h2>
                    <p class="text-lg">
                        This is a simple PHP web application running inside of a Docker container.
                    </p>
                </section>
                <section class="my-8">
                    <h2 class="text-2xl font-semibold mb-4">
                        Posts
                    </h2>
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <?php
                        $connect = mysqli_connect(
                            'db', # hostname
                            'admindb', # username
                            'password', # password
                            'mydatabase' # db
                        );

                        $table_name = "php_docker_table";

                        $query = "SELECT * FROM $table_name";

                        $response = mysqli_query($connect, $query);

                        echo "<div class='space-y-4'>";
                        while($i = mysqli_fetch_assoc($response))
                        {
                            echo "<div class='p-4 border-b border-gray-200'>";
                            echo "<h3 class='text-xl font-bold'>".$i['title']."</h3>";
                            echo "<p class='text-gray-700'>".$i['body']."</p>";
                            echo "<p class='text-sm text-gray-500'>".$i['date_created']."</p>";
                            echo "</div>";
                        }
                        echo "</div>";
                        ?>
                    </div>
                </section>
            </main>
            <footer class="bg-gray-800 text-white p-4 text-center rounded">
                <p>&copy; 2024 My PHP Full-Stack Web App  |  Made by <a href ="https://shahathir.me" class = underline>Shahathir Iskandar<a></p>
            </footer>
        </div>
    </div>
</body>
</html>
