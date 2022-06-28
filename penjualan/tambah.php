<?php
/**
 * @var $connection PDO
 */
include 'koneksi.php';
$reply = [
    'status' => false,
    'error' => '',
    'data' => []
];

/*
 * Validate http method
 */
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Content-Type: application/json');
    http_response_code(400);
    $reply['error'] = 'POST method required';
    echo json_encode($reply);
    exit();
}
/**
 * Get input data POST
 */
$no_nota = $_POST['no_nota'] ?? '';
$tgl_penjualan = $_POST['tgl_penjualan'] ?? date('Y-m-d');
$jumlah_barang = $_POST['jumlah_barang'] ?? 0;
$id_pembeli = $_POST['id_pembeli'] ?? '' ;
$id_karyawan = $_POST['id_karyawan'] ?? '';
$id_barang = $_POST['id_barang'] ?? '';

/**
 * Validation int value
 */
$no_notaFilter = filter_var($no_nota, FILTER_VALIDATE_INT);
$jumlah_barangFilter = filter_var($jumlah_barang, FILTER_VALIDATE_INT);
$id_pembeliFilter = filter_var($id_pembeli, FILTER_VALIDATE_INT);
$id_karyawanFilter = filter_var($id_karyawan, FILTER_VALIDATE_INT);
$id_barangFilter = filter_var($id_barang, FILTER_VALIDATE_INT);
/**
 * Validation empty fields
 */
$isValidated = true;
if($no_notaFilter === false){
    $reply['error'] = "No Nota  harus format INT";
    $isValidated = false;
}
if($jumlah_barangFilter === false){
    $reply['error'] = "Jumlah Barang harus format INT";
    $isValidated = false;
}
if($id_pembeliFilter === false){
    $reply['error'] = "id pembeli  harus format INT";
    $isValidated = false;
}
if($id_karyawanFilter === false){
    $reply['error'] = "id karyawan harus format INT";
    $isValidated = false;
}
if($id_barangFilter === false){
    $reply['error'] = "id barang  harus format INT";
    $isValidated = false;
}
if(empty($tgl_penjualan)){
    $reply['error'] = 'Tanggal Penjualan harus diisi';
    $isValidated = false;
}
/*
 * Jika filter gagal
 */
if(!$isValidated){
    header('Content-Type: application/json');
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
/**
 * Method OK
 * Validation OK
 * Prepare query
 */
try{
    $query = "INSERT INTO penjualan (no_nota, tgl_penjualan, jumlah_barang, id_pembeli, id_karyawan, id_barang) 
VALUES (:no_nota, :tgl_penjualan, :jumlah_barang, :id_pembeli, :id_karyawan, :id_barang)";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":no_nota", $no_nota, PDO::PARAM_INT);
    $statement->bindValue(":tgl_penjualan", $tgl_penjualan);
    $statement->bindValue(":jumlah_barang", $jumlah_barang, PDO::PARAM_INT);
    $statement->bindValue(":id_pembeli", $id_pembeli, PDO::PARAM_INT);
    $statement->bindValue(":id_karyawan", $id_karyawan, PDO::PARAM_INT);
    $statement->bindValue(":id_barang", $id_barang, PDO::PARAM_INT);
    /**
     * Execute query
     */
    $isOk = $statement->execute();
}catch (Exception $exception){
    header('Content-Type: application/json');
    $reply['error'] = $exception->getMessage();
    echo json_encode($reply);
    http_response_code(400);
    exit(0);
}
/**
 * If not OK, add error info
 * HTTP Status code 400: Bad request
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#client_error_responses
 */
if(!$isOk){
    $reply['error'] = $statement->errorInfo();
    http_response_code(400);
}

/**
 * Show output to client
 * Set status info true
 */
$reply['status'] = $isOk;
header('Content-Type: application/json');
echo json_encode($reply);