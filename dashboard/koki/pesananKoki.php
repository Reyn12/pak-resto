<?php
session_start();
require '../../Koneksi.php'; // Pastikan jalur ini benar dan koneksi berhasil

// Cek apakah variabel sesi sudah diatur
if (!isset($_SESSION['namaKoki'])) {
    header("Location: ../../login/menuKoki.php");
    exit();
}

// Default jumlah hasil yang ditampilkan
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;

// Query untuk mengambil data dari database dengan limit
$sql = "
    SELECT pesanan.noMeja, pesanan.idPesanan, menu.idMenu, menu.namaMakananMinuman, pesanan.statusPesanan
    FROM pesanan
    JOIN (
        SELECT idMenu, namaMakanan AS namaMakananMinuman
        FROM menumakanan
        UNION
        SELECT idMenu, namaMinuman AS namaMakananMinuman
        FROM menuminuman
    ) AS menu ON menu.idMenu = pesanan.idMenu
    LIMIT $limit";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pesanan Koki</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    </style>
</head>

<body class="flex bg-white">
    <!-- Side Navbar -->
    <div class="w-64 bg-gray-800 text-white min-h-screen p-4 flex flex-col justify-between">
        <div>
            <div class="flex flex-col items-center mb-8">
                <div class="rounded-full flex items-center justify-center">
                    <!-- Icon Orang -->
                    <img src="../../assets/icon/iconKoki.svg" alt="Icon Orang" class="w-36 h-36" />
                </div>
                <div class="mt-2 text-center text-lg">
                    <p class="text-base mb-2">Welcome,</p>
                    <p class="font-bold"><?php echo htmlspecialchars($_SESSION['namaKoki']); ?></p>
                </div>
            </div>

            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="menuKoki.php"
                            class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white">
                            <!-- Icon Menu -->
                            <img src="../../assets/icon/iconMenu.svg" alt="Icon Menu"
                                class="h-10 w-10 text-white mr-4" />
                            <span>Menu</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="#"
                            class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white bg-blue-900">
                            <!-- Icon Pesanan -->
                            <img src="../../assets/icon/iconPesanan.svg" alt="Icon Pesanan"
                                class="h-10 w-10 text-white mr-4" />
                            <span>Pesanan</span>
                        </a>
                    </li>
                    <li>
                        <a href="notifikasiKoki.php"
                            class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white">
                            <!-- Icon Notifikasi -->
                            <img src="../../assets/icon/iconNotif.svg" alt="Icon Notifikasi"
                                class="h-10 w-10 text-white mr-4" />
                            <span>Notifikasi</span>
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

    <!-- Body -->
    <div class="flex-1 p-12 relative">
        <!-- Konten body akan ditempatkan di sini -->
        <div class="flex items-center justify-between">
            <img src="../../assets/icon/iconPesanan.svg" alt="Icon Pesanan" class="w-12 h-12 mx-4" />
            <!-- Wrapper untuk tombol update dan icon resto -->
            <img src="../../assets/icon/iconResto.svg" alt="Icon Resto"
                class="flex flex-col items-end absolute right-10 top-4 w-36 h-36 mb-1" />
        </div>
        <h1 class="text-2xl font-bold py-4">Pesanan</h1>
        <div class="flex items-center space-x-2">
            <p class="text-gray-700">Show</p>
            <div class="relative text-left stroke-black">
                <form method="GET" action="">
                    <select name="limit" id="amountOrder" class="border-2 border-black p-2 rounded-lg"
                        onchange="this.form.submit()">
                        <option value="2" <?php echo $limit == 2 ? 'selected' : ''; ?>>2</option>
                        <option value="10" <?php echo $limit == 10 ? 'selected' : ''; ?>>10</option>
                        <option value="30" <?php echo $limit == 30 ? 'selected' : ''; ?>>30</option>
                        <option value="50" <?php echo $limit == 50 ? 'selected' : ''; ?>>50</option>
                    </select>
                </form>
            </div>

        </div>
        <!-- Tabel -->
        <div class="overflow-auto mt-6 rounded-2xl border-2 border-grey">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-4">Meja</th>
                        <th class="border p-4">Kode Pesanan</th>
                        <th class="border p-4">Kode Menu</th>
                        <th class="border p-4">Nama Menu</th>
                        <th class="border p-4">Status</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='text-center'>";
                            echo "<td class='border p-4'>" . $row["noMeja"] . "</td>";
                            echo "<td class='border p-4'><a href='#' class='text-blue-500 product-link1'>" . $row["idPesanan"] . "</a></td>";
                            echo "<td class='border p-4'>" . $row["idMenu"] . "</td>";
                            echo "<td class='border p-4'>" . $row["namaMakananMinuman"] . "</td>";
                            echo "<td class='border p-4'><div class='flex space-x-2 justify-center items-center'>";
                            if ($row["statusPesanan"] == 'Selesai') {
                                echo "<button class='p-4 bg-green-100 text-green-600 rounded-full text-sm'>Selesai Dimasak</button>";
                            } else {
                                echo "<button class='p-4 bg-yellow-300 text-white rounded-full text-sm status-button'>Sedang Dimasak</button>";
                            }
                            echo "</div></td>";
                           
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center p-4'>No data found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    <!-- Pop-up detail pesanan -->
    <div id="popup" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-4">Product Info</h2>
            <p id="popup-content">This is the detailed information for product ID: P001</p>
            <button id="close-popup" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Close</button>
        </div>
    </div>
    <!-- Popup Alert -->
    <div id="logoutPopup" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div id="alertBox"
            class="bg-blue-900 p-8 rounded-lg shadow-lg text-center text-white max-w-lg w-full rounded-3xl">
            <img src="../../assets/icon/iconLogout.svg" alt="Icon Logout Berhasil" class="w-16 h-16 mx-auto mb-6" />
            <p class="mb-6 text-lg">Anda berhasil Logout</p>
            <button id="okButton"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold w-24 h-10 rounded-lg">OK</button>
        </div>
    </div>

    <script>
    document.getElementById("logoutButton").addEventListener("click", function() {
        const alertBox = document.getElementById("alertBox");
        document.getElementById("logoutPopup").classList.remove("hidden");
        alertBox.classList.remove("animate-fade-out");
        alertBox.classList.add("animate-fade-in");
    });

    document.getElementById("okButton").addEventListener("click", function() {
        const alertBox = document.getElementById("alertBox");
        alertBox.classList.remove("animate-fade-in");
        alertBox.classList.add("animate-fade-out");
        alertBox.addEventListener(
            "animationend",
            function() {
                document.getElementById("logoutPopup").classList.add("hidden");
                window.location.href = "../../login/loginKoki.php";
            }, {
                once: true
            }
        );
    });

    const productLinks = document.querySelectorAll(".product-link");
    productLinks.forEach((link) => {
        link.addEventListener("click", function(event) {
            event.preventDefault();
            const productId = this.textContent;
            const popupContent = document.getElementById("popup-content");
            popupContent.textContent = `This is the detailed information for product ID: ${productId}`;
            document.getElementById("popup").classList.remove("hidden");
        });
    });

    document.getElementById("close-popup").addEventListener("click", function() {
        document.getElementById("popup").classList.add("hidden");
    });
    </script>
</body>

</html>