<?php
$mysqli = new mysqli("localhost", "root", "", "vete");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$mensaje = "";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Actualizar cita si se envió el formulario
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $motivo = $_POST['motivo'];
        $mascota = $_POST['mascota'];
        $veterinario = $_POST['veterinario'];
        $stmt = $mysqli->prepare("UPDATE citas SET fecha=?, hora=?, motivo=?, mascota=?, veterinario=? WHERE id_citas=?");
        $stmt->bind_param("sssssi", $fecha, $hora, $motivo, $mascota, $veterinario, $id);
        $stmt->execute();
        $stmt->close();
        $mensaje = "✅ Cita actualizada correctamente.";
    }
    // Obtener datos de la cita
    $stmt = $mysqli->prepare("SELECT * FROM citas WHERE id_citas = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $cita = $resultado->fetch_assoc();
    $stmt->close();
    if (!$cita) {
        die("❌ Cita no encontrada.");
    }
} else {
    die("❌ ID no proporcionado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita - Veterinaria</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 600px;
            width: 100%;
            position: relative;
        }

        .header {
            background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: "🐾";
            position: absolute;
            left: 30px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2em;
            opacity: 0.3;
        }

        .header::after {
            content: "🐾";
            position: absolute;
            right: 30px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2em;
            opacity: 0.3;
        }

        h2 {
            font-size: 2.2em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .subtitle {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .form-content {
            padding: 40px;
        }

        .mensaje {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 5px solid #28a745;
            font-weight: 600;
            text-align: center;
            animation: slideIn 0.5s ease;
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

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            display: block;
            font-weight: 600;
            color: #2e7d32;
            margin-bottom: 8px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .label-fecha::before { content: "📅"; }
        .label-hora::before { content: "🕐"; }
        .label-motivo::before { content: "📝"; }
        .label-mascota::before { content: "🐕"; }
        .label-veterinario::before { content: "👩‍⚕️"; }

        input[type="date"],
        input[type="time"],
        input[type="text"],
        textarea {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fafafa;
            font-family: inherit;
        }

        input[type="date"]:focus,
        input[type="time"]:focus,
        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: #4caf50;
            background: white;
            box-shadow: 0 0 0 4px rgba(76, 175, 80, 0.1);
            transform: translateY(-2px);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }

        .button-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            flex: 1;
            padding: 15px 25px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-save {
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
            color: white;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
        }

        .btn-save::before {
            content: "💾";
        }

        .btn-back {
            background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
            color: white;
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(33, 150, 243, 0.3);
        }

        .btn-back::before {
            content: "↩️";
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .input-icon {
            position: relative;
        }

        .input-icon::after {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #4caf50;
            font-size: 1.2em;
            pointer-events: none;
        }

        .decorative-element {
            position: absolute;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.1) 0%, rgba(46, 125, 50, 0.1) 100%);
            border-radius: 50%;
            top: -50px;
            right: -50px;
            z-index: -1;
        }

        .decorative-element::before {
            content: "";
            position: absolute;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.15) 0%, rgba(46, 125, 50, 0.15) 100%);
            border-radius: 50%;
            bottom: -30px;
            left: -30px;
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .container {
                border-radius: 15px;
            }
            
            .header {
                padding: 25px 20px;
            }
            
            .header::before,
            .header::after {
                display: none;
            }
            
            h2 {
                font-size: 1.8em;
            }
            
            .form-content {
                padding: 25px 20px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .button-container {
                flex-direction: column;
            }
            
            input[type="date"],
            input[type="time"],
            input[type="text"],
            textarea {
                padding: 12px 15px;
                font-size: 16px;
            }
        }

        /* Animaciones de entrada */
        .form-group {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="decorative-element"></div>
        
        <div class="header">
            <h2>Editar Cita</h2>
            <p class="subtitle">Modificar información de la cita veterinaria</p>
        </div>

        <div class="form-content">
            <?php if ($mensaje): ?>
                <div class="mensaje"><?php echo $mensaje; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label class="label-fecha">Fecha:</label>
                        <input type="date" name="fecha" value="<?php echo $cita['fecha']; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="label-hora">Hora:</label>
                        <input type="time" name="hora" value="<?php echo $cita['hora']; ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="label-motivo">Motivo de la consulta:</label>
                    <textarea name="motivo" required placeholder="Describe el motivo de la consulta..."><?php echo htmlspecialchars($cita['motivo']); ?></textarea>
                </div>

                <div class="form-group">
                    <label class="label-mascota">Nombre de la mascota:</label>
                    <input type="text" name="mascota" value="<?php echo htmlspecialchars($cita['mascota']); ?>" required placeholder="Ej: Max, Luna, Rocky...">
                </div>

                <div class="form-group">
                    <label class="label-veterinario">Veterinario asignado:</label>
                    <input type="text" name="veterinario" value="<?php echo htmlspecialchars($cita['veterinario']); ?>" required placeholder="Dr. Nombre Apellido">
                </div>

                <div class="button-container">
                    <button type="submit" class="btn btn-save">Guardar Cambios</button>
                    <a href="consultaconsulta.php" class="btn btn-back">Volver a Lista</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
