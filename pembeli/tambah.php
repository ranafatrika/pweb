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
$id_pembeli = $_POST['id_pembeli'] ?? '';
$nama_pembeli = $_POST['nama_pembeli'] ?? '';

/**
 * Validation int value
 */
$id_pembeliFilter = filter_var($id_pembeli, FILTER_VALIDATE_INT);

/**
 * Validation empty fields
 */
$isValidated = true;
if($id_pembeliFilter === false){
    $reply['error'] = "id pembeli  harus format INT";
    $isValidated = false;
}
if(empty($nama_pembeli)){
    $reply['error'] = 'nama pembeli harus diisi';
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
    $query = "INSERT INTO pembeli (id_pembeli, nama_pembeli) 
VALUES (:id_pembeli, :nama_pembeli)";
    $statement = $connection->prepare($query);
    /**
     * Bind params
     */
    $statement->bindValue(":id_pembeli", $id_pembeli, PDO::PARAM_INT);
    $statement->bindValue(":nama_pembeli", $nama_pembeli);
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