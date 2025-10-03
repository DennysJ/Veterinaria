<?php
// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "vete");
// Verificar conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}
// Variable para mostrar mensaje
$mensaje = "";
$tipo_mensaje = "";
// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $mysqli->real_escape_string($_POST["nombre"]);
    $usuario = $mysqli->real_escape_string($_POST["usuario"]);
    $contrasena = password_hash($_POST["contrasena"], PASSWORD_DEFAULT);
    $rol = $_POST["rol"];
    // Verificamos que no esté vacío ningún campo
    if ($nombre && $usuario && $_POST["contrasena"] && $rol) {
        $sql = "INSERT INTO usuarios (nombre, usuario, contrasena, rol)
                VALUES ('$nombre', '$usuario', '$contrasena', '$rol')";
        if ($mysqli->query($sql)) {
            $mensaje = "Usuario registrado correctamente";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "Error al registrar usuario: " . $mysqli->error;
            $tipo_mensaje = "error";
        }
    } else {
        $mensaje = "Todos los campos son obligatorios";
        $tipo_mensaje = "warning";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario - Veterinaria</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            position: relative;
        }

        .header {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 30px 20px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="40" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="80" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% { transform: translateX(-50%) translateY(-50%) rotate(0deg); }
            100% { transform: translateX(-50%) translateY(-50%) rotate(360deg); }
        }

        .header h2 {
            font-size: 24px;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header .subtitle {
            font-size: 14px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .vet-icon {
            font-size: 48px;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .form-container {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4CAF50;
            font-size: 16px;
            z-index: 1;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4CAF50;
            background: white;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        .form-group select {
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(76, 175, 80, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin: 20px 0;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert.success {
            background: #d4eedd;
            color: #155724;
            border-left: 4px solid #4CAF50;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert.warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #4CAF50;
            text-decoration: none;
            font-weight: 500;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .back-link:hover {
            color: #45a049;
            transform: translateX(-5px);
        }

        .role-descriptions {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            border-left: 3px solid #4CAF50;
        }

        @media (max-width: 480px) {
            .container {
                margin: 10px;
            }
            
            .form-container {
                padding: 30px 20px;
            }
            
            .header {
                padding: 25px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="vet-icon">
                <i class="fas fa-user-md"></i>
            </div>
            <h2>Agregar Usuario</h2>
            <div class="subtitle">Sistema de Gestión Veterinaria</div>
        </div>
        
        <div class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nombre">
                        <i class="fas fa-user"></i>
                        Nombre completo
                    </label>
                    <input type="text" name="nombre" id="nombre" required placeholder="Ingresa el nombre completo">
                </div>

                <div class="form-group">
                    <label for="usuario">
                        <i class="fas fa-at"></i>
                        Usuario
                    </label>
                    <input type="text" name="usuario" id="usuario" required placeholder="Nombre de usuario">
                </div>

                <div class="form-group">
                    <label for="contrasena">
                        <i class="fas fa-lock"></i>
                        Contraseña
                    </label>
                    <input type="password" name="contrasena" id="contrasena" required placeholder="Contraseña segura">
                </div>

                <div class="form-group">
                    <label for="rol">
                        <i class="fas fa-user-tag"></i>
                        Rol del usuario
                    </label>
                    <select name="rol" id="rol" required>
                        <option value="">-- Selecciona un rol --</option>
                        <option value="1">👑 Administrador</option>
                        <option value="2">📋 Recepcionista</option>
                        <option value="3">🩺 Veterinario</option>
                    </select>
                    <div class="role-descriptions">
                        <strong>Roles:</strong><br>
                        • <strong>Administrador:</strong> Acceso completo al sistema<br>
                        • <strong>Recepcionista:</strong> Gestión de citas y clientes<br>
                        • <strong>Veterinario:</strong> Consultas y tratamientos
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-user-plus"></i>
                    Registrar Usuario
                </button>
            </form>

            <?php if ($mensaje): ?>
                <div class="alert <?= $tipo_mensaje ?>">
                    <?php if ($tipo_mensaje == 'success'): ?>
                        <i class="fas fa-check-circle"></i>
                    <?php elseif ($tipo_mensaje == 'error'): ?>
                        <i class="fas fa-exclamation-circle"></i>
                    <?php else: ?>
                        <i class="fas fa-exclamation-triangle"></i>
                    <?php endif; ?>
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <a href="admin.php" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Volver al panel
            </a>
        </div>
    </div>
</body>
</html>