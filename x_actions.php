<?php
// SERVER DATS
$servername = "localhost";
$username = "root";
$password = "";
$database = "tfg_prod";

// CONEXION
$conn = mysqli_connect($servername, $username, $password, $database);

// SESIONS
$status = session_status();
if($status == PHP_SESSION_NONE){
    // CREA SESION
    session_start();
}else
if($status == PHP_SESSION_DISABLED){
}else
if($status == PHP_SESSION_ACTIVE){
    // ACTUALIZA SESSION
    session_destroy();
    session_start();
}

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$action=$_POST["action"];
if(trim($action)==""){
  $action=$_POST["action"];
}

// ACTIONS
if($action == 'loginAction'){

  $email = $_POST['offset0'];
  $password = $_POST['offset1'];

  $sql = "SELECT nombre FROM usuarios WHERE email ='$email' and password ='$password'";
  $result = mysqli_query($conn, $sql);

  $respuestaLogin = json_encode($result->fetch_array(),true);

  if (strlen($respuestaLogin)>4) {
    $_SESSION["email"] = $email;
    $respuesta = "Ok";
  }
}else if($action=='registerAction'){

  $email = $_POST['offset0'];
  $password = $_POST['offset1'];
  $nombre = $_POST['offset2'];
  $comunidad = $_POST['offset3'];

  $sql = "INSERT INTO usuarios (email, nombre, password, ccaa) VALUES ('$email', '$nombre', '$password', '$comunidad')";

  if ($conn->query($sql) === TRUE) {
    $result = "Ok";
  }else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $respuesta = $result;
}else if($action=='registerMarker'){

  $coordsMarker = $_POST['offset0'];
  $idUser = $_POST['offset1'];
  $status = $_POST['offset2'];
  $descripcionMarker = $_POST['offset3'];

  $sql = "INSERT INTO markers (iduser, cords, status, description) VALUES ('$idUser', '$coordsMarker', '$status', '$descripcionMarker')";

  if ($conn->query($sql) === TRUE) {
    $result = "Ok";
  }else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $respuesta = $result;
}else if($action=='getAllMarkers'){

  $myArray = array();
  if ($result = $conn->query("SELECT iduser, cords, status, description FROM markers WHERE status NOT LIKE 4")) {
      $tempArray = array();
      while ($row = $result->fetch_object()) {
          $tempArray = $row;
          array_push($myArray, $tempArray);
      }
      $respuesta = json_encode($myArray);
  }
}else if($action=='updateProcess'){
  $lats = $_POST['offset0'];

  $sql = "UPDATE markers SET status = 1 WHERE cords = '$lats'";  
  if ($conn->query($sql) === TRUE) {
    $result = "Ok";
  }else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $respuesta = $result;
}else if($action=='updateFinished'){
  $lats = $_POST['offset0'];

  $sql = "UPDATE markers SET status = 2 WHERE cords = '$lats'";  
  if ($conn->query($sql) === TRUE) {
    $result = "Ok";
  }else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $respuesta = $result;
}else if($action=='updateReboot'){
  $lats = $_POST['offset0'];

  $sql = "UPDATE markers SET status = 0 WHERE cords = '$lats'";  
  if ($conn->query($sql) === TRUE) {
    $result = "Ok";
  }else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $respuesta = $result;
}else if($action=='updateDelete'){
  $lats = $_POST['offset0'];

  $sql = "UPDATE markers SET status = 4 WHERE cords = '$lats'";  
  if ($conn->query($sql) === TRUE) {
    $result = "Ok";
  }else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  $respuesta = $result;
}else if($action=='deslogear'){
  session_destroy();
  $respuesta = "Ok";
}else if($action=='getUserId'){

  $email = $_POST['offset0'];  
  $sql = "SELECT nombre,id FROM usuarios WHERE email ='$email'";
  $result = mysqli_query($conn, $sql);

  $respuestaEmail = json_encode($result->fetch_array(),true);
  $respuesta = $respuestaEmail;
}else if($action=='getMarkerHistory'){

  $idUser = $_POST['offset0'];  

  $myArray = array();
  if ($result = $conn->query("SELECT description, cords, date, status FROM markers WHERE iduser ='$idUser'")) {
      $tempArray = array();
      while ($row = $result->fetch_object()) {
          $tempArray = $row;
          array_push($myArray, $tempArray);
      }
      $respuesta = json_encode($myArray);
  }
}
$conn->close();
if (isset($respuesta)) {
  echo $respuesta;
}
?>