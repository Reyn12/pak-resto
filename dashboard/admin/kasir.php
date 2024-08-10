<?php
include '../../Koneksi.php';

// Menangani pengiriman form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no = $_POST['no'];
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $password = $_POST['password'];

    $sql_insert = "INSERT INTO kasir (idKasir, namaKasir, passwordKasir) VALUES ('$id', '$nama', '$password')";
    if ($conn->query($sql_insert) === TRUE) {
        echo "success";
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
    exit();
}

// Query untuk mengambil data kasir
$sql = "SELECT idKasir, namaKasir, passwordKasir FROM kasir";
$result = $conn->query($sql);

if ($result === false) {
    // Menangani kesalahan dalam eksekusi query
    die("Error executing query: " . $conn->error);
}
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
            opacity: 0.8;
            transform: scale(0);
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
                            class="flex items-center text-base p-3 rounded-lg hover:bg-blue-300 hover:text-white">
                            <!-- Icon Menu -->
                            <img src="../../assets/icon/iconDashboard.svg" alt="Icon Menu"
                                class="h-10 w-10 text-white mr-4" />
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-4 relative">
                        <a href="#"
                            class="flex items-center text-base p-3 rounded-lg hover:bg-blue-300 hover:text-white"
                            onclick="toggleDropdown(event)">
                            <!-- Icon Pesanan -->
                            <img src="../../assets/icon/IconTeam.svg" alt="Icon Pesanan"
                                class="h-10 w-10 text-white mr-4" />
                            <span>Team</span>
                        </a>
                        <!-- Dropdown menu -->
                        <ul id="teamDropdown" class="absolute bg-gray-800 text-white rounded-lg mt-2 w-full hidden">
                            <li class="p-3 hover:bg-blue-300">
                                <a href="koki.php">Koki</a>
                            </li>
                            <li class="p-3 hover:bg-blue-300">
                                <a href="pelayan.php">Pelayan</a>
                            </li>
                            <li
                                class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white bg-blue-500">
                                <a href="#">Kasir</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="adminMenu.php"
                            class="flex items-center text-base p-3 rounded-lg hover:bg-blue-300 hover:text-white">
                            <!-- Icon Menu -->
                            <img src="../../assets/icon/IconMenu1.svg" alt="Icon Menu"
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
    <div class="flex-1 p-10 text-xl font-bold">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-gray-700 text-base">Kasir</h2>
            <button id="addCashierButton" class="bg-green-500 text-white text-sm px-4 py-2 rounded">Tambah
                Kasir</button>
        </div>
        <table id="cashierTable" class="min-w-full bg-white border rounded">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-sm text-left">No</th>
                    <th class="py-2 px-4 border-b text-sm text-left">ID</th>
                    <th class="py-2 px-4 border-b text-sm text-left">Nama Kasir</th>
                    <th class="py-2 px-4 border-b text-sm text-left">Password</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $no = 1;
                    while($row = $result->fetch_assoc()) {
                        echo "<tr class='bg-gray-100'>";
                        echo "<td class='py-2 px-4 border-b font-normal text-sm text-left'>" . $no++ . "</td>";
                        echo "<td class='py-2 px-4 border-b font-normal text-sm text-left'>" . $row["idKasir"] . "</td>";
                        echo "<td class='py-2 px-4 border-b font-normal text-sm text-left'>" . $row["namaKasir"] . "</td>";
                        echo "<td class='py-2 px-4 border-b font-normal text-sm text-left'>" . $row["passwordKasir"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='py-2 px-4 border-b font-normal text-sm text-left'>No data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Form "Tambah Kasir" -->
    <div id="addCashierForm" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full">
            <h3 class="text-2xl mb-4">Tambah Kasir</h3>
            <form id="cashierForm">
                <div class="mb-4">
                    <label for="no" class="block text-sm font-medium text-gray-700">No</label>
                    <input type="text" id="no" class="mt-1 p-2 w-full border rounded-md" />
                </div>
                <div class="mb-4">
                    <label for="id" class="block text-sm font-medium text-gray-700">ID</label>
                    <input type="text" id="id" class="mt-1 p-2 w-full border rounded-md" />
                </div>
                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Kasir</label>
                    <input type="text" id="nama" class="mt-1 p-2 w-full border rounded-md" />
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" class="mt-1 p-2 w-full border rounded-md" />
                </div>
                <div class="flex justify-end">
                    <button type="button" id="cancelButton"
                        class="bg-red-500 text-white px-4 py-2 rounded mr-2">Batal</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success Alert -->
    <div id="successAlert" class="hidden fixed inset-0 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg text-center">
            <img src="/mnt/data/image.png" alt="Success" class="w-24 h-24 mx-auto mb-4" />
            <p class="text-xl">KASIR BERHASIL DITAMBAHKAN!!!</p>
            <button id="closeAlertButton" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">SELESAI</button>
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
    function toggleDropdown(event) {
        event.stopPropagation();
        const dropdown = event.currentTarget.nextElementSibling;
        dropdown.classList.toggle("hidden");
        localStorage.setItem("dropdownOpen", !dropdown.classList.contains("hidden"));
    }

    const addCashierButton = document.getElementById("addCashierButton");
    const addCashierForm = document.getElementById("addCashierForm");
    const cancelButton = document.getElementById("cancelButton");
    const cashierForm = document.getElementById("cashierForm");
    const successAlert = document.getElementById("successAlert");
    const closeAlertButton = document.getElementById("closeAlertButton");
    const cashierTable = document.getElementById("cashierTable").querySelector("tbody");

    addCashierButton.addEventListener("click", () => {
        addCashierForm.classList.remove("hidden");
        addCashierForm.classList.add("animate-fade-in");
    });

    cancelButton.addEventListener("click", () => {
        addCashierForm.classList.remove("animate-fade-in");
        addCashierForm.classList.add("animate-fade-out");
        setTimeout(() => {
            addCashierForm.classList.add("hidden");
            addCashierForm.classList.remove("animate-fade-out");
        }, 300);
    });

    cashierForm.addEventListener("submit", (e) => {
        e.preventDefault();

        const no = document.getElementById("no").value;
        const id = document.getElementById("id").value;
        const nama = document.getElementById("nama").value;
        const password = document.getElementById("password").value;

        // Menggunakan AJAX untuk mengirim data ke server tanpa reload halaman
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "kasir.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200 && xhr.responseText.trim() === "success") {
                const newRow = document.createElement("tr");
                newRow.innerHTML = `
                    <td class="py-2 px-4 border-b font-normal text-sm text-left">${no}</td>
                    <td class="py-2 px-4 border-b font-normal text-sm text-left">${id}</td>
                    <td class="py-2 px-4 border-b font-normal text-sm text-left">${nama}</td>
                    <td class="py-2 px-4 border-b font-normal text-sm text-left">${password}</td>
                    `;
                cashierTable.appendChild(newRow);

                addCashierForm.classList.add("hidden");
                successAlert.classList.remove("hidden");

                cashierForm.reset();
            } else {
                console.error("Error adding cashier: ", xhr.responseText);
            }
        };
        xhr.send(`no=${no}&id=${id}&nama=${nama}&password=${password}`);
    });

    closeAlertButton.addEventListener("click", () => {
        successAlert.classList.add("hidden");
    });

    const logoutButton = document.getElementById("logoutButton");
    logoutButton.addEventListener("click", () => {
        window.location.href = "../../login/loginAdmin.php";
    });

    // Set the correct state on page load
    document.addEventListener("DOMContentLoaded", () => {
        const dropdownOpen = localStorage.getItem("dropdownOpen") === "true";

        if (dropdownOpen) {
            document.getElementById("teamDropdown").classList.remove("hidden");
        }
    });
    </script>
</body>

</html>