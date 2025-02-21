<?php

require 'functions.php';
$query = "SELECT 
    transaksi.*, 
    transaksi.tgl, 
    member.nama_member, 
    detail_transaksi.total_harga, 
    outlet.nama_outlet, 
    paket.nama_paket,
    COUNT(detail_transaksi.paket_id) AS jumlah_paket
FROM transaksi
INNER JOIN member ON member.id_member = transaksi.member_id
INNER JOIN detail_transaksi ON detail_transaksi.transaksi_id = transaksi.id_transaksi
INNER JOIN outlet ON outlet.id_outlet = transaksi.outlet_id
INNER JOIN paket ON paket.id_paket = detail_transaksi.paket_id
WHERE transaksi.status_bayar = 'dibayar'  -- Menampilkan hanya yang sudah dibayar
GROUP BY 
    transaksi.id_transaksi, 
    transaksi.tgl, 
    member.nama_member, 
    detail_transaksi.total_harga, 
    outlet.nama_outlet, 
    paket.nama_paket";

$data = mysqli_query($conn, $query);


setlocale(LC_ALL, 'id_id');
setlocale(LC_TIME, 'id_ID.utf8');
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cetak Laporan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
</head>

<body>
<center>

<h2>DATA LAPORAN TRANSAKSI LAUNDRY</h2>
<h6><?= date('l, d F Y'); ?></h6>
<h6 class="mr-auto">Oleh : <?= $_SESSION['username']; ?></h6>
<br>
</center>

<table class="table table-bordered" style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 3%">#</th>
                <th>Kode</th>
                <th>Nama Pelanggan</th>
                <th>Nama Paket</th>
                <th>Jumlah Paket</th>
                <th>Status</th>
                <th>Pembayaran</th>
                <th>Tanggal Pembayaran</th>
                <th>Total</th>
                <th>Outlet Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if (mysqli_num_rows($data) > 0) {
                while ($trans = mysqli_fetch_assoc($data)) {
            ?>

                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $trans['kode_invoice']; ?></td>
                        <td><?= $trans['nama_member']; ?></td>
                        <td><?= $trans['nama_paket']; ?></td>
                        <td><?= $trans['jumlah_paket']; ?></td>
                        <td><?= $trans['status']; ?></td>
                        <td><?= $trans['status_bayar']; ?></td>
                        <td><?= $trans['tgl']; ?></td>
                        <td><?= 'Rp ' . number_format($trans['total_harga']); ?></td>
                        <td><?= $trans['nama_outlet']; ?></td>
                    </tr>
            <?php }
            }
            ?>
        </tbody>
    </table>

    <script>
        window.print();
    </script>

</body>

</html>