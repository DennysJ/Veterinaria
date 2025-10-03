<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);

// Variables para el mensaje
$mensaje = "";
$tipo_mensaje = "";
$mostrar_mensaje = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM recetas WHERE id_recetas = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $mensaje = "Receta eliminada correctamente";
        $tipo_mensaje = "success";
        $mostrar_mensaje = true;
    } else {
        $mensaje = "Error al eliminar la receta: " . $mysqli->error;
        $tipo_mensaje = "error";
        $mostrar_mensaje = true;
    }
    $stmt->close();
} else {
    $mensaje = "ID no especificado";
    $tipo_mensaje = "warning";
    $mostrar_mensaje = true;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Receta - Veterinaria</title>
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
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #4CAF50, #2196F3, #FF9800);
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 2.2em;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .header p {
            color: #666;
            font-size: 1.1em;
        }

        .veterinary-icon {
            font-size: 4em;
            color: #4CAF50;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .message-container {
            margin: 30px 0;
            padding: 20px;
            border-radius: 15px;
            font-size: 1.1em;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-success {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border: 2px solid #4CAF50;
        }

        .message-error {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
            border: 2px solid #f44336;
        }

        .message-warning {
            background: linear-gradient(135deg, #ff9800, #f57c00);
            color: white;
            border: 2px solid #ff9800;
        }

        .message-icon {
            font-size: 1.5em;
        }

        .actions {
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1em;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
            box-shadow: 0 8px 20px rgba(33, 150, 243, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(33, 150, 243, 0.4);
        }

        .btn i {
            margin-right: 8px;
        }

        .decorative-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            opacity: 0.1;
            overflow: hidden;
        }

        .decorative-elements::before,
        .decorative-elements::after {
            content: '';
            position: absolute;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #4CAF50;
        }

        .decorative-elements::before {
            top: -50px;
            right: -50px;
            animation: float 6s ease-in-out infinite;
        }

        .decorative-elements::after {
            bottom: -50px;
            left: -50px;
            animation: float 8s ease-in-out infinite reverse;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.9em;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .header h1 {
                font-size: 1.8em;
            }
            
            .veterinary-icon {
                font-size: 3em;
            }
            
            .btn {
                padding: 12px 25px;
                font-size: 1em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="decorative-elements"></div>
        
        <div class="header">
            <div class="veterinary-icon">
                <i class="fas fa-stethoscope"></i>
            </div>
            <h1>Sistema Veterinario</h1>
            <p>Gestión de Recetas Médicas</p>
        </div>

        <?php if ($mostrar_mensaje): ?>
            <div class="message-container message-<?php echo $tipo_mensaje; ?>">
                <div class="message-icon">
                    <?php if ($tipo_mensaje == 'success'): ?>
                        <i class="fas fa-check-circle"></i>
                    <?php elseif ($tipo_mensaje == 'error'): ?>
                        <i class="fas fa-exclamation-triangle"></i>
                    <?php else: ?>
                        <i class="fas fa-info-circle"></i>
                    <?php endif; ?>
                </div>
                <div><?php echo $mensaje; ?></div>
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="consultaci.php" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i>
                Volver a las consultas
            </a>
        </div>

        <div class="footer">
            <p><i class="fas fa-paw"></i> Sistema de Gestión Veterinaria</p>
        </div>
    </div>

    <script>
        // Animación de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                container.style.transition = 'all 0.6s ease-out';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);
        });

        // Auto-redirect después de eliminación exitosa
        <?php if ($mostrar_mensaje && $tipo_mensaje == 'success'): ?>
            setTimeout(function() {
                const btn = document.querySelector('.btn-primary');
                btn.style.animation = 'pulse 1s ease-in-out';
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Redirigiendo...';
                
                setTimeout(function() {
                    window.location.href = 'consultaci.php';
                }, 2000);
            }, 3000);
        <?php endif; ?>
    </script>
</body>
</html>