<?php
session_start();
require '../../../Koneksi.php';

$sql = "SELECT * FROM menumakanan";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Menu Makanan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
    .table-auto th,
    .table-auto td {
        padding: 0.75rem;
        text-align: center;
    }

    .table-auto th {
        background-color: #064e3b;
        color: white;
    }

    #orderList {
        max-height: 150px;
        overflow-y: auto;
    }

    .btn {
        text-transform: uppercase;
        transition: all 0.3s ease-in-out;
    }

    .btn:hover {
        transform: scale(1.05);
    }
    </style>
</head>

<body class="bg-black flex flex-col items-center justify-center h-screen">
    <!-- Back Button -->
    <button class="absolute top-4 left-4 p-2 text-white rounded" onclick="goBack()">
        <img src="../../../assets/images/left-arrow.png" class="w-10 h-10">
    </button>

    <div class="bg-white p-6 rounded-lg shadow-lg w-3/4">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-green-800">Menu Makanan</h1>
        </div>

        <table class="table-auto w-full text-left border-collapse">
            <thead>
                <tr>
                    <th>Nama Makanan</th>
                    <th>Harga</th>
                    <th>Ketersediaan</th>
                    <th>Tambahkan ke Keranjang</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr class='hover:bg-gray-100'>";
                            echo "<td class='border'><div class='flex items-center justify-center'><img src='../../../assets/images/meatballs.png' alt='".$row["namaMakanan"]."' class='w-10 h-10 mr-2'>".$row["namaMakanan"]."</div></td>";
                            echo "<td class='border'>Rp. ".number_format($row["harga"], 0, ',', '.')."</td>";
                            echo "<td class='border'>".($row["ketersediaan"] > 0 ? "Ada" : "Habis")."</td>";
                            echo "<td class='border'><div class='flex justify-center items-center space-x-2'><button class='bg-red-500 text-white px-3 py-1 rounded' onclick='decreaseQuantity(\"".$row["namaMakanan"]."\")'>X</button><button class='bg-green-500 text-white px-3 py-1 rounded' onclick='increaseQuantity(\"".$row["namaMakanan"]."\", \"Rp. ".number_format($row["harga"], 0, ',', '.')."\")'>+</button></div></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No data available</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Error: " . $conn->error . "</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>

        <div class="flex justify-between mt-6">
            <!-- Pesanan Button -->
            <button
                class="btn bg-white text-black py-2 px-20 border border-gray-400 rounded flex items-center shadow-lg"
                onclick="showOrder()">
                <img src="../../../assets/images/shopping-cart.png" class="w-10 h-10 mr-2">
                <span id="pesananText">Pesanan (0)</span>
            </button>

            <!-- Clear Button -->
            <button class="btn bg-red-500 text-white py-2 px-20 border border-gray-400 rounded shadow-lg"
                onclick="resetOrder()">
                Clear
            </button>

            <!-- Pesan Button -->
            <button class="btn bg-green-500 text-white py-2 px-20 border border-gray-400 rounded shadow-lg"
                onclick="showConfirmationPopup()">
                Pesan
            </button>
        </div>
    </div>

    <!-- Order Popup -->
    <div id="orderPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-lg">
            <h2 class="text-xl font-bold mb-4">Pesanan Anda</h2>
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th>Pesanan</th>
                        <th>Harga</th>
                        <th>QTY</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="orderList"></tbody>
                <tfoot>
                    <tr class="total-row">
                        <td colspan="3" class="text-right">Total Keseluruhan:</td>
                        <td id="totalAmount">Rp. 0</td>
                    </tr>
                </tfoot>
            </table>
            <div class="flex justify-between items-center mt-4">
                <button class="bg-gray-500 text-white py-2 px-4 rounded" onclick="closePopup()">Kembali</button>
            </div>
        </div>
    </div>

    <!-- Confirmation Popup -->
    <div id="confirmationPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-lg text-center">
            <img src="../../../assets/images/bell.png" alt="Notification" class="mx-auto mb-4 w-20 h-20">
            <h2 class="text-xl font-bold mb-4">Apakah anda sudah selesai memesan?</h2>
            <div class="flex justify-center space-x-4">
                <button class="bg-green-500 text-white py-2 px-4 rounded" onclick="orderNow()">Lanjut
                    Pembayaran</button>
                <button class="bg-gray-500 text-white py-2 px-4 rounded"
                    onclick="closeConfirmationPopup()">Kembali</button>
            </div>
        </div>
    </div>

    <script>
    function goBack() {
        window.location.href = '../menuPesanMakanan.php';
    }

    function showOrder() {
        const orders = JSON.parse(localStorage.getItem('orders')) || {};
        const orderList = document.getElementById('orderList');
        orderList.innerHTML = '';
        let totalAmount = 0;
        for (const [item, details] of Object.entries(orders)) {
            const total = details.quantity * parseInt(details.price.replace('Rp. ', '').replace('.', ''));
            totalAmount += total;
            const tr = document.createElement('tr');
            tr.innerHTML =
                `<td>${item}</td><td>${details.price}</td><td>${details.quantity}</td><td>Rp. ${total.toLocaleString('id-ID')}</td>`;
            orderList.appendChild(tr);
        }
        document.getElementById('totalAmount').innerText = `Rp. ${totalAmount.toLocaleString('id-ID')}`;
        document.getElementById('orderPopup').classList.remove('hidden');
    }

    function resetOrder() {
        localStorage.removeItem('orders');
        updateOrderButton();
        alert("Order has been reset");
    }

    function showConfirmationPopup() {
        const orders = JSON.parse(localStorage.getItem('orders')) || {};
        const orderCount = Object.values(orders).reduce((total, order) => total + order.quantity, 0);
        if (orderCount > 0) {
            document.getElementById('confirmationPopup').classList.remove('hidden');
        } else {
            alert("Anda belum memesan apa pun!");
        }
    }

    function closeConfirmationPopup() {
        document.getElementById('confirmationPopup').classList.add('hidden');
    }

    function orderNow() {
        window.location.href = 'menuTotal.php';
    }

    function closePopup() {
        document.getElementById('orderPopup').classList.add('hidden');
    }

    function updateOrderButton() {
        const orders = JSON.parse(localStorage.getItem('orders')) || {};
        const orderCount = Object.values(orders).reduce((total, order) => total + order.quantity, 0);
        document.getElementById('pesananText').innerText = `Pesanan (${orderCount})`;
    }

    function increaseQuantity(name, price) {
        const orders = JSON.parse(localStorage.getItem('orders')) || {};
        if (orders[name]) {
            orders[name].quantity++;
        } else {
            orders[name] = {
                quantity: 1,
                price: price
            };
        }
        localStorage.setItem('orders', JSON.stringify(orders));
        updateOrderButton();
    }

    function decreaseQuantity(name) {
        const orders = JSON.parse(localStorage.getItem('orders')) || {};
        if (orders[name] && orders[name].quantity > 1) {
            orders[name].quantity--;
        } else {
            delete orders[name];
        }
        localStorage.setItem('orders', JSON.stringify(orders));
        updateOrderButton();
    }

    document.addEventListener('DOMContentLoaded', updateOrderButton);
    </script>
</body>

</html>