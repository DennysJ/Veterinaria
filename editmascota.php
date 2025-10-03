<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);

// Verifica conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT foto, nombre, raza, especie, edad, genero, peso, estado_salud  FROM duenos WHERE id_dueno = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
} else {
    die("ID de mascota no especificado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Dueños</title>
</head>
<body>
    <h2>Editar Mascota</h2>

    <form action="editdue.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" required><br>

        <label>Teléfono:</label>
        <input type="text" name="telefono" value="<?php echo $row['telefono']; ?>" required><br>

        <label>Direccion:</label>
        <input type="text" name="direccion" value="<?php echo $row['direccion']; ?>" required><br>

        <input type="submit" value="Actualizar">
    </form>

    <a href="duenos.php">Regresar</a>
</body>
</html>