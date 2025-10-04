<?php
require_once 'database.php';

// Recolectar datos del formulario
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$motivo = $_POST['motivo'];
$mascota = $_POST['mascota'];
$veterinario = $_POST['veterinario'];

// Preparar y ejecutar la consulta
$sql = "INSERT INTO citas (fecha, hora, motivo, id_mascota, id_usuario) VALUES (?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

try {
    if ($stmt->execute([$fecha, $hora, $motivo, $mascota, $veterinario])) {
        echo "✅ Cita agendada correctamente.";
    } else {
        echo "❌ Error al agendar la cita.";
    }
} catch (PDOException $e) {
    echo "❌ Error de base de datos: " . $e->getMessage();
}

?>
