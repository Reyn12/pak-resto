<?php
session_start();
include '../../../Koneksi.php';
// Ambil nilai totalAmount dari URL parameter
$totalAmount = isset($_GET['totalPayment']) ? $_GET['totalPayment'] : 'Rp. 0';


// Fetch the latest order from the database
$query = "SELECT * FROM pesanan ORDER BY idPesanan DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>

<body class="bg-black text-white flex justify-center items-center min-h-screen relative">
    <div class="bg-gray-900 p-6 rounded-lg shadow-lg w-3/4">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">Pesanan Telah Dibuat</h1>
            <p class="text-sm">Tunjukkan bukti pemesanan ini pada kasir untuk pembayaran</p>
        </div>
        <div class="bg-white text-gray-800 p-6 rounded-lg shadow-lg">
            <div class="text-center mb-4">
                <img src="../../../assets/images/check.png" alt="Success" class="mx-auto mb-4"
                    style="width: 20%; height: 20%" /><br />
            </div>
            <div class="mb-4 text-center bold">
                <p class="font-bold">NOMOR MEJA : <?php echo $order['noMeja']; ?></p>
                <p class="font-bold">TANGGAL PEMESANAN : <?php echo $order['tanggalPesanan']; ?></p>
                <p class="font-bold">PELAYAN : <?php echo $order['idPelayan']; ?></p>
                <p class="font-bold">TOTAL PEMBAYARAN :
                    <?php echo $totalAmount; ?></p>
                <br /><br /><br /><br />
            </div>
            <div class="text-center font-bold">TERIMA KASIH UNTUK PEMESANANNYA</div>
            <div class="text-center text-sm mt-2">Kami tunggu pesanan anda yang selanjutnya</div>
        </div>
    </div>

    <!-- Forward Button -->
    <button class="absolute top-4 right-4 px-4 py-2 text-white font-semibold rounded-lg bg-white" onclick="forward()">
        <img src="../../../assets/images/next.png" class="w-20 h-20" />
    </button>

    <script>
    function forward() {
        window.location.href = "../menuUtamaPelayan.php";
    }
    </script>
</body>

</html>