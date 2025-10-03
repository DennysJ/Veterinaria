<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Dueños - Veterinaria</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .header {
            color: #333;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #5a67d8;
        }

        .header p {
            color: #666;
            font-size: 1.1em;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 1.1em;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
            background: #f8fafc;
        }

        .form-group input:focus {
            outline: none;
            border-color: #5a67d8;
            box-shadow: 0 0 0 3px rgba(90, 103, 216, 0.1);
            background: white;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
            min-width: 140px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #5a67d8, #667eea);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(90, 103, 216, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #718096, #4a5568);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(113, 128, 150, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(72, 187, 120, 0.3);
        }

        .success-message {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            font-size: 1.3em;
            font-weight: 600;
        }

        .success-message h2 {
            font-size: 2em;
            margin-bottom: 15px;
        }

        .icon {
            font-size: 3em;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 25px;
                margin: 10px;
            }
            
            .header h1 {
                font-size: 2em;
            }
            
            .btn {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Verificar si se enviaron datos del formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["txtnombre"])) {
            $nombre = $_POST["txtnombre"];
            $telefono = $_POST["txttelefono"];
            $direccion = $_POST["txtdireccion"];
            
            // Validación básica
            if (!empty($nombre) && !empty($telefono) && !empty($direccion)) {
                try {
                    $mysqli = new mysqli("localhost", "root", "", "vete");
                    
                    // Verificar conexión
                    if ($mysqli->connect_error) {
                        throw new Exception("Error de conexión: " . $mysqli->connect_error);
                    }
                    
                    // Preparar la consulta para evitar inyección SQL
                    $stmt = $mysqli->prepare("INSERT INTO duenos (nombre, telefono, direccion) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $nombre, $telefono, $direccion);
                    
                    if ($stmt->execute()) {
                        echo '<div class="success-message">';
                        echo '<div class="icon">✅</div>';
                        echo '<h2>¡Éxito!</h2>';
                        echo '<p>El dueño <strong>' . htmlspecialchars($nombre) . '</strong> ha sido registrado exitosamente.</p>';
                        echo '</div>';
                        echo '<a href="' . $_SERVER['PHP_SELF'] . '" class="btn btn-success">Agregar Otro Dueño</a>';
                        echo '<a href="consulta.php" class="btn btn-secondary">Regresar al Menú</a>';
                    } else {
                        throw new Exception("Error al insertar datos");
                    }
                    
                    $stmt->close();
                    $mysqli->close();
                    
                } catch (Exception $e) {
                    echo '<div style="background: #f56565; color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px;">';
                    echo '<h3>Error</h3>';
                    echo '<p>' . $e->getMessage() . '</p>';
                    echo '</div>';
                    echo '<a href="' . $_SERVER['PHP_SELF'] . '" class="btn btn-primary">Intentar de Nuevo</a>';
                }
            } else {
                echo '<div style="background: #f6ad55; color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px;">';
                echo '<h3>Advertencia</h3>';
                echo '<p>Por favor, completa todos los campos.</p>';
                echo '</div>';
                echo '<a href="' . $_SERVER['PHP_SELF'] . '" class="btn btn-primary">Volver al Formulario</a>';
            }
        } else {
            // Mostrar formulario
        ?>
            <div class="header">
                <div class="icon">🐕</div>
                <h1>Registro de Dueños</h1>
                <p>Sistema de Gestión Veterinaria</p>
            </div>
            
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                    <label for="txtnombre">👤 Nombre Completo:</label>
                    <input type="text" id="txtnombre" name="txtnombre" required 
                           placeholder="Ingresa el nombre completo del dueño">
                </div>
                
                <div class="form-group">
                    <label for="txttelefono">📞 Teléfono:</label>
                    <input type="tel" id="txttelefono" name="txttelefono" required 
                           placeholder="Ej: +52 55 1234 5678">
                </div>
                
                <div class="form-group">
                    <label for="txtdireccion">🏠 Dirección:</label>
                    <input type="text" id="txtdireccion" name="txtdireccion" required 
                           placeholder="Dirección completa">
                </div>
                
                <div style="margin-top: 30px;">
                    <button type="submit" class="btn btn-primary">
                        ➕ Registrar Dueño
                    </button>
                    <a href="consulta.php" class="btn btn-secondary">
                        🔙 Regresar al Menú
                    </a>
                </div>
            </form>
        <?php } ?>
    </div>
</body>
</html>