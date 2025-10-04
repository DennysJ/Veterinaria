<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);

// Verifica conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $foto = $_POST['foto'];
    $nombre = $_POST['nombre'];
    $raza = $_POST['raza'];
    $especie = $_POST['especie'];
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];
    $peso = $_POST['peso'];
    $estado_salud = $_POST['estado_salud'];

    $sql = "UPDATE mascotas SET foto=?, nombre=?, raza=?, especie=?, edad=?, genero=?, peso=?, estado_salud=? WHERE id_mascota=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssssssssi", $foto, $nombre, $raza, $especie, $edad, $genero, $peso, $estado_salud, $id);

    if ($stmt->execute()) {
        echo "<p>Registro actualizado correctamente.</p>";
    } else {
        echo "<p>Error al actualizar: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Si se pasa el ID por GET, muestra el formulario
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT foto, nombre, raza, especie, edad, genero, peso, estado_salud FROM mascotas WHERE id_mascota = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Mascota</title>
</head>
<body>

<?php if (isset($row)) { ?>
    <h2>Editar Mascota</h2>
    <form action="editarmasc.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label>Foto:</label>
        <input type="text" name="foto" value="<?php echo $row['foto']; ?>" required><br>

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $row['nombre']; ?>" required><br>

        <label>Raza:</label>
        <input type="text" name="raza" value="<?php echo $row['raza']; ?>" required><br>

        <label>Especie:</label>
        <input type="text" name="especie" value="<?php echo $row['especie']; ?>" required><br>

        <label>Edad:</label>
        <input type="text" name="edad" value="<?php echo $row['edad']; ?>" required><br>

        <label>Género:</label>
        <input type="text" name="genero" value="<?php echo $row['genero']; ?>" required><br>

        <label>Peso:</label>
        <input type="text" name="peso" value="<?php echo $row['peso']; ?>" required><br>

        <label>Estado de salud:</label>
        <input type="text" name="estado_salud" value="<?php echo $row['estado_salud']; ?>" required><br>

        <input type="submit" value="Actualizar">
    </form>
<?php } else { ?>
    <p>ID de mascota no especificado.</p>
<?php } ?>

<a href="consulta.php">Regresar a la consulta</a>

</body>
</html>
