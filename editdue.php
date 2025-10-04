<?php
require_once 'database.php';

// Verificar que se recibieron los datos POST
if (!isset($_POST['id']) || !isset($_POST['nombre']) || !isset($_POST['telefono']) || !isset($_POST['direccion'])) {
    die("Datos incompletos recibidos.");
}

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];

$sql = "UPDATE duenos SET nombre = ?, telefono = ?, direccion = ? WHERE id_dueno = ?";
$stmt = $pdo->prepare($sql);

$success = $stmt->execute([$nombre, $telefono, $direccion, $id]);
?>

<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Dueño</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .icon {
            font-size: 5rem;
            margin-bottom: 20px;
        }
        .success .icon { color: #28a745; }
        .error .icon { color: #dc3545; }
        h1 {
            font-size: 2rem;
            margin-bottom: 15px;
        }
        p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
        }
    </style>
</head>
<body>
    <?php if($success): ?>
        <div class="container success">
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <h1>¡Actualización Exitosa!</h1>
            <p>Los datos del dueño se han actualizado correctamente.</p>
            <a href="duenos.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver a la Lista</a>
        </div>
    <?php else: ?>
        <div class="container error">
            <div class="icon"><i class="fas fa-times-circle"></i></div>
            <h1>Error en la Actualización</h1>
            <p>No se pudieron actualizar los datos. Por favor, inténtelo de nuevo.</p>
            <a href="duenos.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver a la Lista</a>
        </div>
    <?php endif; ?>
</body>
</html>
