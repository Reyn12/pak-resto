<?php
session_start();
require '../Koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM koki WHERE namaKoki = '$username' AND passwordKoki = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $_SESSION['namaKoki'] = $username;
        header("Location: ../dashboard/koki/menuKoki.php");
    } else {
        echo "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Koki Login</title>
    <link rel="stylesheet" href="../style.css" />
</head>

<body class="bg-gray-900 h-screen flex items-center justify-center">
    <div class="flex w-3/4 max-w-4xl bg-white shadow-lg overflow-hidden relative rounded-2xl">
        <a href="../index.html" class="absolute top-4 left-4 flex items-center text-white">
            <img src="../assets/images/left-arrow.svg" alt="Back" class="w-6 h-6 mr-2" />
            Kembali
        </a>
        <div class="w-1/2 bg-gray-800 p-10 flex items-center justify-center">
            <h2 class="text-3xl font-semibold text-white text-center">
                Makanan lezat adalah karya seni kami. <br /><br />
                Masaklah dengan cinta, keterampilan, dan dedikasi penuh!
            </h2>
        </div>
        <div class="w-1/2 p-10">
            <div class="flex flex-col items-center">
                <img src="../assets/images/logoResto.jpg" alt="Logo" class="mb-4" />
                <h1 class="text-2xl font-bold mb-2">Log In Koki</h1>
                <h2 class="text-xl font-semibold mb-6">Smart Canteen</h2>
                <p class="text-sm text-gray-600 mb-6">Log in menggunakan Username dan Password anda</p>

                <form class="w-full" method="POST" action="">
                    <div class="mb-6">
                        <label for="username"
                            class="block mb-2 text-sm font-medium text-blue-700 dark:text-blue-500">Username</label>
                        <input type="text" id="username" name="username"
                            class="bg-blue-50 border border-blue-500 text-blue-900 dark:text-blue-400 placeholder-blue-700 dark:placeholder-blue-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-200 dark:border-blue-500"
                            placeholder="Username" required />
                    </div>
                    <div class="mb-6">
                        <label for="password"
                            class="block mb-2 text-sm font-medium text-blue-700 dark:text-blue-500">Password</label>
                        <input type="password" id="password" name="password"
                            class="bg-blue-50 border border-blue-500 text-blue-900 dark:text-blue-400 placeholder-blue-700 dark:placeholder-blue-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-200 dark:border-blue-500"
                            placeholder="*********" required />
                    </div>
                    <div class="flex items-center justify-between">
                        <button
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full rounded-3xl"
                            type="submit">
                            Log In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>