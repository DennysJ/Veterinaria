<?php
$message = null;
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["txtnombre"])) {
    require_once 'database.php';

    $nombre = $_POST["txtnombre"];
    $telefono = $_POST["txttelefono"];
    $direccion = $_POST["txtdireccion"];

    if (!empty($nombre) && !empty($telefono) && !empty($direccion)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO duenos (nombre, telefono, direccion) VALUES (?, ?, ?)");
            if ($stmt->execute([$nombre, $telefono, $direccion])) {
                $message = '¡El dueño <strong>' . htmlspecialchars($nombre) . '</strong> ha sido registrado exitosamente!';
                $message_type = 'success';
            } else {
                throw new Exception("Error al registrar al dueño.");
            }
        } catch (Exception $e) {
            $message = "Error en la base de datos: " . $e->getMessage();
            $message_type = 'error';
        }
    } else {
        $message = "Por favor, completa todos los campos.";
        $message_type = 'warning';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Dueños - Veterinaria</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            max-width: 550px;
            width: 100%;
            text-align: center;
        }

        .header {
            margin-bottom: 30px;
        }

        .header .icon {
            font-size: 4rem;
            color: #4A90E2;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 2.5em;
            color: #333;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
            font-size: 1em;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4A90E2;
            box-shadow: 0 0 8px rgba(74, 144, 226, 0.2);
        }

        .btn-group {
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4A90E2, #357ABD);
            color: white;
        }

        .btn-primary:hover {
            box-shadow: 0 8px 20px rgba(74, 144, 226, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
             box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
        }

        .message {
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            font-size: 1.1em;
            font-weight: 500;
        }

        .message.success {
            background: #d4edda;
            color: #155724;
        }

        .message.error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .message.warning {
            background: #fff3cd;
            color: #856404;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon"><i class="fas fa-user-plus"></i></div>
            <h1>Registro de Dueños</h1>
        </div>

        <?php if ($message && $message_type === 'success'): ?>
            <div class="message success"><?php echo $message; ?></div>
            <div class="btn-group">
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Agregar Otro Dueño</a>
                <a href="consulta.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Regresar al Menú</a>
            </div>
        <?php elseif ($message): ?>
            <div class="message <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-group">
                    <label for="txtnombre"><i class="fas fa-user"></i> Nombre Completo:</label>
                    <input type="text" id="txtnombre" name="txtnombre" required value="<?php echo htmlspecialchars($_POST['txtnombre'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="txttelefono"><i class="fas fa-phone"></i> Teléfono:</label>
                    <input type="tel" id="txttelefono" name="txttelefono" required value="<?php echo htmlspecialchars($_POST['txttelefono'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="txtdireccion"><i class="fas fa-map-marker-alt"></i> Dirección:</label>
                    <input type="text" id="txtdireccion" name="txtdireccion" required value="<?php echo htmlspecialchars($_POST['txtdireccion'] ?? ''); ?>">
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Registrar Dueño</button>
                </div>
            </form>
        <?php else: ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-group">
                    <label for="txtnombre"><i class="fas fa-user"></i> Nombre Completo:</label>
                    <input type="text" id="txtnombre" name="txtnombre" required placeholder="Ingresa el nombre completo">
                </div>
                <div class="form-group">
                    <label for="txttelefono"><i class="fas fa-phone"></i> Teléfono:</label>
                    <input type="tel" id="txttelefono" name="txttelefono" required placeholder="Ej: 55 1234 5678">
                </div>
                <div class="form-group">
                    <label for="txtdireccion"><i class="fas fa-map-marker-alt"></i> Dirección:</label>
                    <input type="text" id="txtdireccion" name="txtdireccion" required placeholder="Dirección completa">
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Registrar Dueño</button>
                    <a href="consulta.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Regresar al Menú</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
