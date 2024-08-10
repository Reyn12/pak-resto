<?php
session_start();
include('../../Koneksi.php');

// Function to fetch data from database
function fetchData($conn, $table) {
    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);
    $data = [];
    while($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

// Fetching data from each table
$koki = fetchData($conn, 'koki');
$kasir = fetchData($conn, 'kasir');
$pelayan = fetchData($conn, 'pelayan');
$meja = fetchData($conn, 'meja');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Reservation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    .hidden {
        display: none;
    }

    .active-tab {
        background-color: white;
        color: black;
    }

    .inactive-tab {
        background-color: gray;
        color: white;
    }
    </style>
</head>

<body class="bg-gray-800 text-white flex justify-center items-center min-h-screen relative">
    <div class="bg-gray-900 p-6 rounded-lg shadow-lg">
        <div class="flex justify-center mb-4">
            <button id="tab-2"
                class="tab-btn px-4 py-2 active-tab font-semibold rounded-lg mr-2 flex flex-col items-center space-y-2"
                data-tab="meja-2">
                <img src="../../assets/icon/iconMeja.svg" class="w-6 h-6">
                <span>MEJA 2 ORANG</span>
            </button>
            <button id="tab-4"
                class="tab-btn px-4 py-2 inactive-tab font-semibold rounded-lg mr-2 flex flex-col items-center space-y-2"
                data-tab="meja-4">
                <img src="../../assets/icon/iconMeja.svg" class="w-6 h-6">
                <span>MEJA 4 ORANG</span>
            </button>
            <button id="tab-8"
                class="tab-btn px-4 py-2 inactive-tab font-semibold rounded-lg flex flex-col items-center space-y-2"
                data-tab="meja-8">
                <img src="../../assets/icon/iconMeja.svg" class="w-6 h-6">
                <span>MEJA 8 ORANG</span>
            </button>
        </div>

        <!-- Meja 2 Orang -->
        <div id="meja-2" class="table-list grid grid-cols-3 gap-4">
            <div class="p-4 bg-gray-600 rounded-lg text-center cursor-pointer" data-table="1">
                <div class="mb-2">1</div>
                <div class="mb-4">
                    <img src="../../assets/images/iconMeja2OrangNoBG.png" alt="Meja 2 Orang" class="mx-auto mb-4"
                        style="width: 40%; height: 40%">
                </div>
                <button class="status-btn px-4 py-2 bg-red-500 text-white font-semibold rounded-lg">Kosong</button>
            </div>
            <div class="p-4 bg-gray-600 rounded-lg text-center cursor-pointer" data-table="2">
                <div class="mb-2">2 </div>
                <div class="mb-4">
                    <img src="../../assets/images/iconMeja2OrangNoBG.png" alt="Meja 2 Orang" class="mx-auto mb-4"
                        style="width: 40%; height: 40%">
                </div>
                <button class="status-btn px-4 py-2 bg-red-500 text-white font-semibold rounded-lg">Kosong</button>
            </div>
            <div class="p-4 bg-gray-600 rounded-lg text-center cursor-pointer" data-table="3">
                <div class="mb-2">3</div>
                <div class="mb-4">
                    <img src="../../assets/images/iconMeja2OrangNoBG.png" alt="Meja 2 Orang" class="mx-auto mb-4"
                        style="width: 40%; height: 40%">
                </div>
                <button class="status-btn px-4 py-2 bg-red-500 text-white font-semibold rounded-lg">Kosong</button>
            </div>
            <!-- Add more table elements as needed -->
        </div>

        <!-- Meja 4 Orang -->
        <div id="meja-4" class="table-list grid grid-cols-3 gap-4 hidden">
            <div class="p-4 bg-gray-600 rounded-lg text-center cursor-pointer" data-table="4">
                <div class="mb-2">4</div>
                <div class="mb-4">
                    <img src="../../assets/images/iconMeja4Orang.png" alt="Meja 4 Orang" class="mx-auto mb-4"
                        style="width: 40%; height: 40%">
                </div>
                <button class="status-btn px-4 py-2 bg-red-500 text-white font-semibold rounded-lg">Kosong</button>
            </div>
            <div class="p-4 bg-gray-600 rounded-lg text-center cursor-pointer" data-table="5">
                <div class="mb-2">5</div>
                <div class="mb-4">
                    <img src="../../assets/images/iconMeja4Orang.png" alt="Meja 4 Orang" class="mx-auto mb-4"
                        style="width: 40%; height: 40%">
                </div>
                <button class="status-btn px-4 py-2 bg-red-500 text-white font-semibold rounded-lg">Kosong</button>
            </div>
            <div class="p-4 bg-gray-600 rounded-lg text-center cursor-pointer" data-table="6">
                <div class="mb-2">6</div>
                <div class="mb-4">
                    <img src="../../assets/images/iconMeja4Orang.png" alt="Meja 4 Orang" class="mx-auto mb-4"
                        style="width: 40%; height: 40%">
                </div>
                <button class="status-btn px-4 py-2 bg-red-500 text-white font-semibold rounded-lg">Kosong</button>
            </div>
            <!-- Add more table elements as needed -->
        </div>

        <!-- Meja 8 Orang -->
        <div id="meja-8" class="table-list grid grid-cols-3 gap-4 hidden">
            <div class="p-4 bg-gray-600 rounded-lg text-center cursor-pointer" data-table="7">
                <div class="mb-2">7</div>
                <div class="mb-4">
                    <img src="../../assets/images/iconMeja8Orang.png" alt="Meja 8 Orang" class="mx-auto mb-4"
                        style="width: 40%; height: 40%">
                </div>
                <button class="status-btn px-4 py-2 bg-red-500 text-white font-semibold rounded-lg">Kosong</button>
            </div>
            <div class="p-4 bg-gray-600 rounded-lg text-center cursor-pointer" data-table="8">
                <div class="mb-2">8</div>
                <div class="mb-4">
                    <img src="../../assets/images/iconMeja8Orang.png" alt="Meja 8 Orang" class="mx-auto mb-4"
                        style="width: 40%; height: 40%">
                </div>
                <button class="status-btn px-4 py-2 bg-red-500 text-white font-semibold rounded-lg">Kosong</button>
            </div>
            <div class="p-4 bg-gray-600 rounded-lg text-center cursor-pointer" data-table="9">
                <div class="mb-2">9</div>
                <div class="mb-4">
                    <img src="../../assets/images/iconMeja8Orang.png" alt="Meja 8 Orang" class="mx-auto mb-4"
                        style="width: 40%; height: 40%">
                </div>
                <button class="status-btn px-4 py-2 bg-red-500 text-white font-semibold rounded-lg">Kosong</button>
            </div>
            <!-- Add more table elements as needed -->
        </div>
    </div>

    <div id="popup" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center hidden">
        <div class="bg-white text-gray-800 p-6 rounded-lg shadow-lg text-center">
            <div id="popup-table-number" class="text-2xl mb-4 flex flex-col items-center space-y-2">
                <img src="../../assets/images/edit.png" class="w-10 h-10">
            </div>
            <div class="mb-4">Apakah yakin untuk mengisi Meja nomor <span id="popup-table-id">1</span>?</div>
            <button id="confirm-btn" class="px-4 py-2 bg-pink-500 text-white font-semibold rounded-lg mr-2">ISI</button>
            <button id="cancel-btn" class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg">KEMBALI</button>
        </div>
    </div>

    <div id="success-popup" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center hidden">
        <div class="bg-white text-gray-800 p-6 rounded-lg shadow-lg text-center">
            <div class="mb-4">
                <img src="../../assets/images/smile.png" alt="Success" class="mx-auto mb-4"
                    style="width: 50px; height: 50px">
            </div>
            <div class="mb-4">MEJA BERHASIL DIPESAN!!!<br />SILAHKAN LANJUT KE HALAMAN BERIKUTNYA UNTUK ORDER!!</div>

            <!-- Input Nama Pelanggan -->
            <div class="mb-4">
                <label for="nama-pelanggan">Nama Pelanggan:</label>
                <input id="nama-pelanggan" type="text" class="block w-full mt-1 p-2 rounded border-gray-300">
            </div>

            <!-- Dropdowns -->
            <div class="mb-4">
                <label for="select-koki">Pilih Koki:</label>
                <select id="select-koki" class="block w-full mt-1 p-2 rounded border-gray-300">
                    <?php foreach($koki as $k): ?>
                    <option value="<?= $k['idKoki'] ?>"><?= $k['namaKoki'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="select-kasir">Pilih Kasir:</label>
                <select id="select-kasir" class="block w-full mt-1 p-2 rounded border-gray-300">
                    <?php foreach($kasir as $k): ?>
                    <option value="<?= $k['idKasir'] ?>"><?= $k['namaKasir'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="select-pelayan">Pilih Pelayan:</label>
                <select id="select-pelayan" class="block w-full mt-1 p-2 rounded border-gray-300">
                    <?php foreach($pelayan as $p): ?>
                    <option value="<?= $p['idPelayan'] ?>"><?= $p['namaPelayan'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="select-meja">Pilih Meja:</label>
                <select id="select-meja" class="block w-full mt-1 p-2 rounded border-gray-300">
                    <?php foreach($meja as $k): ?>
                    <option value="<?= $k['noMeja'] ?>"><?= $k['Nomor Meja'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button id="order-food-btn" class="px-4 py-2 bg-black text-white font-semibold rounded-lg mr-2">PESAN
                MAKANAN</button>
            <button id="success-cancel-btn"
                class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg">KEMBALI</button>
        </div>
    </div>

    <div id="occupied-popup" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center hidden">
        <div class="bg-white text-gray-800 p-6 rounded-lg shadow-lg text-center">
            <div id="occupied-table-number" class="text-2xl mb-4">1</div>
            <div class="mb-4">SILAHKAN PILIH MENU!!!</div>
            <button id="empty-btn" class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg mr-2">Kosongkan
                Meja</button>
            <button id="order-btn" class="px-4 py-2 bg-pink-500 text-white font-semibold rounded-lg mr-2">PESAN
                MAKANAN</button>
            <button id="occupied-cancel-btn"
                class="px-4 py-2 bg-gray-500 text-white font-semibold rounded-lg">KEMBALI</button>
        </div>
    </div>

    <!-- Logout Button -->
    <button class="absolute top-4 right-4 px-4 py-2 bg-red-500 text-white font-semibold rounded-lg"
        onclick="logout()">Logout</button>

    <script>
    const tableButtons = document.querySelectorAll(".status-btn");
    const popup = document.getElementById("popup");
    const successPopup = document.getElementById("success-popup");
    const occupiedPopup = document.getElementById("occupied-popup");
    const popupTableId = document.getElementById("popup-table-id");
    const confirmBtn = document.getElementById("confirm-btn");
    const emptyBtn = document.getElementById("empty-btn");
    const orderBtn = document.getElementById("order-btn");
    let selectedTable = null;

    // Load table statuses from local storage
    document.addEventListener("DOMContentLoaded", () => {
        tableButtons.forEach((button) => {
            const tableId = button.parentElement.getAttribute("data-table");
            const status = localStorage.getItem(`table-${tableId}`);
            if (status === "Terisi") {
                button.textContent = "Terisi";
                button.classList.remove("bg-red-500");
                button.classList.add("bg-green-500");
            }
        });
    });

    tableButtons.forEach((button) => {
        button.addEventListener("click", () => {
            selectedTable = button;
            const tableId = button.parentElement.getAttribute("data-table");
            if (button.textContent === "Kosong") {
                popupTableId.textContent = tableId;
                popup.classList.remove("hidden");
            } else {
                document.getElementById("occupied-table-number").textContent = tableId;
                occupiedPopup.classList.remove("hidden");
            }
        });
    });

    confirmBtn.addEventListener("click", () => {
        if (selectedTable) {
            const tableId = selectedTable.parentElement.getAttribute("data-table");
            selectedTable.textContent = "Terisi";
            selectedTable.classList.remove("bg-red-500");
            selectedTable.classList.add("bg-green-500");
            localStorage.setItem(`table-${tableId}`, "Terisi");
            localStorage.setItem("tableNumber", tableId); // Store the table number
            popup.classList.add("hidden");
            successPopup.classList.remove("hidden");
        }
    });

    emptyBtn.addEventListener("click", () => {
        if (selectedTable) {
            const tableId = selectedTable.parentElement.getAttribute("data-table");
            selectedTable.textContent = "Kosong";
            selectedTable.classList.remove("bg-green-500");
            selectedTable.classList.add("bg-red-500");
            localStorage.removeItem(`table-${tableId}`);
            occupiedPopup.classList.add("hidden");
        }
    });

    orderBtn.addEventListener("click", () => {
        successPopup.classList.remove("hidden");
        occupiedPopup.classList.add("hidden");
    });

    document.getElementById("cancel-btn").addEventListener("click", () => {
        popup.classList.add("hidden");
    });

    document.getElementById("success-cancel-btn").addEventListener("click", () => {
        successPopup.classList.add("hidden");
    });

    document.getElementById("occupied-cancel-btn").addEventListener("click", () => {
        occupiedPopup.classList.add("hidden");
    });

    document.getElementById("order-food-btn").addEventListener("click", () => {
        // Handle koki, kasir, pelayan, and pelanggan selection here
        const selectedKoki = document.getElementById("select-koki").value;
        const selectedKasir = document.getElementById("select-kasir").value;
        const selectedPelayan = document.getElementById("select-pelayan").value;
        const namaPelanggan = document.getElementById("nama-pelanggan").value;
        const selectedmeja = document.getElementById("select-meja").value;

        // Store the selected koki, kasir, pelayan, and pelanggan in local storage
        localStorage.setItem("selectedKoki", selectedKoki);
        localStorage.setItem("selectedKasir", selectedKasir);
        localStorage.setItem("selectedPelayan", selectedPelayan);
        localStorage.setItem("namaPelanggan", namaPelanggan);
        localStorage.setItem("selectedmeja", selectedmeja);

        console.log(
            `Selected Koki: ${selectedKoki}, Kasir: ${selectedKasir}, Pelayan: ${selectedPelayan}, Pelanggan: ${namaPelanggan},Selected meja: ${selectedmeja}`
        );
        window.location.href = "menuPesanMakanan.php";
    });

    const tabButtons = document.querySelectorAll(".tab-btn");
    const tableLists = document.querySelectorAll(".table-list");

    tabButtons.forEach((button) => {
        button.addEventListener("click", () => {
            tabButtons.forEach((btn) => {
                btn.classList.remove("active-tab");
                btn.classList.add("inactive-tab");
            });
            button.classList.add("active-tab");
            button.classList.remove("inactive-tab");

            const targetTab = button.getAttribute("data-tab");
            tableLists.forEach((list) => {
                list.classList.add("hidden");
            });
            document.getElementById(targetTab).classList.remove("hidden");
        });
    });

    // Logout function
    function logout() {
        // Clear local storage
        localStorage.clear();
        // Redirect to the login page
        window.location.href = "../../login/loginPelayan.php";
    }
    </script>
</body>

</html>