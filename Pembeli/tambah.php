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
$nik = $_POST['nik'] ?? '';
$nama = $_POST['nama'] ?? '';
$tanggallahir = $_POST['tanggallahir'] ?? date('Y-m-d');
$alamat = $_POST['alamat'] ?? '' ;
$jabatan = $_POST['jabatan'] ?? '';
$nohp = $_POST['nohp'] ?? '';

/**
 * Validation int value
 */
$nohpFilter = filter_var($nohp, FILTER_VALIDATE_INT);

/**
 * Validation empty fields
 */
$isValidated = true;
if($nohpFilter === false){
    $reply['error'] = "No Hp  harus format INT";
    $isValidated = false;
}
if(empty($nik)){
    $reply['error'] = 'nik harus diisi';
    $isValidated = false;
}
if(empty($nama)){
    $reply['error'] = 'Nama harus diisi';
    $isValidated = false;
}
if(empty($alamat)){
    $reply['error'] = 'Alamat harus diisi';
    $isValidated = false;
}
if(empty($jabatan)){
    $reply['error'] = 'Jabatan harus diisi';
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
    $query = "INSERT INTO karyawan (nik, nama, tanggallahir, alamat, jabatan, nohp) 
VALUES (:nik, :nama, :tanggallahir, :alamat, :jabatan, :nohp)";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":nik", $nik);
    $statement->bindValue(":nama", $nama);
    $statement->bindValue(":tanggallahir", $tanggallahir);
    $statement->bindValue(":alamat", $alamat);
    $statement->bindValue(":jabatan", $jabatan);
    $statement->bindValue(":nohp", $nohp, PDO::PARAM_INT);
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