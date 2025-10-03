<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);

// Verifica conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Recolectar datos del formulario
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$motivo = $_POST['motivo'];
$mascota = $_POST['mascota'];
$veterinario = $_POST['veterinario'];

// Preparar y ejecutar la consulta
$sql = "INSERT INTO citas (fecha, hora, motivo, mascota, veterinario) VALUES (?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssss", $fecha, $hora, $motivo, $mascota, $veterinario);

if ($stmt->execute()) {
    echo "✅ Cita agendada correctamente.";
} else {
    echo "❌ Error al agendar la cita: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
