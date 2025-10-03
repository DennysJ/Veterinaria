<?php
$mysqli = new mysqli("localhost", "root", "", "vete");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Manejo de la subida de la imagen
    $ruta_foto = '';
    if (isset($_FILES["txtfoto"]) && $_FILES["txtfoto"]["error"] == UPLOAD_ERR_OK) {
        $nombreArchivo = basename($_FILES["txtfoto"]["name"]);
        $rutaDestino = "fotos" . $nombreArchivo;

        // Asegúrate de que la carpeta "fotos/" exista y tenga permisos de escritura
        if (move_uploaded_file($_FILES["txtfoto"]["tmp_name"], $rutaDestino)) {
            $ruta_foto = $rutaDestino;
        }
    }

    // Recolección de los demás datos del formulario
    $foto = $ruta_foto;
    $nombre = $_POST["txtnombre"] ?? '';
    $raza = $_POST["txtraza"] ?? '';
    $especie = $_POST["txtespecie"] ?? '';
    $edad = $_POST["txtedad"] ?? '';
    $genero = $_POST["txtgenero"] ?? '';
    $peso = $_POST["txtpeso"] ?? ''; 
    $salud = $_POST["txtsalud"] ?? '';
    $dueno = $_POST["txtdueno"] ?? '';
    
    if (!empty($nombre) && !empty($dueno)) {
        $stmt = $mysqli->prepare("INSERT INTO mascotas (foto, nombre, raza, especie, edad, genero, peso, estado, id_dueno) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $foto, $nombre, $raza, $especie, $edad, $genero, $peso, $salud, $dueno);
        
        if ($stmt->execute()) {
            $success_message = "La mascota ha sido registrada exitosamente.";
        } else {
            $error_message = "Error al registrar la mascota: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Los campos Nombre y Dueño son obligatorios.";
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VetCare - Registro de Mascotas</title>
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
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            background: white;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .logo i {
            font-size: 32px;
            color: #4a90e2;
        }

        .main-title {
            color: white;
            font-size: 32px;
            font-weight: 300;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .subtitle {
            color: rgba(255,255,255,0.8);
            font-size: 16px;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            position: relative;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .required {
            color: #e74c3c;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        .form-input:focus {
            outline: none;
            border-color: #4a90e2;
            background: white;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
            transform: translateY(-1px);
        }

        .form-select {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 16px;
            background: #fafbfc;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #4a90e2;
            background: white;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-upload input[type=file] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 20px;
            border: 2px dashed #4a90e2;
            border-radius: 12px;
            background: #f8fbff;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #4a90e2;
            font-weight: 500;
        }

        .file-upload-label:hover {
            background: #e8f4ff;
            border-color: #2980b9;
        }

        .file-upload-label i {
            margin-right: 10px;
            font-size: 18px;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 35px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
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
            background: linear-gradient(135deg, #4a90e2, #357abd);
            color: white;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(74, 144, 226, 0.4);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #6c757d;
            border: 2px solid #e9ecef;
        }

        .btn-secondary:hover {
            background: #e9ecef;
            transform: translateY(-1px);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .alert-success {
            background: #d1edff;
            color: #0c5460;
            border: 1px solid #b8daff;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f1aeb5;
        }

        .pet-icon {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ff9a56, #ff6b95);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            box-shadow: 0 4px 12px rgba(255, 154, 86, 0.3);
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-card {
                margin: 20px;
                padding: 30px 20px;
            }
            
            .button-group {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 250px;
            }
        }

        .floating-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .floating-paw {
            position: absolute;
            color: rgba(255,255,255,0.1);
            animation: float 6s ease-in-out infinite;
        }

        .floating-paw:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-paw:nth-child(2) { top: 60%; right: 15%; animation-delay: 2s; }
        .floating-paw:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 4s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
    </style>
</head>
<body>
    <div class="floating-elements">
        <i class="fas fa-paw floating-paw" style="font-size: 60px;"></i>
        <i class="fas fa-paw floating-paw" style="font-size: 45px;"></i>
        <i class="fas fa-paw floating-paw" style="font-size: 55px;"></i>
    </div>

    <div class="container">
        <div class="header">
            <div class="logo">
                <i class="fas fa-heart"></i>
            </div>
            <h1 class="main-title">MoritosPet</h1>
            <p class="subtitle">Sistema de Registro de Mascotas</p>
        </div>

        <div class="form-card">
            <div class="pet-icon">
                <i class="fas fa-dog"></i>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo $success_message; ?>
                    <br><br>
                    <a href="consulta.php" style="color: #0c5460; text-decoration: underline;">
                        <i class="fas fa-arrow-left"></i> Volver al listado
                    </a>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <form action="alta.php" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">
                            <i class="fas fa-camera"></i> Fotografía de la mascota
                        </label>
                        <div class="file-upload">
                            <input type="file" name="txtfoto" id="txtfoto" accept="image/*">
                            <label for="txtfoto" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Seleccionar imagen
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="txtnombre">
                            <i class="fas fa-tag"></i> Nombre <span class="required">*</span>
                        </label>
                        <input type="text" name="txtnombre" id="txtnombre" class="form-input" 
                               placeholder="Nombre de la mascota" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="txtraza">
                            <i class="fas fa-dna"></i> Raza
                        </label>
                        <input type="text" name="txtraza" id="txtraza" class="form-input" 
                               placeholder="Raza del animal">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="txtespecie">
                            <i class="fas fa-dna"></i> Especie
                        </label>
                        <input type="text" name="txtespecie" id="txtEspecie" class="form-input" 
                               placeholder="Especie del animal">

                    <div class="form-group">
                        <label class="form-label" for="txtedad">
                            <i class="fas fa-birthday-cake"></i> Edad
                        </label>
                        <input type="text" name="txtedad" id="txtedad" class="form-input" 
                               placeholder="Ej: 2 años, 6 meses">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="txtgenero">
                            <i class="fas fa-venus-mars"></i> Género
                        </label>
                        <select name="txtgenero" id="txtgenero" class="form-select">
                            <option value="">Seleccionar género</option>
                            <option value="Macho">Macho</option>
                            <option value="Hembra">Hembra</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="txtpeso">
                            <i class="fas fa-weight"></i> Peso
                        </label>
                        <input type="text" name="txtpeso" id="txtpeso" class="form-input" 
                               placeholder="Ej: 15.5 kg">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="txtsalud">
                            <i class="fas fa-heartbeat"></i> Estado
                        </label>
                        <input type="text" name="txtsalud" id="txtsalud" class="form-input" 
                               placeholder="Estado general">
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label" for="txtdueno">
                            <i class="fas fa-user"></i> Propietario <span class="required">*</span>
                        </label>
                        <select name="txtdueno" id="txtdueno" class="form-select" required>
                            <option value="">Seleccionar propietario</option>
                            <?php
                            $resultado = $mysqli->query("SELECT id_dueno, nombre FROM duenos ORDER BY nombre");
                            if ($resultado) {
                                while ($fila = $resultado->fetch_assoc()) {
                                    echo "<option value='{$fila['id_dueno']}'>{$fila['nombre']} (ID: {$fila['id_dueno']})</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
<p>
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Registrar Mascota
                    </button>
                    <a href="consulta.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
                        </p>
    <script>
        // Efecto para el label del archivo
        document.getElementById('txtfoto').addEventListener('change', function(e) {
            const label = document.querySelector('.file-upload-label');
            const fileName = e.target.files[0]?.name;
            
            if (fileName) {
                label.innerHTML = `<i class="fas fa-check"></i> ${fileName}`;
                label.style.color = '#27ae60';
                label.style.borderColor = '#27ae60';
                label.style.background = '#f0fff4';
            }
        });

        // Animación de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.form-card');
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 200);
        });
    </script>
</body>
</html>

<?php
$mysqli->close();
?>

