<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conexion = new mysqli("localhost", "root", "", "vete");
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$motivo = $_POST['motivo'];
$id_mascota = $_POST['id_mascota'];
$id_usuario = $_POST['id_usuario'];

$stmt = $conexion->prepare("INSERT INTO citas (fecha, hora, motivo, id_mascota, id_usuario) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssii", $fecha, $hora, $motivo, $id_mascota, $id_usuario);
$success = $stmt->execute();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $success ? 'Cita Registrada' : 'Error en Registro'; ?> - VeteCare</title>
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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .logo i {
            color: white;
            font-size: 2.5rem;
        }

        .success-message {
            color: #2E7D32;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .error-message {
            color: #d32f2f;
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .success-icon {
            width: 50px;
            height: 50px;
            background: #4CAF50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: checkmark 0.6s ease-in-out;
        }

        .error-icon {
            width: 50px;
            height: 50px;
            background: #f44336;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: shake 0.6s ease-in-out;
        }

        @keyframes checkmark {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .success-icon i, .error-icon i {
            color: white;
            font-size: 1.5rem;
        }

        .message-text {
            font-size: 1.2rem;
            color: #333;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 25px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
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
            background: linear-gradient(45deg, #4CAF50, #2E7D32);
            color: white;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(45deg, #2196F3, #1976D2);
            color: white;
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        .error-details {
            background: #ffebee;
            border: 1px solid #ffcdd2;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #c62828;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            text-align: left;
        }

        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .btn-container {
                flex-direction: column;
            }
            
            .success-message, .error-message {
                font-size: 1.2rem;
            }
        }

        .vet-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.1;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23000000' fill-opacity='0.1'%3E%3Cpath d='M30 30c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20zm0 0c0 11.046 8.954 20 20 20s20-8.954 20-20-8.954-20-20-20-20 8.954-20 20z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="vet-pattern"></div>
    <div class="container">
        <div class="logo">
            <i class="fas fa-paw"></i>
        </div>
        
        <?php if ($success): ?>
            <div class="success-message">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <h2 class="message-text">¡Cita registrada correctamente!</h2>
            <p style="color: #666; margin-bottom: 20px;">
                La cita ha sido programada exitosamente. Recibirás una confirmación pronto.
            </p>
            
            <div class="btn-container">
                <a href="nueva_cita.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Registrar otra cita
                </a>
                <a href="ver_citas.php" class="btn btn-secondary">
                    <i class="fas fa-calendar-alt"></i>
                    Ver todas las citas
                </a>
                <a href="vete.html" class="btn btn-outline">
                    <i class="fas fa-home"></i>
                    Regresar al menú principal
                </a>
            </div>
            
        <?php else: ?>
            <div class="error-message">
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            <h2 class="message-text">Error al registrar la cita</h2>
            <p style="color: #666; margin-bottom: 20px;">
                Lo sentimos, ocurrió un problema al procesar tu solicitud.
            </p>
            
            <div class="error-details">
                <strong>Detalles del error:</strong><br>
                <?php echo htmlspecialchars($stmt->error); ?>
            </div>
            
            <div class="btn-container">
                <a href="nueva_cita.php" class="btn btn-primary">
                    <i class="fas fa-redo"></i>
                    Intentar nuevamente
                </a>
                <a href="vete.html" class="btn btn-outline">
                    <i class="fas fa-home"></i>
                    Regresar al menú principal
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Añadir efecto de confeti para éxito
        <?php if ($success): ?>
        function createConfetti() {
            const colors = ['#4CAF50', '#2E7D32', '#81C784', '#A5D6A7'];
            for (let i = 0; i < 100; i++) {
                const confetti = document.createElement('div');
                confetti.style.cssText = `
                    position: fixed;
                    width: 10px;
                    height: 10px;
                    background: ${colors[Math.floor(Math.random() * colors.length)]};
                    left: ${Math.random() * 100}vw;
                    top: -10px;
                    border-radius: 50%;
                    pointer-events: none;
                    z-index: 1000;
                    animation: confetti-fall ${Math.random() * 3 + 2}s linear forwards;
                `;
                document.body.appendChild(confetti);
                
                setTimeout(() => confetti.remove(), 5000);
            }
        }
        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes confetti-fall {
                to {
                    transform: translateY(100vh) rotate(360deg);
                }
            }
        `;
        document.head.appendChild(style);
        
        setTimeout(createConfetti, 500);
        <?php endif; ?>
        
        // Añadir efectos de hover mejorados
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.02)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>
</body>
</html>

<?php
$stmt->close();
$conexion->close();
?>

