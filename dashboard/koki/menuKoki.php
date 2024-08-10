<?php
session_start();
require '../../Koneksi.php'; // Pastikan jalur ini benar dan koneksi berhasil

// Cek apakah variabel sesi sudah diatur
if (!isset($_SESSION['namaKoki'])) {
    header("Location: ../../login/menuKoki.php");
    exit();
}

// Tentukan jumlah item per halaman
$itemsPerPage = isset($_GET['itemsPerPage']) ? intval($_GET['itemsPerPage']) : 10;

// Tangani pembaruan stok jika formulir dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateStock'])) {
    $menuId = $_POST['menuId'];
    $newStock = intval($_POST['newStock']);

    // Update stock untuk menumakanan
    $updateQueryMakanan = "UPDATE menumakanan SET ketersediaan = ? WHERE idMenu = ?";
    $stmtMakanan = $conn->prepare($updateQueryMakanan);
    if (!$stmtMakanan) {
        die("Prepare failed: " . $conn->error);
    }
    $stmtMakanan->bind_param('is', $newStock, $menuId);
    $stmtMakanan->execute();
    $stmtMakanan->close();

    // Update stock untuk menuminuman
    $updateQueryMinuman = "UPDATE menuminuman SET ketersediaan = ? WHERE idMenu = ?";
    $stmtMinuman = $conn->prepare($updateQueryMinuman);
    if (!$stmtMinuman) {
        die("Prepare failed: " . $conn->error);
    }
    $stmtMinuman->bind_param('is', $newStock, $menuId);
    $stmtMinuman->execute();
    $stmtMinuman->close();
}

// Ambil query pencarian jika ada
$searchQuery = isset($_GET['searchQuery']) ? "%" . $_GET['searchQuery'] . "%" : "%";

// Query ke tabel menu dengan pencarian, menggabungkan menumakanan dan menuminuman
$query = "
    SELECT idMenu, namaMakanan, ketersediaan, harga 
    FROM menumakanan 
    WHERE namaMakanan LIKE ?

    UNION

    SELECT idMenu , namaMinuman, ketersediaan, harga 
    FROM menuminuman 
    WHERE namaMinuman LIKE ?

    LIMIT ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error); // Add this line
}
$stmt->bind_param('ssi', $searchQuery, $searchQuery, $itemsPerPage);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $conn->error);
}

$menuData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menuData[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Halaman Koki</title>
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
                        <a href="#"
                            class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white bg-blue-500">
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
            <img src="../../assets/icon/iconMenu.svg" alt="Icon Menu" class="w-12 h-12 mx-4" />
            <!-- Wrapper untuk tombol update dan icon resto -->
            <img src="../../assets/icon/iconResto.svg" alt="Icon Resto"
                class="flex flex-col items-end absolute right-10 top-4 w-36 h-36 mb-1" />
        </div>
        <h1 class="text-2xl font-bold py-4">Menu</h1>
        <div class="flex items-center space-x-2">
            <p class="text-gray-700">Show</p>
            <div class="relative text-left stroke-black">
                <select name="amountOrder" id="amountOrder" class="border-2 border-black p-2 rounded-lg"
                    onchange="changeItemsPerPage(this.value)">
                    <option value="5" <?php echo ($itemsPerPage == 5) ? 'selected' : ''; ?>>5</option>
                    <option value="10" <?php echo ($itemsPerPage == 10) ? 'selected' : ''; ?>>10</option>
                    <option value="20" <?php echo ($itemsPerPage == 20) ? 'selected' : ''; ?>>20</option>
                    <option value="40" <?php echo ($itemsPerPage == 40) ? 'selected' : ''; ?>>40</option>
                </select>
            </div>
            <span class="font-bold">MENU</span>
            <div class="relative flex-1">
                <form method="GET" action="">
                    <input type="text" name="searchQuery" class="border-2 border-black p-2 pl-10 rounded-lg"
                        placeholder="Search..."
                        value="<?php echo isset($_GET['searchQuery']) ? htmlspecialchars($_GET['searchQuery']) : ''; ?>" />
                    <img src="../../assets/icon/iconSearch.svg" alt="Search Icon"
                        class="absolute left-2 top-1/2 transform -translate-y-1/2 w-6 h-6" />
                    <button type="submit" class="p-3 text-sm bg-blue-200 rounded-3xl ms-1">Cari</button>
                </form>
            </div>

        </div>

        <!-- Tabel -->
        <div class="overflow-auto mt-6 rounded-2xl border-2 border-grey">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-4">Jenis</th>
                        <th class="border p-4">Menu</th>
                        <th class="border p-4">Harga</th>
                        <th class="border p-4">Stock Bahan</th>
                        <th class="border p-4">Status</th>
                        <th class="border p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menuData as $item) { ?>
                    <tr>
                        <td class="border p-4"><?php echo htmlspecialchars($item['idMenu']); ?></td>
                        <td class="border p-4"><?php echo htmlspecialchars($item['namaMakanan']); ?></td>
                        <td class="border p-4"><span
                                class="harga"><?php echo htmlspecialchars($item['harga']); ?></span></td>
                        <td class="border p-4 text-center"><?php echo htmlspecialchars($item['ketersediaan']); ?></td>
                        <td class="border p-4">
                            <div class="flex justify-center items-center">
                                <?php if ($item['ketersediaan'] >= 1) { ?>
                                <button class="p-4 bg-green-100 text-green-600 rounded-full text-sm">Ready</button>
                                <?php } else { ?>
                                <button class="p-4 bg-red-600 text-white rounded-full text-sm">Bahan Habis</button>
                                <?php } ?>
                            </div>
                        </td>
                        <td class="border p-4">
                            <div class="flex space-x-2 justify-center">
                                <button class="edit-button" data-menu-id="<?php echo $item['idMenu']; ?>"
                                    data-current-stock="<?php echo $item['ketersediaan']; ?>">
                                    <img src="../../assets/icon/iconEdit.svg" alt="Icon Edit" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Popup for Editing Stock -->
    <div id="editPopup" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div id="editBox" class="bg-white p-8 rounded-lg shadow-lg text-center text-black max-w-lg w-full rounded-3xl">
            <h2 class="text-lg mb-4">Edit Stock</h2>
            <form method="POST" action="">
                <input type="hidden" name="menuId" id="menuId">
                <input type="hidden" name="updateStock" value="1">
                <div class="mb-4">
                    <label for="newStock" class="block mb-2 text-sm font-medium">New Stock</label>
                    <input type="number" id="newStock" name="newStock"
                        class="border border-gray-300 rounded-lg w-full p-2" required>
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Update</button>
                    <button type="button" onclick="closeEditPopup()"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                </div>
            </form>
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

    function editStock(menuId, currentStock) {
        document.getElementById('menuId').value = menuId; // Set menuId ke input
        document.getElementById('newStock').value = currentStock; // Set currentStock ke input
        document.getElementById('editPopup').classList.remove('hidden'); // Tampilkan popup
    }

    function closeEditPopup() {
        document.getElementById('editPopup').classList.add('hidden'); // Sembunyikan popup
    }

    // Fungsi untuk memformat angka menjadi Rupiah
    function formatRupiah(angka) {
        return "Rp " + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Ambil semua elemen dengan class "harga" dan format angkanya
    document.querySelectorAll(".harga").forEach(function(element) {
        const angka = element.innerText;
        element.innerText = formatRupiah(angka);
    });

    // Add event listener for edit buttons
    document.querySelectorAll(".edit-button").forEach(function(button) {
        button.addEventListener("click", function() {
            const menuId = this.getAttribute("data-menu-id");
            const currentStock = this.getAttribute("data-current-stock");
            editStock(menuId, currentStock); // Panggil fungsi untuk menampilkan popup
        });
    });

    function changeItemsPerPage(value) {
        window.location.href = "?itemsPerPage=" + value;
    }
    </script>
</body>

</html>