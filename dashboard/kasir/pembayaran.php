<?php
// pembayaran.php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Include database connection
include '../../Koneksi.php'; // Make sure this path is correct

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the user exists
    $sql = "SELECT idKasir, namaKasir FROM kasir WHERE namaKasir = ? AND passwordKasir = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['idKasir'] = $user['idKasir'];
        $_SESSION['namaKasir'] = $user['namaKasir'];
        session_regenerate_id(true); // Regenerate session ID to prevent fixation
        // Redirect to the same page to avoid resubmission
        header("Location: pembayaran.php");
        exit();
    } else {
        $loginError = "Invalid username or password";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: ../../login/loginKasir.php"); // Correct path to login page
    exit();
}

// Handle fetching orders
if (isset($_GET['action']) && $_GET['action'] == 'fetch') {
    $orders = [];
    $sql = "SELECT idPesanan, noMeja, tanggalPesanan, totalHarga, statusPembayaran, statusPesanan FROM pesanan";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($orders);
    exit();
}

// Handle updating payment status
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $idPesanan = $_POST['idPesanan'];
    $sql = "UPDATE pesanan SET statusPembayaran = 'Lunas' WHERE idPesanan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $idPesanan);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Halaman Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    .hidden {
        display: none;
    }

    .table-status {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .table {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border: 1px solid #ccc;
        border-radius: 0.5rem;
    }

    .paid {
        background-color: #d4edda;
    }

    .unpaid {
        background-color: #f8d7da;
    }

    .modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 90%;
        max-width: 500px;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }
    </style>
</head>

<body class="flex flex-col bg-gray-100">

    <?php if (!isset($_SESSION['namaKasir'])): ?>
    <!-- Login Form -->
    <div class="flex items-center justify-center min-h-screen">
        <form method="POST" action="pembayaran.php" class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Login</h2>
            <?php if (isset($loginError)): ?>
            <p class="text-red-500 mb-4"><?php echo $loginError; ?></p>
            <?php endif; ?>
            <div class="mb-4">
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" class="w-full p-2 border border-gray-300 rounded-lg" required />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full p-2 border border-gray-300 rounded-lg" required />
            </div>
            <button type="submit" name="login"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                Login
            </button>
        </form>
    </div>
    <?php else: ?>
    <!-- Top Navbar -->
    <div class="w-full bg-gray-800 text-white p-4 flex justify-between items-center">
        <div class="flex items-center">
            <img src="../../assets/icon/iconkasir.svg" alt="Icon Kasir" class="w-12 h-12 mr-2" />
            <div>
                <p class="text-base mb-2">Welcome, Kasir</p>
                <p class="font-bold"><?php echo $_SESSION['namaKasir']; ?></p>
            </div>
        </div>
        <nav class="flex space-x-4">
            <a href="#" class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white bg-blue-500">
                <img src="../../assets/icon/iconPembayaranKasir.svg" alt="Icon Pembayaran" class="h-6 w-6 mr-2" />
                <span>Pembayaran</span>
            </a>
            <a href="rekapan.php" class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white">
                <img src="../../assets/icon/iconRekapanKasir.svg" alt="Icon Rekapan" class="h-6 w-6 mr-2" />
                <span>Rekap Penjualan</span>
            </a>
        </nav>
        <a href="pembayaran.php?logout=1"
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg">Logout</a>
    </div>

    <!-- Body -->
    <div class="flex-1 p-4">
        <h1 class="text-2xl font-bold">Menu Pembayaran</h1>
        <p>Selamat datang di Menu Kasir. Di sini Anda bisa mengelola <span class="font-bold italic">Pembayaran
                Pesanan</span></p>
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Status Meja</h2>
            <div id="tableStatus" class="table-status"></div>
        </div>
    </div>

    <script>
    async function fetchTableData() {
        const response = await fetch('pembayaran.php?action=fetch');
        const orders = await response.json();
        renderTableStatus(orders);
    }

    function renderTableStatus(orders) {
        const tableStatusContainer = document.getElementById("tableStatus");
        tableStatusContainer.innerHTML = "";
        orders.forEach((order) => {
            const tableDiv = document.createElement("div");
            tableDiv.className = `table ${order.statusPembayaran === "Lunas" ? "paid" : "unpaid"}`;
            tableDiv.innerHTML = `
                    <span class="ml-52">Meja ${order.noMeja} - ${order.idPesanan}</span>
                    <span class="ml-52">${order.tanggalPesanan}</span>
                    <span class="ml-52">Total: Rp ${order.totalHarga}</span>
                    <button class="ml-52 toggleStatus ${order.statusPembayaran === "Lunas" ? "bg-green-500" : "bg-blue-500"} hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        ${order.statusPembayaran === "Lunas" ? "Sudah Bayar" : "Belum Bayar"}
                    </button>
                `;
            // Conditionally add click event for unpaid orders
            if (order.statusPembayaran !== "Lunas") {
                tableDiv.querySelector(".toggleStatus").addEventListener("click", () => showPaymentModal(
                    order));
            }
            tableStatusContainer.appendChild(tableDiv);
        });
    }

    function showPaymentModal(order) {
        const orderDetailsContainer = document.getElementById("orderDetails");
        const totalAmountSpan = document.getElementById("totalAmount");
        orderDetailsContainer.innerHTML = `
                <h3 class="font-bold text-lg mb-2">Rincian Pesanan:</h3>
                <div><strong>ID Pesanan:</strong> ${order.idPesanan}</div>
                <div><strong>No Meja:</strong> ${order.noMeja}</div>
                <div><strong>Tanggal:</strong> ${order.tanggalPesanan}</div>
                <div><strong>Total Harga:</strong> Rp ${order.totalHarga}</div>
                <div><strong>Status Pembayaran:</strong> ${order.statusPembayaran}</div>
                <div><strong>Status Pesanan:</strong> ${order.statusPesanan}</div>
            `;

        totalAmountSpan.textContent = `Rp ${order.totalHarga}`;

        document.getElementById("paymentModal").classList.remove("hidden");
        document.getElementById("paymentForm").onsubmit = function(event) {
            event.preventDefault();
            handlePayment(order);
        };

        document.getElementById("cancelButton").onclick = function() {
            document.getElementById("paymentModal").classList.add("hidden");
        };
    }

    async function handlePayment(order) {
        const amountPaid = parseInt(document.getElementById("amount").value, 10);
        if (amountPaid < parseInt(order.totalHarga, 10)) {
            alert("Jumlah pembayaran tidak mencukupi.");
        } else {
            // Update the order status on the server
            const response = await fetch('pembayaran.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=update&idPesanan=${order.idPesanan}`,
            });

            if (response.ok) {
                document.getElementById("paymentModal").classList.add("hidden");
                fetchTableData();
            } else {
                alert("Gagal mengupdate status pembayaran.");
            }
        }
    }

    fetchTableData();
    </script>

    <!-- Logout Modal -->
    <div id="logoutPopup" class="hidden overlay">
        <div class="modal">
            <h2 class="text-2xl font-bold mb-4">Logout</h2>
            <p>Apakah Anda yakin ingin logout?</p>
            <div class="mt-6 flex justify-end">
                <button id="cancelLogoutButton" class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2"
                    onclick="closeLogoutModal()">Batal</button>
                <button id="confirmLogoutButton" class="bg-red-500 text-white px-4 py-2 rounded-lg">Logout</button>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="hidden overlay">
        <div class="modal">
            <h2 class="text-xl font-bold mb-4">Pembayaran</h2>
            <div id="orderDetails" class="mb-4"></div>
            <div class="mb-4">
                <label for="totalAmount" class="block text-lg font-bold">Total Pembayaran:</label>
                <span id="totalAmount" class="block text-lg font-bold">Rp 0</span>
            </div>
            <form id="paymentForm">
                <div class="mb-4">
                    <label for="amount" class="block text-lg">Jumlah Pembayaran:</label>
                    <input type="number" id="amount" name="amount" class="w-full p-2 border border-gray-300 rounded-lg"
                        required />
                </div>
                <div class="flex justify-end">
                    <button type="button" id="cancelButton"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg mr-2">Batal</button>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">Bayar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Mengatur event listener untuk logout
    document.getElementById("logoutButton").addEventListener("click", function() {
        document.getElementById("logoutPopup").classList.remove("hidden");
    });

    // Mengatur event listener untuk tombol OK pada popup logout
    document.getElementById("confirmLogoutButton").addEventListener("click", function() {
        window.location.href = "../../login/loginKasir.php";
    });

    // Mengatur event listener untuk tombol Batal pada popup logout
    document.getElementById("cancelLogoutButton").addEventListener("click", function() {
        document.getElementById("logoutPopup").classList.add("hidden");
    });
    </script>
    <?php endif; ?>

</body>

</html>