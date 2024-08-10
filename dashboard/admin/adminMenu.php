<?php
include '../../Koneksi.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        // Handle adding a new menu item
        $idMenu = $_POST['idMenu'];
        $namaMakananMinuman = $_POST['namaMakananMinuman'];
        $harga = $_POST['harga'];
        $ketersediaan = $_POST['ketersediaan'];

        // Determine the table based on the ID prefix (e.g., MKN for makanan, DRK for minuman)
        if (strpos($idMenu, 'MKN') === 0) {
            $sql_insert = "INSERT INTO menumakanan (idMenu, namaMakanan, harga, ketersediaan) VALUES ('$idMenu', '$namaMakananMinuman', '$harga', '$ketersediaan')";
        } elseif (strpos($idMenu, 'DRK') === 0) {
            $sql_insert = "INSERT INTO menuminuman (idMenu, namaMinuman, harga, ketersediaan) VALUES ('$idMenu', '$namaMakananMinuman', '$harga', '$ketersediaan')";
        } else {
            echo "Error: Invalid menu ID format.";
            exit();
        }

        if ($conn->query($sql_insert) === TRUE) {
            echo "success";
        } else {
            echo "Error: " . $sql_insert . "<br>" . $conn->error;
        }
        exit();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        // Handle deleting a menu item
        $idMenu = $_POST['idMenu'];

        // Determine the table based on the ID prefix
        if (strpos($idMenu, 'MKN') === 0) {
            $sql_delete = "DELETE FROM menumakanan WHERE idMenu = '$idMenu'";
        } elseif (strpos($idMenu, 'DRK') === 0) {
            $sql_delete = "DELETE FROM menuminuman WHERE idMenu = '$idMenu'";
        } else {
            echo "Error: Invalid menu ID format.";
            exit();
        }

        if ($conn->query($sql_delete) === TRUE) {
            echo "success";
        } else {
            echo "Error: " . $sql_delete . "<br>" . $conn->error;
        }
        exit();
    }
}

// Query to fetch menu data from both tables
$sql = "
    SELECT idMenu, namaMakanan AS namaMakananMinuman, harga, ketersediaan FROM menumakanan
    UNION
    SELECT idMenu, namaMinuman AS namaMakananMinuman, harga, ketersediaan FROM menuminuman";

$result = $conn->query($sql);

if ($result === false) {
    // Handle query error
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
                            class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white">
                            <img src="../../assets/icon/iconDashboard.svg" alt="Icon Menu"
                                class="h-10 w-10 text-white mr-4" />
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="mb-4 relative">
                        <a href="#" class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white"
                            onclick="toggleDropdown(event)">
                            <img src="../../assets/icon/IconTeam.svg" alt="Icon Team"
                                class="h-10 w-10 text-white mr-4" />
                            <span>Team</span>
                        </a>
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
                            class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white bg-blue-500">
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
    <div class="flex-grow p-10">
        <div class="flex justify-end">
            <button class="p-2 text-sm bg-green-400 rounded-2xl mr-8" onclick="showTambahModal()">Tambah Menu</button>
            <button class="p-2 text-sm bg-red-500 rounded-2xl" onclick="showHapusModal()">Hapus Menu</button>
        </div>
        <!-- Table -->
        <div class="mt-6 rounded-2xl border-gray-200 overflow-x-auto border-2">
            <table class="min-w-full bg-white rounded">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-4">ID Menu</th>
                        <th class="border p-4">Nama Makanan/Minuman</th>
                        <th class="border p-4">Harga</th>
                        <th class="border p-4">Ketersediaan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td class='border p-4'>" . $row["idMenu"] . "</td>";
                            echo "<td class='border p-4'>" . $row["namaMakananMinuman"] . "</td>";
                            echo "<td class='border p-4'><span class='harga'>" . $row["harga"] . "</span></td>";
                            echo "<td class='border p-4'>" . $row["ketersediaan"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='border p-4'>No data found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Tambah Menu Modal -->
    <div id="tambahModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-8 rounded-lg animate-fade-in">
            <h2 class="text-2xl font-bold mb-4">Tambah Menu</h2>
            <p>Harap Mengisi inputan yang disediakan:</p>
            <form id="tambahForm">
                <div class="mt-4">
                    <table class="min-w-full bg-white rounded">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border p-4">ID Menu</th>
                                <th class="border p-4">Nama Makanan/Minuman</th>
                                <th class="border p-4">Harga</th>
                                <th class="border p-4">Ketersediaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border p-4">
                                    <input type="text" id="idMenu" name="idMenu" class="border p-2 rounded"
                                        placeholder="MKN001" />
                                </td>
                                <td class="border p-4">
                                    <input type="text" id="namaMakananMinuman" name="namaMakananMinuman"
                                        class="border p-2 rounded" placeholder="Bakso Goreng" />
                                </td>
                                <td class="border p-4">
                                    <input type="text" id="harga" name="harga" class="border p-2 rounded"
                                        placeholder="20000" />
                                </td>
                                <td class="border p-4">
                                    <input type="number" id="ketersediaan" name="ketersediaan"
                                        class="border p-2 rounded" placeholder="10" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg mr-2"
                        onclick="closeTambahModal()">Batal</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">Tambah</button>
                    <input type="hidden" name="action" value="add">
                </div>
            </form>
        </div>
    </div>
    <!-- Hapus Menu Modal -->
    <div id="hapusModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-8 rounded-lg animate-fade-in">
            <h2 class="text-2xl font-bold mb-4">Hapus Menu</h2>
            <p>Pilih menu yang ingin dihapus:</p>
            <form id="hapusForm">
                <div class="mt-4">
                    <select id="hapusMenuSelect" name="idMenu" class="border p-2 rounded w-full">
                        <?php
                        if ($result->num_rows > 0) {
                            $result->data_seek(0); // Reset pointer to fetch the rows again
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row["idMenu"] . "'>" . $row["namaMakananMinuman"] . "</option>";
                            }
                        } else {
                            echo "<option value=''>No data found</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg mr-2"
                        onclick="closeHapusModal()">Batal</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Hapus</button>
                    <input type="hidden" name="action" value="delete">
                </div>
            </form>
        </div>
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
        event.stopPropagation();
        const dropdown = event.currentTarget.nextElementSibling;
        dropdown.classList.toggle("hidden");
        localStorage.setItem("dropdownOpen", !dropdown.classList.contains("hidden"));
    }

    function showTambahModal() {
        document.getElementById('tambahModal').classList.remove('hidden');
    }

    function closeTambahModal() {
        document.getElementById('tambahModal').classList.add('hidden');
    }

    function showHapusModal() {
        document.getElementById('hapusModal').classList.remove('hidden');
    }

    function closeHapusModal() {
        document.getElementById('hapusModal').classList.add('hidden');
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

    document.getElementById('tambahForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var form = event.target;

        fetch('adminMenu.php', {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + data);
                }
            })
            .catch(error => console.error('Error:', error));
    });

    document.getElementById('hapusForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var form = event.target;

        fetch('adminMenu.php', {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === 'success') {
                    location.reload();
                } else {
                    alert('Error: ' + data);
                }
            })
            .catch(error => console.error('Error:', error));
    });

    document.getElementById("logoutButton").addEventListener("click", function() {
        showLogoutModal();
    });

    document.getElementById("okButton").addEventListener("click", function() {
        closeLogoutModal();
        window.location.href = "../../login/loginAdmin.php";
    });

    document.getElementById("cancelButton").addEventListener("click", function() {
        closeLogoutModal();
    });
    </script>
</body>

</html>