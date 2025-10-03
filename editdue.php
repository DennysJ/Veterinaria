<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);

// Verificar conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Verificar que se recibieron los datos POST
if (!isset($_POST['id']) || !isset($_POST['nombre']) || !isset($_POST['telefono']) || !isset($_POST['direccion'])) {
    die("Datos incompletos recibidos.");
}

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$direccion = $_POST['direccion'];

$sql = "UPDATE duenos SET nombre = ?, telefono = ?, direccion = ? WHERE id_dueno = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssi", $nombre, $telefono, $direccion, $id);

$success = $stmt->execute();
$error_message = $mysqli->error;
?>

<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de Actualización - Veterinaria</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .message-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .header {
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header.success {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .header.error {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="60" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="30" cy="70" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>');
        }

        .icon {
            font-size: 4rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
            animation: bounce 1s ease-in-out;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .message {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 30px;
            text-align: center;
        }

        .success-details {
            background: #e8f5e8;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #4CAF50;
        }

        .error-details {
            background: #ffeaea;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            border-left: 4px solid #f44336;
            color: #d32f2f;
        }

        .updated-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            text-align: left;
        }

        .updated-info h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin: 8px 0;
            color: #555;
        }

        .info-item i {
            margin-right: 10px;
            width: 20px;
            color: #4CAF50;
        }

        .buttons-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            min-width: 180px;
            justify-content: center;
            text-align: center;
        }

        .back-link.primary {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .back-link.primary:hover {
            background: linear-gradient(135deg, #45a049, #3d8b40);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .back-link.secondary {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .back-link.secondary:hover {
            background: linear-gradient(135deg, #1976D2, #1565C0);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
        }

        @media (max-width: 768px) {
            .message-container {
                margin: 10px;
                border-radius: 15px;
            }

            .buttons-container {
                flex-direction: column;
            }

            .back-link {
                width: 100%;
            }

            .content {
                padding: 20px;
            }
        }

        .paw-print {
            position: fixed;
            opacity: 0.05;
            font-size: 2rem;
            color: #4CAF50;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .paw-1 { top: 10%; left: 10%; animation-delay: 0s; }
        .paw-2 { top: 20%; right: 15%; animation-delay: 1s; }
        .paw-3 { bottom: 30%; left: 20%; animation-delay: 2s; }
        .paw-4 { bottom: 10%; right: 10%; animation-delay: 0.5s; }
    </style>
</head>
<body>
    <div class="paw-print paw-1"><i class="fas fa-paw"></i></div>
    <div class="paw-print paw-2"><i class="fas fa-paw"></i></div>
    <div class="paw-print paw-3"><i class="fas fa-paw"></i></div>
    <div class="paw-print paw-4"><i class="fas fa-paw"></i></div>

    <div class="message-container">
        <?php if ($success): ?>
            <div class="header success">
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="message">¡Actualización Exitosa!</div>
                <p>Los datos del dueño han sido actualizados correctamente</p>
            </div>
            
            <div class="content">
                <div class="success-details">
                    <i class="fas fa-info-circle"></i>
                    La información del propietario se ha guardado exitosamente en la base de datos.
                </div>
                
                <div class="updated-info">
                    <h4><i class="fas fa-user-check"></i> Información Actualizada:</h4>
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <span><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <span><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><strong>Dirección:</strong> <?php echo htmlspecialchars($direccion); ?></span>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="header error">
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="message">Error en la Actualización</div>
                <p>No se pudo actualizar la información del dueño</p>
            </div>
            
            <div class="content">
                <div class="error-details">
                    <i class="fas fa-bug"></i>
                    <strong>Detalles del error:</strong><br>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
                
                <p>Por favor, inténtelo de nuevo o contacte al administrador del sistema si el problema persiste.</p>
            </div>
        <?php endif; ?>
        
        <div class="content">
            <div class="buttons-container">
                <a href="duenos.php" class="back-link primary">
                    <i class="fas fa-list"></i>
                    Ver Listado de Dueños
                </a>
                <a href="consulta.php" class="back-link secondary">
                    <i class="fas fa-paw"></i>
                    Ver Mascotas
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto-redirect después de 5 segundos si fue exitoso
        <?php if ($success): ?>
        setTimeout(function() {
            if (confirm('¿Desea ser redirigido automáticamente al listado de dueños?')) {
                window.location.href = 'duenos.php';
            }
        }, 5000);
        <?php endif; ?>
        
        // Confetti effect para éxito
        <?php if ($success): ?>
        function createConfetti() {
            const colors = ['#4CAF50', '#45a049', '#66BB6A', '#81C784'];
            for (let i = 0; i < 50; i++) {
                setTimeout(() => {
                    const confetti = document.createElement('div');
                    confetti.style.position = 'fixed';
                    confetti.style.width = '10px';
                    confetti.style.height = '10px';
                    confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                    confetti.style.left = Math.random() * 100 + 'vw';
                    confetti.style.top = '-10px';
                    confetti.style.borderRadius = '50%';
                    confetti.style.pointerEvents = 'none';
                    confetti.style.zIndex = '1000';
                    confetti.style.animation = 'fall 3s linear forwards';
                    
                    document.body.appendChild(confetti);
                    
                    setTimeout(() => {
                        confetti.remove();
                    }, 3000);
                }, i * 100);
            }
        }
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fall {
                to {
                    transform: translateY(100vh) rotate(360deg);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
        
        // Ejecutar confetti después de un pequeño delay
        setTimeout(createConfetti, 500);
        <?php endif; ?>
    </script>
</body>
</html>

<?php
$stmt->close();
$mysqli->close();
?>