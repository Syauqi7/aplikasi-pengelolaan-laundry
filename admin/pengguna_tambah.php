<?php
$title = 'pengguna';
require'functions.php';
$outlet = ambildata($conn,'SELECT * FROM outlet');

if(isset($_POST['btn-simpan'])){
    $nama     = trim(stripslashes($_POST['nama_user']));
    $username = trim(stripslashes($_POST['username']));
    $pass     = md5($_POST['password']);
    $role     = $_POST['role'];

    // Cek apakah username sudah ada
    $query_check = "SELECT COUNT(*) AS jumlah FROM user WHERE username = '$username'";
    $result = ambildata($conn, $query_check);

    if ($result[0]['jumlah'] > 0) {
        // Jika username sudah ada, tampilkan alert dan redirect dengan parameter error
        echo "<script>
            alert('Username sudah digunakan! Silakan pilih username lain.');
            window.location.href='pengguna_tambah.php?crud=false&msg=Username sudah ada!&type=error&title=Gagal';
        </script>";
        exit;
    } else {
        // Jika tidak ada, lakukan insert
        if($role == 'kasir'){
            $outlet_id = $_POST['outlet_id'];
            $query = "INSERT INTO user (nama_user, username, password, role, outlet_id) 
                      VALUES ('$nama', '$username', '$pass', '$role', '$outlet_id')";
        } else {
            $query = "INSERT INTO user (nama_user, username, password, role) 
                      VALUES ('$nama', '$username', '$pass', '$role')";
        }
        
        $execute = bisa($conn, $query);
        if($execute == 1){
            $success = 'true';
            $title = 'Berhasil';
            $message = 'Berhasil menambahkan ' .$role. ' baru';
            $type = 'success';
            header('location: pengguna.php?crud='.$success.'&msg='.$message.'&type='.$type.'&title='.$title);
        } else {
            echo "Gagal Tambah Data";
        }
    }
}



require'layout_header.php';
?> 
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Data Master Pengguna</h4> </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="outlet.php">Pengguna</a></li>
                <li><a href="#">Tambah Pengguna</a></li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-md-6">
                          <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-primary box-title"><i class="fa fa-arrow-left fa-fw"></i> Kembali</a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button id="btn-refresh" class="btn btn-primary box-title text-right" title="Refresh Data"><i class="fa fa-refresh" id="ic-refresh"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
            <div class="white-box">
                <form method="post" action="">
                <div class="form-group">
                    <label>Nama Pengguna</label>
                    <input type="text" name="nama_user" class="form-control">
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="text" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="admin">Admin</option>
                        <option value="owner">Owner</option>
                        <option value="kasir">Kasir</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Jika Role Nya Kasir Maka Pilih Outlet Dimana Dia Akan Ditempatkan</label>
                    <select name="outlet_id" class="form-control">
                        <?php foreach ($outlet as $key): ?>
                            <option value="<?= $key['id_outlet'] ?>"><?= $key['nama_outlet'] ?></option>
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