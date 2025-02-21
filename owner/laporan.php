<?php
$title = 'laporan';
require 'functions.php';
require 'layout_header.php';
$outlet_id = $_SESSION['outlet_id'];
$bulan = ambilsatubaris($conn,"SELECT SUM(total_harga) AS total FROM detail_transaksi INNER JOIN transaksi ON transaksi.id_transaksi = detail_transaksi.transaksi_id WHERE status_bayar = 'dibayar' AND MONTH(tgl_pembayaran) = MONTH(NOW())");
$tahun = ambilsatubaris($conn,"SELECT SUM(total_harga) AS total FROM detail_transaksi INNER JOIN transaksi ON transaksi.id_transaksi = detail_transaksi.transaksi_id WHERE status_bayar = 'dibayar' AND YEAR(tgl_pembayaran) = YEAR(NOW())");
$minggu = ambilsatubaris($conn,"SELECT SUM(total_harga) AS total FROM detail_transaksi INNER JOIN transaksi ON transaksi.id_transaksi = detail_transaksi.transaksi_id WHERE status_bayar = 'dibayar' AND WEEK(tgl_pembayaran) = WEEK(NOW())");


$penjualan = ambildata($conn, "SELECT 
    SUM(detail_transaksi.total_harga) AS total, 
    COUNT(detail_transaksi.paket_id) AS jumlah_paket, 
    paket.nama_paket, 
    transaksi.tgl_pembayaran, 
    transaksi.kode_invoice, 
    transaksi.status, 
    transaksi.status_bayar, 
    member.nama_member, 
    outlet.nama_outlet 
FROM detail_transaksi 
INNER JOIN transaksi ON transaksi.id_transaksi = detail_transaksi.transaksi_id 
INNER JOIN paket ON paket.id_paket = detail_transaksi.paket_id 
INNER JOIN member ON member.id_member = transaksi.member_id 
INNER JOIN outlet ON outlet.id_outlet = transaksi.outlet_id 
WHERE transaksi.status_bayar = 'dibayar' 
AND paket.outlet_id = '$outlet_id' 
GROUP BY detail_transaksi.paket_id, member.nama_member, outlet.nama_outlet, transaksi.kode_invoice, transaksi.status, transaksi.status_bayar");

?>
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Dashboard</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <!-- ============================================================== -->
    <!-- Different data widgets -->
    <!-- ============================================================== -->
    <!-- .row -->
    <div class="row">
        <div class="col-lg-4 col-sm-6 col-xs-12">
            <div class="white-box analytics-info">
                <h3 class="box-title">Penghasilan Tahun Ini</h3>
                <ul class="list-inline two-part">
                    <li>
                        <div id="sparklinedash"></div>
                    </li>
                    <li class="text-right"><i class="ti-arrow-up text-success"></i> <span class="counter text-success"><?= $tahun['total'] ?></span></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-xs-12">
            <div class="white-box analytics-info">
                <h3 class="box-title">Penghasilan Bulan ini</h3>
                <ul class="list-inline two-part">
                    <li>
                        <div id="sparklinedash2"></div>
                    </li>
                    <li class="text-right"><i class="ti-arrow-up text-purple"></i> <span class="counter text-purple"><?= $bulan['total'] ?></span></li>
                </ul>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-xs-12">
            <div class="white-box analytics-info">
                <h3 class="box-title">Penghasilan Minggu Ini</h3>
                <ul class="list-inline two-part">
                    <li>
                        <div id="sparklinedash3"></div>
                    </li>
                    <li class="text-right"><i class="ti-arrow-up text-info"></i> <span class="counter text-info"><?= $minggu['total'] ?></span></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="white-box">
            <div class="col-md-auto">
                    <a href="cetak.php" target="_blank" class="btn btn-primary box-title"><i class="fa fa-print"></i> Cetak Laporan</a>
                </div>
                <h3 class="box-title">Laporan Penjualan Paket</h3>
                <div class="table-responsive">
                    <table class="table" id="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode Invoice</th>
                            <th>Nama Pelanggan</th>
                            <th>Nama Paket</th>
                            <th>Jumlah Transaksi</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Tanggal Transaksi</th>
                            <th>Total Hasil</th>
                            <th>Outlet Pembayaran</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($penjualan as $transaksi): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($transaksi['kode_invoice']); ?></td>
                                    <td><?= htmlspecialchars($transaksi['nama_member']); ?></td>
                                    <td><?= htmlspecialchars($transaksi['nama_paket']); ?></td>
                                    <td><?= htmlspecialchars($transaksi['jumlah_paket']); ?></td>
                                    <td><?= htmlspecialchars($transaksi['status']); ?></td>
                                    <td><?= htmlspecialchars($transaksi['status_bayar']); ?></td>
                                    <td><?= htmlspecialchars($transaksi['tgl_pembayaran']); ?></td>
                                    <td><?= htmlspecialchars($transaksi['total']); ?></td>
                                    <td><?= htmlspecialchars($transaksi['nama_outlet']); ?></td>                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require 'layout_footer.php';
?> 