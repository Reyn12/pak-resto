<?php
// rekapan.php
session_start();
require '../../Koneksi.php'; // Ensure this path is correct and the connection works

if (!isset($_SESSION['namaKasir'])) {
    header("Location: ../../login/loginKasir.php");
    exit();
}

function fetchSalesData($conn, $type) {
    $query = "";

    // Define the query based on the type of report
    switch ($type) {
        case 'Harian':
            $query = "SELECT idPesanan, totalHarga, jumlah, tanggalPesanan FROM pesanan WHERE DATE(tanggalPesanan) = CURDATE()";
            break;
        case 'Mingguan':
            $query = "SELECT idPesanan, totalHarga, jumlah, tanggalPesanan FROM pesanan WHERE WEEK(tanggalPesanan) = WEEK(CURDATE())";
            break;
        case 'Bulanan':
            $query = "SELECT idPesanan, totalHarga, jumlah, tanggalPesanan FROM pesanan WHERE MONTH(tanggalPesanan) = MONTH(CURDATE())";
            break;
        case 'Tahunan':
            $query = "SELECT idPesanan, totalHarga, jumlah, tanggalPesanan FROM pesanan WHERE YEAR(tanggalPesanan) = YEAR(CURDATE())";
            break;
        default:
            return [];
    }

    $result = $conn->query($query);
    if (!$result) {
        error_log("Error executing query: " . $conn->error);
        return [];
    }

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['type'])) {
    $type = $_GET['type'];
    $salesData = fetchSalesData($conn, $type);
    header('Content-Type: application/json');
    echo json_encode($salesData);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
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

<body class="flex flex-col min-h-screen">
    <!-- Top Navbar -->
    <div class="w-full bg-gray-800 text-white p-4 flex justify-between items-center">
        <div class="flex items-center">
            <img src="../../assets/icon/iconkasir.svg" alt="Icon Orang" class="w-12 h-12 mr-4">
            <div>
                <p class="text-base">Welcome,</p>
                <p class="font-bold"><?php echo $_SESSION['namaKasir']; ?></p>
            </div>
        </div>
        <nav class="flex space-x-4">
            <a href="pembayaran.php"
                class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white">
                <img src="../../assets/icon/iconPembayaranKasir.svg" alt="Icon Menu" class="h-6 w-6 text-white mr-2">
                <span>Pembayaran</span>
            </a>
            <a href="#" class="flex items-center text-lg p-3 rounded-lg hover:bg-blue-300 hover:text-white bg-blue-900">
                <img src="../../assets/icon/iconRekapanKasir.svg" alt="Icon Pesanan" class="h-6 w-6 text-white mr-2">
                <span>Rekap Penjualan</span>
            </a>
        </nav>
        <a href="../../login/loginKasir.php?logout=1"
            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-3xl">
            Logout
        </a>
    </div>

    <!-- Body -->
    <div class="flex-1 p-4">
        <h1 class="text-2xl font-bold mb-4">REKAP PENJUALAN</h1>
        <p class="mb-4">Selamat datang di rekapan penjualan. Di sini Anda bisa mengelola <span
                class="font-bold italic">REKAPAN PENJUALAN</span></p>


        <!-- Tabel Rekap Penjualan -->
        <div class="mt-8">
            <h2 class="text-xl font-bold mb-4">Total Pesanan</h2>
            <div class="flex mb-4">
                <button class="tabButton bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l-lg"
                    onclick="showSalesRecords('Harian')">Harian</button>
                <button class="tabButton bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4"
                    onclick="showSalesRecords('Mingguan')">Mingguan</button>
                <button class="tabButton bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4"
                    onclick="showSalesRecords('Bulanan')">Bulanan</button>
                <button class="tabButton bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r-lg"
                    onclick="showSalesRecords('Tahunan')">Tahunan</button>
            </div>
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/5 py-2">Pesanan</th>
                        <th class="w-1/5 py-2">Harga</th>
                        <th class="w-1/5 py-2">Qty</th>
                        <th class="w-1/5 py-2">Total Harga</th>
                        <th class="w-1/5 py-2">Tanggal</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody">
                    <!-- Rows will be dynamically added here -->
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="bg-gray-800 text-white py-2">Total Keseluruhan</td>
                        <td class="bg-gray-800 text-white py-2" id="totalAmount">Rp. 0</td>
                    </tr>
                </tfoot>
            </table>
            <button onclick="downloadExcel()"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg mt-4">Download
                Rekap</button>
        </div>

        <script>
        let currentType = 'Harian';

        async function fetchSalesData(type) {
            try {
                const response = await fetch(`rekapan.php?type=${type}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const salesData = await response.json();
                updateSalesTable(salesData);
            } catch (error) {
                console.error('Error fetching data:', error);
                alert('Failed to fetch sales data');
            }
        }

        function updateSalesTable(salesData) {
            const tableBody = document.getElementById('salesTableBody');
            tableBody.innerHTML = '';
            let totalAmount = 0;

            salesData.forEach(record => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td class="border px-4 py-2">${record.idPesanan}</td>
                    <td class="border px-4 py-2">Rp. ${record.totalHarga}</td>
                    <td class="border px-4 py-2">${record.jumlah}</td>
                    <td class="border px-4 py-2">Rp. ${record.totalHarga}</td>
                    <td class="border px-4 py-2">${record.tanggalPesanan}</td>
                `;
                tableBody.appendChild(newRow);
                totalAmount += parseFloat(record.totalHarga);
            });

            document.getElementById('totalAmount').textContent = `Rp. ${totalAmount}`;
        }

        function showSalesRecords(type) {
            currentType = type;
            fetchSalesData(type);

            // Update active tab button style
            document.querySelectorAll('.tabButton').forEach(button => {
                button.classList.remove('bg-gray-800', 'text-white');
                button.classList.add('bg-gray-300', 'text-gray-800');
            });
            document.querySelector(`[onclick="showSalesRecords('${type}')"]`).classList.add('bg-gray-800',
                'text-white');
        }

        function downloadExcel() {
            fetch(`rekapan.php?type=${currentType}`)
                .then(response => response.json())
                .then(data => {
                    const worksheet = XLSX.utils.json_to_sheet(data);
                    const workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(workbook, worksheet, `Rekap ${currentType}`);
                    XLSX.writeFile(workbook, `Rekap_Penjualan_${currentType}.xlsx`);
                });
        }

        // Load initial data
        showSalesRecords('Harian');
        </script>
</body>

</html>