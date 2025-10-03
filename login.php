<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vete";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$username = $_POST["username"];
$password = $_POST["password"];

// Consulta para verificar credenciales
$sql = "SELECT * FROM usuarios WHERE usuario = '$username' AND contrasena = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // Iniciar sesión
  session_start();
  $_SESSION["contrasena"] = $username;
  header("Location: dashboard.php");
} else {
  echo "Usuario o contraseña incorrectos";
}

$conn->close();
?>