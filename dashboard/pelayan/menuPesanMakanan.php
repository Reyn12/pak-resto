<?php
session_start();
require '../../Koneksi.php';

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Makanan</title>
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

<body class="bg-white flex flex-col items-center justify-center h-screen">

    <!-- Back Button -->
    <button class="absolute top-4 left-4 p-2 text-white rounded" onclick="goBack()">
        <img src="../../assets/images/left-arrow-in-circular-button-black-symbol.png" class="w-10 h-10">
    </button>

    <div class="text-center mb-8">
        <h1 class="text-xl font-bold">Silahkan Pilih Makanan dan Minuman untuk Dipesan</h1>
    </div>

    <div class="flex justify-center space-x-8 mb-8">
        <!-- Makanan Container -->
        <div class="p-8 bg-green-200 rounded-lg cursor-pointer flex flex-col items-center" onclick="goToFood()">
            <img src="../../assets/images/taco.png" alt="Makanan" class="mb-4 w-100 h-100">
            <p class="text-lg font-semibold text-center">MAKANAN</p>
        </div>

        <!-- Minuman Container -->
        <div class="p-8 bg-blue-200 rounded-lg cursor-pointer flex flex-col items-center" onclick="goToDrink()">
            <img src="../../assets/images/drink.png" alt="Minuman" class="mb-4 w-100 h-100">
            <p class="text-lg font-semibold text-center">MINUMAN</p>
        </div>
    </div>

    <div class="flex space-x-4">
        <!-- Pesanan Button -->
        <button class="btn bg-white text-black py-2 px-20 border border-gray-400 rounded flex items-center shadow-lg"
            onclick="showOrder()">
            <img src="../../assets/images/shopping-cart.png" class="w-10 h-10 mr-2">
            <span id="pesananText">PESANAN (0)</span>
        </button>

        <!-- Clear Button -->
        <button class="btn bg-red-500 text-white py-2 px-20 border border-gray-400 rounded shadow-lg"
            onclick="resetOrder()">
            CLEAR
        </button>

        <!-- Pesan Button -->
        <button class="btn bg-green-500 text-white py-2 px-20 border border-gray-400 rounded shadow-lg"
            onclick="showConfirmationPopup()">
            PESAN
        </button>
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
                
            </table>
            <div class="flex justify-between items-center mt-4">
                <div class="font-bold">Total: <span id="totalAmount">Rp. 0</span></div>
                <button class="bg-gray-500 text-white py-2 px-4 rounded" onclick="closePopup()">Kembali</button>
            </div>
        </div>
    </div>

    <!-- Confirmation Popup -->
    <div id="confirmationPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-lg text-center">
            <img src="../../assets/images/bell.png" alt="Notification" class="mx-auto mb-4 w-20 h-20">
            <h2 class="text-xl font-bold mb-4">Apakah anda sudah selesai memesan?</h2>
            <div class="flex justify-center space-x-4">
                <button id="proceedButton" class="bg-green-500 text-white py-2 px-4 rounded" onclick="orderNow()">LANJUT
                    PEMBAYARAN</button>
                <button class="bg-gray-500 text-white py-2 px-4 rounded"
                    onclick="closeConfirmationPopup()">KEMBALI</button>
            </div>
        </div>
    </div>

    <script>
    function goBack() {
        window.location.href = 'menuUtamaPelayan.php';
    }

    function goToFood() {
        window.location.href = 'menuMakananMinuman/menuMakanan.php';
    }

    function goToDrink() {
        window.location.href = 'menuMakananMinuman/menuMinuman.php';
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
        window.location.href = 'menuMakananMinuman/menuTotal.php';
    }

    function closePopup() {
        document.getElementById('orderPopup').classList.add('hidden');
    }

    function updateOrderButton() {
        const orders = JSON.parse(localStorage.getItem('orders')) || {};
        const orderCount = Object.values(orders).reduce((total, order) => total + order.quantity, 0);
        document.getElementById('pesananText').innerText = `PESANAN (${orderCount})`;
    }

    // Initialize order button on page load
    document.addEventListener('DOMContentLoaded', updateOrderButton);
    </script>

</body>

</html>