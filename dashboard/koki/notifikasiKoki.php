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

// Check if the query was successful
if ($result === false) {
    die("Query failed: " . $conn->error); // Output the error for debugging
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mendapatkan data JSON dari permintaan
    $data = json_decode(file_get_contents('php://input'), true);
    $idPesanan = $data['idPesanan'];

    // Persiapkan dan eksekusi pernyataan SQL untuk memperbarui status pesanan
    $sql = "UPDATE pesanan SET statusPesanan = 'Selesai' WHERE idPesanan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idPesanan);

    $response = array();
    if ($stmt->execute()) {
        // Jika eksekusi berhasil, kirimkan respons sukses
        $response['success'] = true;
    } else {
        // Jika eksekusi gagal, kirimkan respons gagal
        $response['success'] = false;
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();

    // Mengirimkan respons sebagai JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
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
                        <a href="pesananKoki.php"
                            class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white">
                            <!-- Icon Pesanan -->
                            <img src="../../assets/icon/iconPesanan.svg" alt="Icon Pesanan"
                                class="h-10 w-10 text-white mr-4" />
                            <span>Pesanan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white bg-blue-900">
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
            <img src="../../assets/icon/iconNotif.svg" alt="Icon Notifikasi" class="w-12 h-12 mx-4" />
            <!-- Wrapper untuk tombol update dan icon resto -->
            <img src="../../assets/icon/iconResto.svg" alt="Icon Resto"
                class="flex flex-col items-end absolute right-10 top-4 w-36 h-36 mb-1" />
        </div>
        <h1 class="text-2xl font-bold py-4">Notifikasi</h1>
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
                        <th class="border p-4">Action</th>
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
                            echo "<td class='border p-4'><div class='flex justify-center items-center'><button class='p-4 bg-blue-100 text-blue-600 rounded-full text-sm done-button' data-id='" . $row["idPesanan"] . "'>Done</button></div></td>";
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
            <p class="font-bold text-xl mb-4">Logout Berhasil!</p>
            <p class="text-white mb-6">Anda berhasil logout dari sistem.</p>
            <button id="confirmLogoutButton"
                class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-3xl w-full">Tutup</button>
        </div>
    </div>

    <!-- Script untuk pop-up dan logout -->
    <script>
    // Fungsi untuk membuka pop-up
    function openPopup() {
        document.getElementById("popup").classList.remove("hidden");
    }

    // Fungsi untuk menutup pop-up
    function closePopup() {
        document.getElementById("popup").classList.add("hidden");
    }

    // Event listener untuk membuka pop-up saat link di klik
    const productLinks = document.querySelectorAll(".product-link1");
    productLinks.forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault();
            openPopup();
        });
    });

    // Event listener untuk menutup pop-up saat tombol "Close" di klik
    document.getElementById("close-popup").addEventListener("click", closePopup);

    // Script untuk logout button
    document.getElementById("logoutButton").addEventListener("click", function() {
        // Tampilkan pop-up logout
        document.getElementById("logoutPopup").classList.remove("hidden");

        // Tambahkan animasi fade-in
        const alertBox = document.getElementById("alertBox");
        alertBox.classList.add("animate-fade-in");

        // Setelah 1 detik, tambahkan animasi fade-out dan sembunyikan pop-up
        setTimeout(() => {
            alertBox.classList.remove("animate-fade-in");
            alertBox.classList.add("animate-fade-out");

            setTimeout(() => {
                document.getElementById("logoutPopup").classList.add("hidden");
                alertBox.classList.remove("animate-fade-out");
                window.location.href =
                    "../../login/loginKoki.php"; // Ganti dengan URL halaman login Anda
            }, 300); // Waktu animasi fade-out
        }, 1000); // Waktu menunggu sebelum fade-out
    });

    // Event listener untuk tombol "Tutup" di pop-up logout
    document.getElementById("confirmLogoutButton").addEventListener("click", function() {
        // Hapus animasi fade-in, tambahkan animasi fade-out, dan sembunyikan pop-up
        const alertBox = document.getElementById("alertBox");
        alertBox.classList.remove("animate-fade-in");
        alertBox.classList.add("animate-fade-out");

        setTimeout(() => {
            document.getElementById("logoutPopup").classList.add("hidden");
            alertBox.classList.remove("animate-fade-out");
        }, 300); // Waktu animasi fade-out
    });

    // Script untuk mengubah status pesanan saat tombol "Done" diklik
    const doneButtons = document.querySelectorAll(".done-button");
    doneButtons.forEach(button => {
        button.addEventListener("click", function() {
            const orderId = this.getAttribute("data-id");

            fetch("notifikasiKoki.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        idPesanan: orderId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Cari baris yang sesuai dengan idPesanan ini
                        const row = this.closest('tr');

                        // Temukan tombol status dalam baris tersebut
                        const statusButton = row.querySelector('.status-button');

                        // Hanya ubah status jika tombol status ditemukan
                        if (statusButton) {
                            statusButton.classList.remove('bg-yellow-300', 'text-white');
                            statusButton.classList.add('bg-green-100', 'text-green-600');
                            statusButton.textContent = 'Selesai Dimasak';
                        }

                        // Hapus tombol "Done" setelah update sukses
                        this.remove();
                    } else {
                        alert("Failed to update order status");
                    }
                })
                .catch(error => console.error("Error:", error));
        });
    });
    </script>
</body>

</html>