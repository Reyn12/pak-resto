<?php
include '../../Koneksi.php';

// Ambil data dari database
$koki_count = $conn->query("SELECT COUNT(*) as count FROM koki")->fetch_assoc()['count'];
$pelayan_count = $conn->query("SELECT COUNT(*) as count FROM pelayan")->fetch_assoc()['count'];
$kasir_count = $conn->query("SELECT COUNT(*) as count FROM kasir")->fetch_assoc()['count'];

// Query untuk menghitung jumlah menu dari kedua tabel
$query = "
    SELECT idMenu, namaMakanan, ketersediaan, harga 
    FROM menumakanan 
    WHERE namaMakanan LIKE '%'

    UNION

    SELECT idMenu , namaMinuman, ketersediaan, harga 
    FROM menuminuman 
    WHERE namaMinuman LIKE '%'
";
$result = $conn->query($query);
$menu_count = $result->num_rows;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
    .hidden {
        display: none;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: scale(1);
        }

        to {
            opacity: 0;
            transform: scale(0.8);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s forwards;
    }

    .animate-fade-out {
        animation: fadeOut 0.3s forwards;
    }

    .status-lunas {
        background-color: green;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        border: none;
        cursor: default;
    }

    .status-belum-lunas {
        background-color: yellow;
        color: black;
        padding: 5px 10px;
        border-radius: 5px;
        border: none;
        cursor: default;
    }
    </style>
</head>

<body class="flex bg-white">
    <!-- Side Navbar -->
    <div class="w-64 bg-gray-800 text-white min-h-screen p-4 flex flex-col justify-between">
        <div>
            <div class="flex flex-col items-center mb-8">
                <div class="rounded-full flex items-center justify-center">
                    <!-- Icon Orang -->
                    <img src="../../assets/icon/iconAdministrator.svg" alt="Icon Orang" class="w-36 h-36" />
                </div>
                <div class="mt-2 text-center text-lg">
                    <p class="text-base mb-2">Welcome, Admin</p>
                </div>
            </div>

            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="adminDashboard.php"
                            class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white bg-blue-500">
                            <!-- Icon Menu -->
                            <img src="../../assets/icon/iconDashboard.svg" alt="Icon Menu"
                                class="h-10 w-10 text-white mr-4" />
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-4 relative">
                        <a href="#" class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white"
                            onclick="toggleDropdown(event)">
                            <!-- Icon Pesanan -->
                            <img src="../../assets/icon/IconTeam.svg" alt="Icon Pesanan"
                                class="h-10 w-10 text-white mr-4" />
                            <span>Team</span>
                        </a>
                        <!-- Dropdown menu -->
                        <ul id="teamDropdown" class="hidden absolute bg-gray-800 text-white rounded-lg mt-2 w-full">
                            <li class="p-3 hover:bg-blue-300">
                                <a href="koki.php">Koki</a>
                            </li>
                            <li class="p-3 hover:bg-blue-300">
                                <a href="pelayan.php">Pelayan</a>
                            </li>
                            <li class="p-3 hover:bg-blue-300">
                                <a href="kasir.php">Kasir</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="adminMenu.php"
                            class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white">
                            <!-- Icon Notifikasi -->
                            <img src="../../assets/icon/IconMenu1.svg" alt="Icon Notifikasi"
                                class="h-10 w-10 text-white mr-4" />
                            <span>Menu</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <div class="mt-8">
            <button id="logoutButton"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-3xl">Logout</button>
        </div>
    </div>

    <!-- Main content -->
    <div class="w-3/4 p-10">
        <div class="grid grid-cols-4 gap-4 mb-10">
            <div class="bg-purple-200 p-5 rounded-2xl text-center flex flex-col items-center">
                <div class="flex w-20 h-20 items-center justify-center mb-6">
                    <img src="../../assets/icon/iconKoki.svg" alt="iconKoki">
                </div>
                <h2 class="text-2xl font-bold"><?php echo $koki_count; ?> Koki</h2>
            </div>
            <div class="bg-purple-200 p-5 rounded-2xl text-center flex flex-col items-center">
                <div class="flex w-20 h-20 items-center justify-center mb-6">
                    <img src="../../assets/icon/iconWaitress.svg" alt="iconPelayan">
                </div>
                <h2 class="text-2xl font-bold"><?php echo $pelayan_count; ?> Pelayan</h2>
            </div>
            <div class="bg-purple-200 p-5 rounded-2xl text-center flex flex-col items-center">
                <div class="flex w-20 h-20 items-center justify-center mb-6">
                    <img src="../../assets/icon/iconCashier.svg" alt="iconKasir">
                </div>
                <h2 class="text-2xl font-bold"><?php echo $kasir_count; ?> Kasir</h2>
            </div>
            <div class="bg-purple-200 p-5 rounded-2xl text-center flex flex-col items-center">
                <div class="flex w-20 h-20 items-center justify-center mb-6">
                    <img src="../../assets/icon/IconMenu1.svg" alt="IconMenu1">
                </div>
                <h2 class="text-2xl font-bold"><?php echo $menu_count; ?> Menu</h2>
            </div>
        </div>
        <!-- Tabel -->
        <table class="min-w-full bg-white items-center text-center rounded-2xl border-gray-200 border-2">
            <thead class="bg-gray-200 border-2">
                <tr>
                    <th class="py-2">ID Pesanan</th>
                    <th class="py-2">ID Pelanggan</th>
                    <th class="py-2">ID Menu</th>
                    <th class="py-2">Jumlah</th>
                    <th class="py-2">No Meja</th>
                    <th class="py-2">Tanggal Pesanan</th>
                    <th class="py-2">Total Harga</th>
                    <th class="py-2">Status Pembayaran</th>
                    <th class="py-2">Status Pesanan</th>
                </tr>
            </thead>
            <tbody>
                <?php
    $sql = "SELECT idPesanan, idPelanggan, idMenu, jumlah, noMeja, tanggalPesanan, totalHarga, statusPembayaran, statusPesanan FROM pesanan";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Output data dari setiap row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td class='py-2'>" . $row["idPesanan"] . "</td>";
            echo "<td class='py-2'>" . $row["idPelanggan"] . "</td>";
            echo "<td class='py-2'>" . $row["idMenu"] . "</td>";
            echo "<td class='py-2'>" . $row["jumlah"] . "</td>";
            echo "<td class='py-2'>" . $row["noMeja"] . "</td>";
            echo "<td class='py-2'>" . $row["tanggalPesanan"] . "</td>";
            echo "<td class='py-2'>" . $row["totalHarga"] . "</td>";

            // Tambahkan kondisi untuk status pembayaran menggunakan button
            if ($row["statusPembayaran"] == 'Lunas') {
                echo "<td class='py-2'><button class='status-lunas'>" . $row["statusPembayaran"] . "</button></td>";
            } else {
                echo "<td class='py-2'><button class='status-belum-lunas'>" . $row["statusPembayaran"] . "</button></td>";
            }
             // Tambahkan kondisi untuk status pesanan menggunakan button
             if ($row["statusPesanan"] == 'Selesai') {
                echo "<td class='py-2'><button class='status-lunas'>" . $row["statusPesanan"] . "</button></td>";
            } else {
                echo "<td class='py-2'><button class='status-belum-lunas'>" . $row["statusPesanan"] . "</button></td>";
            }

            echo "</tr>";

            
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='9' class='py-2'>No orders found</td></tr>";
    }
    ?>
            </tbody>
        </table>


    </div>

    <!-- Logout Modal -->
    <div id="logoutPopup" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div id="alertBox" class="bg-white p-8 rounded-lg animate-fade-in">
            <h2 class="text-2xl font-bold mb-4">Logout</h2>
            <p>Apakah Anda yakin ingin logout?</p>
            <div class="mt-6 flex justify-end">
                <button id="cancelButton" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2"
                    onclick="closeLogoutModal()">Batal</button>
                <button id="okButton" class="bg-red-500 text-white px-4 py-2 rounded-lg">Logout</button>
            </div>
        </div>
    </div>

    <script>
    function toggleDropdown(event) {
        event.preventDefault();
        const dropdown = document.getElementById('teamDropdown');
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden', 'animate-fade-out');
            dropdown.classList.add('animate-fade-in');
        } else {
            dropdown.classList.remove('animate-fade-in');
            dropdown.classList.add('animate-fade-out');
            setTimeout(() => dropdown.classList.add('hidden'), 300);
        }
    }

    function showLogoutModal() {
        document.getElementById('logoutPopup').classList.remove('hidden');
        document.getElementById('alertBox').classList.remove('animate-fade-out');
        document.getElementById('alertBox').classList.add('animate-fade-in');
    }

    function closeLogoutModal() {
        const alertBox = document.getElementById('alertBox');
        alertBox.classList.remove('animate-fade-in');
        alertBox.classList.add('animate-fade-out');
        alertBox.addEventListener('animationend', function() {
            document.getElementById('logoutPopup').classList.add('hidden');
        }, {
            once: true
        });
    }

    document.getElementById("logoutButton").addEventListener("click", function() {
        showLogoutModal();
    });

    document.getElementById("okButton").addEventListener("click", function() {
        // Tambahkan permintaan ke server untuk menghancurkan sesi
        fetch('logout.php', {
            method: 'POST'
        }).then(() => {
            // Pengalihan setelah logout
            window.location.href = "../../login/loginAdmin.php";
        }).catch((error) => {
            console.error('Error:', error);
        });
    });

    document.getElementById("cancelButton").addEventListener("click", function() {
        closeLogoutModal();
    });
    </script>
</body>

</html>