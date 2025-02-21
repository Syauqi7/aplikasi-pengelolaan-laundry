<?php
$title = 'outlet';
require'functions.php';
$query = 'SELECT outlet.*, user.nama_user,user.id_user FROM outlet LEFT JOIN user ON user.outlet_id = outlet.id_outlet WHERE id_outlet = ' . stripslashes($_GET['id']);
$data = ambilsatubaris($conn,$query);

$query2 = 'SELECT user.*,outlet.nama_outlet FROM outlet RIGHT JOIN user ON user.outlet_id = outlet.id_outlet WHERE user.role = "owner" ORDER BY user.outlet_id ASC';
$data2 = ambildata($conn,$query2);

$query3 = 'SELECT user.*,outlet.nama_outlet FROM outlet RIGHT JOIN user ON user.outlet_id = outlet.id_outlet WHERE user.role = "kasir" ORDER BY user.outlet_id ASC';
$data3 = ambildata($conn,$query3);

if(isset($_POST['btn-simpan'])){
    $nama   = stripslashes($_POST['nama_outlet']);
    $alamat = stripslashes($_POST['alamat_outlet']);
    $telp   = stripslashes($_POST['telp_outlet']);

    $query = "UPDATE outlet SET nama_outlet = '$nama' , alamat_outlet = '$alamat' , telp_outlet='$telp' WHERE id_outlet = " . stripslashes($_GET['id']);
    
    if($_POST['owner_id_new']){
        $query2 = "UPDATE user SET outlet_id = '" . stripslashes($_GET['id']) . "' WHERE id_user = " . $_POST['owner_id_new'];
        $query3 = "UPDATE user SET outlet_id = NULL WHERE id_user = " . stripslashes($data['id_user']);
        $execute3 = bisa($conn,$query3);
    }else{
        $query2 = "UPDATE user SET outlet_id = '" . stripslashes($_GET['id']) . "' WHERE id_user = " . stripslashes($_POST['owner_id']);
    }

    if($_POST['kasir_id_new']){
        $query4 = "UPDATE user SET outlet_id = '" . stripslashes($_GET['id']) . "' WHERE id_user = " . stripslashes($_POST['kasir_id_new']);
        $execute4 = bisa($conn,$query4);
    }

    $execute = bisa($conn,$query);
    $execute2 = bisa($conn,$query2);

    if($execute == 1 && $execute2 == 1){
        $success = 'true';
        $title = 'Berhasil';
        $message = 'Berhasil Mengubah Data';
        $type = 'success';
        header('location: outlet.php?crud='.$success.'&msg='.$message.'&type='.$type.'&title='.$title);
    }else{
        echo "Gagal Tambah Data";
    }
}

require'layout_header.php';
?>
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Data Master Outlet</h4> </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <form method="post" action="">
                <div class="form-group">
                    <label>Nama Outlet</label>
                    <input type="text" value="<?= $data['nama_outlet']; ?>" name="nama_outlet" class="form-control">
                </div>
                <div class="form-group">
                    <label>Alamat Outlet</label>
                    <textarea name="alamat_outlet" class="form-control"><?= htmlspecialchars($data['alamat_outlet']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="text" value="<?= htmlspecialchars($data['telp_outlet']); ?>" name="telp_outlet" class="form-control">
                </div>
                <div class="form-group">
                    <label>Owner Sekarang</label>
                    <select name="owner_id_new" class="form-control">
                        <option class="">Pilih Untuk Mengganti owner</option>
                        <?php foreach ($data2 as $owner): ?>
                            <option value="<?= htmlspecialchars($owner['id_user']); ?>"> <?= htmlspecialchars($owner['nama_user']); ?> 
                                <?php if ($owner['outlet_id'] == null): ?>
                                    ( Belum memiliki outlet )
                                <?php else: ?>
                                    ( Owner di <?= htmlspecialchars($owner['nama_outlet']); ?> )
                                <?php endif ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Kasir Sekarang</label>
                    <select name="kasir_id_new" class="form-control">
                        <option class="">Pilih Untuk Mengganti Kasir</option>
                        <?php foreach ($data3 as $kasir): ?>
                            <option value="<?= htmlspecialchars($kasir['id_user']); ?>"> <?= htmlspecialchars($kasir['nama_user']); ?> 
                                <?php if ($kasir['outlet_id'] == null): ?>
                                    ( Belum memiliki outlet )
                                <?php else: ?>
                                    ( Kasir di <?= htmlspecialchars($kasir['nama_outlet']); ?> )
                                <?php endif ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="text-right">
                    <button type="reset" class="btn btn-danger">Reset</button>
                    <button type="submit" name="btn-simpan" class="btn btn-primary">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
require'layout_footer.php';
?>
