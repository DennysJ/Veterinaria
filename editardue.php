<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);

// Verifica conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT nombre, telefono, direccion FROM duenos WHERE id_dueno = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
} else {
    die("ID de dueño no especificado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Dueño - Veterinaria</title>
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
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
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

        .header {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
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

        .header h2 {
            font-size: 2rem;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header .icon {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .form-container {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .form-group .icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            z-index: 1;
        }

        .form-group input[type="text"] {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input[type="text"]:focus {
            outline: none;
            border-color: #4CAF50;
            background: white;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        .form-group input[type="text"]:hover {
            border-color: #4CAF50;
        }

        .btn-container {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-align: center;
            min-width: 140px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #45a049, #3d8b40);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #5a6268, #495057);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            border-left: 4px solid;
        }

        .alert-info {
            background-color: #d1ecf1;
            border-color: #17a2b8;
            color: #0c5460;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .form-container {
                padding: 30px 20px;
            }
            
            .btn-container {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }

        .paw-print {
            position: absolute;
            opacity: 0.05;
            font-size: 2rem;
            color: #4CAF50;
        }

        .paw-1 { top: 10%; left: 10%; }
        .paw-2 { top: 20%; right: 15%; }
        .paw-3 { bottom: 30%; left: 20%; }
        .paw-4 { bottom: 10%; right: 10%; }
    </style>
</head>
<body>
    <div class="paw-print paw-1"><i class="fas fa-paw"></i></div>
    <div class="paw-print paw-2"><i class="fas fa-paw"></i></div>
    <div class="paw-print paw-3"><i class="fas fa-paw"></i></div>
    <div class="paw-print paw-4"><i class="fas fa-paw"></i></div>

    <div class="container">
        <div class="header">
            <div class="icon">
                <i class="fas fa-user-edit"></i>
            </div>
            <h2>Editar Información del Dueño</h2>
            <p>Actualiza los datos del propietario de la mascota</p>
        </div>

        <div class="form-container">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Modifica los campos necesarios y presiona "Actualizar" para guardar los cambios.
            </div>

            <form action="editdue.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="form-group">
                    <label for="nombre">
                        <i class="fas fa-user"></i> Nombre Completo
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-user icon"></i>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="<?php echo htmlspecialchars($row['nombre']); ?>" 
                               required 
                               placeholder="Ingrese el nombre completo">
                    </div>
                </div>

                <div class="form-group">
                    <label for="telefono">
                        <i class="fas fa-phone"></i> Número de Teléfono
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-phone icon"></i>
                        <input type="text" 
                               id="telefono" 
                               name="telefono" 
                               value="<?php echo htmlspecialchars($row['telefono']); ?>" 
                               required 
                               placeholder="Ingrese el número de teléfono">
                    </div>
                </div>

                <div class="form-group">
                    <label for="direccion">
                        <i class="fas fa-map-marker-alt"></i> Dirección
                    </label>
                    <div style="position: relative;">
                        <i class="fas fa-map-marker-alt icon"></i>
                        <input type="text" 
                               id="direccion" 
                               name="direccion" 
                               value="<?php echo htmlspecialchars($row['direccion']); ?>" 
                               required 
                               placeholder="Ingrese la dirección completa">
                    </div>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Actualizar
                    </button>
                    <a href="duenos.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Regresar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Animación suave para los inputs
        document.querySelectorAll('input[type="text"]').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Validación en tiempo real
        document.getElementById('telefono').addEventListener('input', function(e) {
            // Permitir solo números, espacios, guiones y paréntesis
            this.value = this.value.replace(/[^0-9\s\-\(\)]/g, '');
        });

        // Confirmación antes de actualizar
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!confirm('¿Está seguro de que desea actualizar la información del dueño?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>