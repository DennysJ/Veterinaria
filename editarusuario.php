<?php
$mysqli = new mysqli("localhost", "root", "", "vete");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}
$id = intval($_GET["id"] ?? 0);
$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $mysqli->real_escape_string($_POST["nombre"]);
    $usuario = $mysqli->real_escape_string($_POST["usuario"]);
    $rol = $mysqli->real_escape_string($_POST["rol"]);
    $sql = "UPDATE usuarios SET nombre='$nombre', usuario='$usuario', rol='$rol' WHERE id_usuario = $id";
    if ($mysqli->query($sql)) {
        $mensaje = "Usuario actualizado correctamente";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al actualizar: " . $mysqli->error;
        $tipo_mensaje = "error";
    }
}

// Obtener datos actuales
$resultado = $mysqli->query("SELECT * FROM usuarios WHERE id_usuario = $id");
$usuarioDatos = $resultado->fetch_assoc();
if (!$usuarioDatos) {
    die("Usuario no encontrado");
}

// Función para obtener el nombre del rol
function obtenerNombreRol($rol) {
    switch($rol) {
        case '1': return 'Administrador';
        case '2': return 'Recepcionista';
        case '3': return 'Veterinario';
        default: return 'Sin rol';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Veterinaria</title>
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
            max-width: 500px;
            position: relative;
        }

        .header {
            background: linear-gradient(135deg, #FF6B35, #F7931E);
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

        .edit-icon {
            font-size: 48px;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-left: 4px solid #FF6B35;
            margin-bottom: 20px;
            border-radius: 0 10px 10px 0;
        }

        .user-info h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .user-info p {
            color: #666;
            margin: 5px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-info i {
            color: #FF6B35;
            width: 20px;
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
            color: #FF6B35;
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
            border-color: #FF6B35;
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }

        .form-group select {
            cursor: pointer;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 25px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #FF6B35, #F7931E);
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 53, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            flex: 0 0 auto;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .btn:active {
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
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert.success {
            background: #d4eedd;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .role-badge.admin {
            background: #fff3cd;
            color: #856404;
        }

        .role-badge.recep {
            background: #d1ecf1;
            color: #0c5460;
        }

        .role-badge.vet {
            background: #d4edda;
            color: #155724;
        }

        .role-descriptions {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 8px;
            border-left: 3px solid #FF6B35;
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

            .form-actions {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="edit-icon">
                <i class="fas fa-user-edit"></i>
            </div>
            <h2>Editar Usuario</h2>
            <div class="subtitle">Sistema de Gestión Veterinaria</div>
        </div>
        
        <div class="form-container">
            <!-- Información actual del usuario -->
            <div class="user-info">
                <h3>Información Actual</h3>
                <p>
                    <i class="fas fa-user"></i>
                    <strong>Nombre:</strong> <?= htmlspecialchars($usuarioDatos['nombre']) ?>
                </p>
                <p>
                    <i class="fas fa-at"></i>
                    <strong>Usuario:</strong> <?= htmlspecialchars($usuarioDatos['usuario']) ?>
                </p>
                <p>
                    <i class="fas fa-user-tag"></i>
                    <strong>Rol actual:</strong> 
                    <span class="role-badge <?= $usuarioDatos['rol'] == '1' ? 'admin' : ($usuarioDatos['rol'] == '2' ? 'recep' : 'vet') ?>">
                        <?= obtenerNombreRol($usuarioDatos['rol']) ?>
                    </span>
                </p>
            </div>

            <form method="POST">
                <div class="form-group">
                    <label for="nombre">
                        <i class="fas fa-user"></i>
                        Nombre completo
                    </label>
                    <input type="text" name="nombre" id="nombre" 
                           value="<?= htmlspecialchars($usuarioDatos['nombre']) ?>" 
                           required placeholder="Ingresa el nombre completo">
                </div>

                <div class="form-group">
                    <label for="usuario">
                        <i class="fas fa-at"></i>
                        Usuario
                    </label>
                    <input type="text" name="usuario" id="usuario" 
                           value="<?= htmlspecialchars($usuarioDatos['usuario']) ?>" 
                           required placeholder="Nombre de usuario">
                </div>

                <div class="form-group">
                    <label for="rol">
                        <i class="fas fa-user-tag"></i>
                        Rol del usuario
                    </label>
                    <select name="rol" id="rol" required>
                        <option value="1" <?= $usuarioDatos['rol'] == '1' ? 'selected' : '' ?>>👑 Administrador</option>
                        <option value="2" <?= $usuarioDatos['rol'] == '2' ? 'selected' : '' ?>>📋 Recepcionista</option>
                        <option value="3" <?= $usuarioDatos['rol'] == '3' ? 'selected' : '' ?>>🩺 Veterinario</option>
                    </select>
                    <div class="role-descriptions">
                        <strong>Roles disponibles:</strong><br>
                        • <strong>Administrador:</strong> Acceso completo al sistema<br>
                        • <strong>Recepcionista:</strong> Gestión de citas y clientes<br>
                        • <strong>Veterinario:</strong> Consultas y tratamientos
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Guardar Cambios
                    </button>
                    <a href="admin.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                </div>
            </form>

            <?php if ($mensaje): ?>
                <div class="alert <?= $tipo_mensaje ?>">
                    <?php if ($tipo_mensaje == 'success'): ?>
                        <i class="fas fa-check-circle"></i>
                    <?php else: ?>
                        <i class="fas fa-exclamation-circle"></i>
                    <?php endif; ?>
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>