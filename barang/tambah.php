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
$id_barang = $_POST['id_barang'] ?? '';
$nama_barang = $_POST['nama_barang'] ?? '';
$harga_barang = $_POST['harga_barang'] ?? '';
$tanggal_masuk = $_POST['tanggal_masuk'] ?? date('Y-m-d');
$tanggal_keluar = $_POST['tanggal_keluar'] ?? date('Y-m-d');
$stok = $_POST['stok'] ?? '' ;

/**
 * Validation int value
 */
$id_barangFilter = filter_var($id_barang, FILTER_VALIDATE_INT);
$stokFilter = filter_var($stok, FILTER_VALIDATE_INT);

/**
 * Validation empty fields
 */
$isValidated = true;
if($id_barangFilter === false){
    $reply['error'] = "id barang  harus format INT";
    $isValidated = false;
}
if($stokFilter === false){
    $reply['error'] = "stok harus format INT";
    $isValidated = false;
}
if(empty($nama_barang)){
    $reply['error'] = 'nama barang harus diisi';
    $isValidated = false;
}
if(empty($harga_barang)){
    $reply['error'] = 'harga barang harus diisi';
    $isValidated = false;
}
if(empty($tanggal_masuk)){
    $reply['error'] = 'tanggal masuk harus diisi';
    $isValidated = false;
}
if(empty($tanggal_keluar)){
    $reply['error'] = 'tanggal keluar harus diisi';
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
    $query = "INSERT INTO barang (kode_barang, nama_barang, jumlah_barang, harga_barang, tanggal_masuk, tanggal_kadarluwasa) 
VALUES (:kode_barang, :nama_barang, :jumlah_barang, :harga_barang, :tanggal_masuk, :tanggal_kadarluwasa)";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":kode_barang", $kode_barang);
    $statement->bindValue(":nama_barang", $nama_barang);
    $statement->bindValue(":jumlah_barang", $jumlah_barang);
    $statement->bindValue(":harga_barang", $harga_barang);
    $statement->bindValue(":tanggal_masuk", $tanggal_masuk);
    $statement->bindValue(":tanggal_kadarluwasa", $tanggal_kadarluwasa, PDO::PARAM_INT);
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