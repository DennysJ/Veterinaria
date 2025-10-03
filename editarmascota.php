<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $raza = $_POST['raza'];
    $especie = $_POST['especie'];
    $edad = $_POST['edad'];
    $genero = $_POST['genero'];
    $peso = $_POST['peso'];
    $estado = $_POST['estado'];
    $foto = $_FILES['foto']['name'] ? 'fotos/' . $_FILES['foto']['name'] : $_POST['foto_actual'];

    if ($_FILES['foto']['tmp_name']) {
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    }

    $sql = "UPDATE mascotas SET nombre=?, raza=?, especie=?, edad=?, genero=?, peso=?, estado=?, foto=? WHERE id_mascota=?";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    die("Error al preparar: " . $mysqli->error);
}

$stmt->bind_param("ssssssssi", $nombre, $raza, $especie, $edad, $genero, $peso, $estado, $foto, $id);

if ($stmt->execute()) {
    echo "<script>alert('Mascota actualizada correctamente'); window.location.href='consulta.php';</script>";
} else {
    echo "Error al actualizar: " . $stmt->error;

    }
    $stmt->close();
}

// Consulta para mostrar datos si es GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT foto, nombre, raza, especie, edad, genero, peso, estado FROM mascotas WHERE id_mascota = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
} else {
    die("ID de mascota no especificado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mascota - Sistema Veterinario</title>
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
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h2 {
            font-size: 2.2em;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .header p {
            opacity: 0.9;
            font-size: 1.1em;
        }

        .form-container {
            padding: 40px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 0.95em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4facfe;
            background: white;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .image-preview {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 2px dashed #dee2e6;
        }

        .image-preview img {
            max-width: 200px;
            max-height: 200px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            min-width: 150px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }

        .info-card {
            background: linear-gradient(135deg, #ffeaa7 0%, #fab1a0 100%);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .info-card h3 {
            color: #2d3436;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card p {
            color: #636e72;
            margin: 0;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .buttons {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn {
                min-width: auto;
            }
            
            .container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .form-container {
                padding: 25px;
            }
        }

        .loading {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #4facfe;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
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
            <h2><i class="fas fa-edit"></i> Editar Información de Mascota</h2>
            <p>Actualiza los datos de la mascota en el sistema</p>
        </div>

        <div class="form-container">
            <div class="info-card">
                <h3><i class="fas fa-info-circle"></i> Información Actual</h3>
                <p>Editando los datos de: <strong><?php echo htmlspecialchars($row['nombre']); ?></strong></p>
            </div>

            <?php if (!empty($row['foto'])): ?>
            <div class="image-preview">
                <h4><i class="fas fa-image"></i> Imagen Actual</h4>
                <img src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto de <?php echo htmlspecialchars($row['nombre']); ?>" onerror="this.style.display='none'">
            </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" id="editForm">

                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="foto"><i class="fas fa-camera"></i> URL de la Foto</label>
<input type="file" id="foto" name="foto" accept="image/*">
<input type="hidden" name="foto_actual" value="<?php echo htmlspecialchars($row['foto']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="nombre"><i class="fas fa-tag"></i> Nombre *</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($row['nombre']); ?>" required placeholder="Nombre de la mascota">
                    </div>

                    <div class="form-group">
                        <label for="raza"><i class="fas fa-dna"></i> Raza *</label>
                        <input type="text" id="raza" name="raza" value="<?php echo htmlspecialchars($row['raza']); ?>" required placeholder="Raza de la mascota">
                    </div>

                    <div class="form-group">
                        <label for="especie"><i class="fas fa-paw"></i> Especie *</label>
                        <select id="especie" name="especie" required>
                            <option value="">Seleccionar especie</option>
                            <option value="Perro" <?php echo ($row['especie'] == 'Perro') ? 'selected' : ''; ?>>Perro</option>
                            <option value="Gato" <?php echo ($row['especie'] == 'Gato') ? 'selected' : ''; ?>>Gato</option>
                            <option value="Ave" <?php echo ($row['especie'] == 'Ave') ? 'selected' : ''; ?>>Ave</option>
                            <option value="Conejo" <?php echo ($row['especie'] == 'Conejo') ? 'selected' : ''; ?>>Conejo</option>
                            <option value="Hamster" <?php echo ($row['especie'] == 'Hamster') ? 'selected' : ''; ?>>Hamster</option>
                            <option value="Otro" <?php echo ($row['especie'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edad"><i class="fas fa-calendar-alt"></i> Edad *</label>
                        <input type="text" id="edad" name="edad" value="<?php echo htmlspecialchars($row['edad']); ?>" required placeholder="Ej: 2 años, 6 meses">
                    </div>

                    <div class="form-group">
                        <label for="genero"><i class="fas fa-venus-mars"></i> Género *</label>
                        <select id="genero" name="genero" required>
                            <option value="">Seleccionar género</option>
                            <option value="Macho" <?php echo ($row['genero'] == 'Macho') ? 'selected' : ''; ?>>Macho</option>
                            <option value="Hembra" <?php echo ($row['genero'] == 'Hembra') ? 'selected' : ''; ?>>Hembra</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="peso"><i class="fas fa-weight"></i> Peso *</label>
                        <input type="text" id="peso" name="peso" value="<?php echo htmlspecialchars($row['peso']); ?>" required placeholder="Ej: 5.2 kg">
                    </div>

                    <div class="form-group full-width">
    <label for="estado"><i class="fas fa-heartbeat"></i> Estado *</label>
    <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($row['estado']); ?>" required placeholder="Ej: Saludable, Enfermo, Recuperación">
</div>

                </div>

                <div class="buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Actualizar Mascota
                    </button>
                    <a href="consulta.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Regresar
                    </a>
                </div>

                <div class="loading" id="loading">
                    <div class="spinner"></div>
                    <p>Actualizando información...</p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Validación del formulario y efectos
        document.getElementById('editForm').addEventListener('submit', function(e) {
            const loading = document.getElementById('loading');
            loading.style.display = 'block';
        });

        // Previsualización de imagen
        document.getElementById('foto').addEventListener('input', function(e) {
            const url = e.target.value;
            const preview = document.querySelector('.image-preview');
            
            if (url && url.match(/\.(jpeg|jpg|gif|png|webp)$/i)) {
                if (!preview) {
                    const newPreview = document.createElement('div');
                    newPreview.className = 'image-preview';
                    newPreview.innerHTML = '<h4><i class="fas fa-image"></i> Vista Previa</h4>';
                    document.querySelector('.form-container').insertBefore(newPreview, document.querySelector('form'));
                }
                
                const img = document.createElement('img');
                img.src = url;
                img.alt = 'Vista previa';
                img.onerror = function() { this.style.display = 'none'; };
                
                const existingImg = preview.querySelector('img');
                if (existingImg) {
                    preview.replaceChild(img, existingImg);
                } else {
                    preview.appendChild(img);
                }
            }
        });

        // Validación en tiempo real
        document.querySelectorAll('input[required], select[required]').forEach(field => {
            field.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.style.borderColor = '#dc3545';
                } else {
                    this.style.borderColor = '#28a745';
                }
            });
        });
    </script>
</body>
</html>