<?php
include '../../../Koneksi.php';

$data = json_decode(file_get_contents('php://input'), true);

$idKoki = $data['idKoki'];
$idKasir = $data['idKasir'];
$idPelayan = $data['idPelayan'];
$orderDate = $data['orderDate'];
$totalPayment = str_replace(['Rp. ', '.'], '', $data['totalPayment']);
$orders = $data['orders'];
$noMeja = $data['noMeja'];
$namaPelanggan = $data['namaPelanggan'];
$idMenu = $data['idMenu'];

foreach ($orders as $order) {
    $idMenu = $data['idMenu'];
    $price = str_replace(['Rp. ', '.'], '', $order['price']);
    $quantity = $order['quantity'];
    $total = $quantity * $price;
    $statusPembayaran = 'Belum Dibayar';
    $statusPesanan = 'Diproses';

    $query = "INSERT INTO pesanan (idPesanan, idPelanggan, idMenu, jumlah, noMeja, tanggalPesanan, totalHarga, statusPembayaran, statusPesanan, idKoki, idPelayan, idKasir) 
              VALUES (NULL, '$namaPelanggan', '$idMenu', '$quantity', '$noMeja', '$orderDate', '$total', '$statusPembayaran', '$statusPesanan', '$idKoki', '$idPelayan', '$idKasir')";

    if (!$conn->query($query)) {
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat memasukkan data pesanan']);
        exit;
    }
}

echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil dilakukan']);
?>