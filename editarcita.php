<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Si se envió el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mascota = $_POST['mascota'];
        $diagnostico = $_POST['diagnostico'];
        $tratamiento = $_POST['tratamiento'];
        $medicamentos = $_POST['medicamentos'];
        $observaciones = $_POST['observaciones'];
        $fecha = $_POST['fecha'];
        
        
        $sql = "UPDATE recetas SET
            mascota=?, diagnostico=?, tratamiento=?, medicamentos=?, observaciones=?, fecha=?, costo_consulta=?
            WHERE id_recetas=?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sssssssi", $mascota, $diagnostico, $tratamiento, $medicamentos, $observaciones, $fecha, $costo, $id);
        
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = "Error al actualizar: " . $mysqli->error;
        }
    }
    
    // Obtener los datos actuales
    $sql = "SELECT * FROM recetas WHERE id_recetas = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();
} else {
    echo "ID no especificado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Receta Veterinaria</title>
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
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .form-container {
            padding: 40px;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-grid {
            display: grid;
            gap: 25px;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e6ed;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            background: white;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            transform: translateY(-2px);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
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
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 150px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(52, 152, 219, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(149, 165, 166, 0.3);
        }

        .cost-input {
            position: relative;
        }

        .cost-input::before {
            content: "$";
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            font-weight: bold;
            z-index: 1;
        }

        .cost-input input {
            padding-left: 40px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }

            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .form-container {
                padding: 25px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .btn-container {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 250px;
            }
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <i class="fas fa-edit"></i>
                Editar Consultas Veterinaria
            </h1>
            <p>Actualiza la información de la consulta médica</p>
        </div>

        <div class="form-container">
            <?php if (isset($success) && $success): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i>
                    Receta actualizada correctamente.
                    <br><br>
                    <a href='consultaci.php' class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Volver a la lista
                    </a>
                </div>
            <?php elseif (isset($error)): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($success) || !$success): ?>
                <form method="post" id="editForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="mascota">
                                <i class="fas fa-paw"></i>
                                Nombre de la Mascota
                            </label>
                            <input type="text" 
                                   id="mascota" 
                                   name="mascota" 
                                   value="<?php echo htmlspecialchars($fila['mascota']); ?>" 
                                   required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="fecha">
                                    <i class="fas fa-calendar-alt"></i>
                                    Fecha de Consulta
                                </label>
                                <input type="date" 
                                       id="fecha" 
                                       name="fecha" 
                                       value="<?php echo $fila['fecha']; ?>" 
                                       required>
                            </div>

                        
                        </div>

                        <div class="form-group">
                            <label for="diagnostico">
                                <i class="fas fa-stethoscope"></i>
                                Diagnóstico
                            </label>
                            <textarea id="diagnostico" 
                                      name="diagnostico" 
                                      required><?php echo htmlspecialchars($fila['diagnostico']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="tratamiento">
                                <i class="fas fa-heartbeat"></i>
                                Tratamiento
                            </label>
                            <textarea id="tratamiento" 
                                      name="tratamiento" 
                                      required><?php echo htmlspecialchars($fila['tratamiento']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="medicamentos">
                                <i class="fas fa-pills"></i>
                                Medicamentos
                            </label>
                            <textarea id="medicamentos" 
                                      name="medicamentos" 
                                      required><?php echo htmlspecialchars($fila['medicamentos']); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="observaciones">
                                <i class="fas fa-clipboard-list"></i>
                                Observaciones Adicionales
                            </label>
                            <textarea id="observaciones" 
                                      name="observaciones"><?php echo htmlspecialchars($fila['observaciones']); ?></textarea>
                        </div>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Guardar Cambios
                        </button>
                        <a href="consultaci.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </a>
                    </div>
                </form>

                <div class="loading" id="loading">
                    <div class="spinner"></div>
                    <p>Guardando cambios...</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.getElementById('editForm').addEventListener('submit', function() {
            document.getElementById('loading').style.display = 'block';
            this.style.display = 'none';
        });

        // Validación en tiempo real
        document.querySelectorAll('input[required], textarea[required]').forEach(function(field) {
            field.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.style.borderColor = '#e74c3c';
                } else {
                    this.style.borderColor = '#27ae60';
                }
            });
        });

        // Auto-resize para textareas
        document.querySelectorAll('textarea').forEach(function(textarea) {
            function autoResize() {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            }
            
            textarea.addEventListener('input', autoResize);
            autoResize(); // Ejecutar al cargar
        });
    </script>
</body>
</html>