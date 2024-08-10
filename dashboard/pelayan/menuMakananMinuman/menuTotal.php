<?php
session_start();
include '../../../Koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Pesanan</title>
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

    .total-row {
        background-color: #064e3b;
        color: white;
        font-weight: bold;
    }

    #orderList {
        min-height: 400px;
        /* Ensures a large table size */
        max-height: 400px;
        /* Keeps the table size consistent */
        overflow-y: auto;
    }

    .btn-large {
        padding: 1rem 2rem;
        font-size: 1.25rem;
        transition: all 0.3s ease-in-out;
    }

    .btn-large:hover {
        background-color: #48bb78;
        /* Lighten green */
        transform: scale(1.05);
    }
    </style>
</head>

<body class="bg-black flex flex-col items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-3/4">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">Total Pesanan</h1>
        </div>

        <div class="text-center mb-4">
            <p><strong>Nama Pelanggan:</strong> <span id="namaPelanggan"></span></p>
        </div>

        <div class="text-center mb-4">
            <p><strong>Koki:</strong> <span id="kokiName"></span></p>
            <p><strong>Kasir:</strong> <span id="kasirName"></span></p>
            <p><strong>Pelayan:</strong> <span id="pelayanName"></span></p>
            <p><strong>Meja:</strong> <span id="noMeja"></span></p>
        </div>

        <table class="table-auto w-full text-left border-collapse">
            <thead>
                <tr>
                    <th>Pesanan</th>
                    <th>Harga</th>
                    <th>QTY</th>
                    <th>Total Harga</th>
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

        <div class="flex justify-end mt-4">
            <button class="btn-large bg-green-500 text-white py-2 px-4 rounded shadow-lg"
                onclick="showConfirmationPopup()">Bayar</button>
        </div>
    </div>

    <!-- Confirmation Popup -->
    <div id="confirmationPopup" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-8 rounded shadow-lg text-center">
            <h2 class="text-xl font-bold mb-4">Apakah anda yakin untuk melanjutkan pembayaran?</h2>
            <div class="flex justify-center space-x-4">
                <button id="proceedButton" class="bg-green-500 text-white py-2 px-4 rounded"
                    onclick="payNow()">Lanjut</button>
                <button class="bg-gray-500 text-white py-2 px-4 rounded"
                    onclick="closeConfirmationPopup()">Kembali</button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const orders = JSON.parse(localStorage.getItem('orders')) || {};
        const orderList = document.getElementById('orderList');
        let totalAmount = 0;

        for (const [item, details] of Object.entries(orders)) {
            const tr = document.createElement('tr');
            const total = details.quantity * parseInt(details.price.replace('Rp. ', '').replace('.', ''));
            totalAmount += total;
            tr.innerHTML = `
                    <td>${item}</td>
                    <td>${details.price}</td>
                    <td>${details.quantity}</td>
                    <td>Rp. ${total.toLocaleString('id-ID')}</td>
                `;
            orderList.appendChild(tr);
        }

        document.getElementById('totalAmount').innerText = `Rp. ${totalAmount.toLocaleString('id-ID')}`;

        // Retrieve selected koki, kasir, pelayan, and namaPelanggan from local storage
        const kokiName = localStorage.getItem('selectedKoki');
        const kasirName = localStorage.getItem('selectedKasir');
        const pelayanName = localStorage.getItem('selectedPelayan');
        const namaPelanggan = localStorage.getItem('namaPelanggan');
        const noMeja = localStorage.getItem('selectedmeja');

        // Display selected koki, kasir, pelayan, and namaPelanggan
        document.getElementById('kokiName').innerText = kokiName;
        document.getElementById('kasirName').innerText = kasirName;
        document.getElementById('pelayanName').innerText = pelayanName;
        document.getElementById('namaPelanggan').innerText = namaPelanggan;
        document.getElementById('noMeja').innerText = noMeja;
    });

    function showConfirmationPopup() {
        document.getElementById('confirmationPopup').classList.remove('hidden');
    }

    function closeConfirmationPopup() {
        document.getElementById('confirmationPopup').classList.add('hidden');
    }

    function payNow() {
        const now = new Date();
        const orderDate = now.toISOString().slice(0, 19).replace('T', ' '); // Format the date to 'YYYY-MM-DD HH:MM:SS'
        const totalPayment = document.getElementById('totalAmount').innerText;

        localStorage.setItem('orderDate', orderDate);
        localStorage.setItem('totalPayment', totalPayment);

        const orders = JSON.parse(localStorage.getItem('orders')) || {};

        // Prepare data for insertion
        const orderData = [];
        for (const [item, details] of Object.entries(orders)) {
            const total = details.quantity * parseInt(details.price.replace('Rp. ', '').replace('.', ''));
            orderData.push({
                item: item,
                price: details.price,
                quantity: details.quantity,
                total: total
            });
        }

        // AJAX request to insert data into the database
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'insert_order.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Clear orders from local storage
                localStorage.removeItem('orders');
                alert('Pesanan Selesai Dilakukan');
                window.location.href = `invoice.php?totalPayment=${totalPayment}`;
            } else {
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        };
        xhr.send(JSON.stringify({

            idKoki: localStorage.getItem('selectedKoki'),
            idKasir: localStorage.getItem('selectedKasir'),
            idPelayan: localStorage.getItem('selectedPelayan'),
            noMeja: localStorage.getItem('selectedmeja'),
            orderDate: orderDate,
            totalPayment: totalPayment,
            orders: orderData,
            namaPelanggan: localStorage.getItem('namaPelanggan')
        }));
    }
    </script>

</body>

</html>