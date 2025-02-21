<?php 
require 'functions.php';
$conn = mysqli_connect("localhost", "root", "", "ujikom_laundry");

$outlet_id = mysqli_real_escape_string($conn, $_GET['id']);

// Delete related records in 'paket' first
mysqli_query($conn, "DELETE FROM paket WHERE outlet_id = '$outlet_id'");

// Now delete the outlet
$sql = "DELETE FROM outlet WHERE id_outlet = '$outlet_id'";
$exe = mysqli_query($conn, $sql);

if ($exe) {
    $success = 'true';
    $title = 'Berhasil';
    $message = 'Menghapus Data';
    $type = 'success';
    header('location: outlet.php?crud='.$success.'&msg='.$message.'&type='.$type.'&title='.$title);
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
