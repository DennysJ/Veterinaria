<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Mascota - Sistema Veterinario</title>
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
            padding: 40px;
            max-width: 500px;
            width: 100%;
            text-align: center;
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
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .icon-container {
            margin-bottom: 30px;
        }

        .success-icon {
            color: #28a745;
            font-size: 4rem;
            margin-bottom: 20px;
            animation: successPulse 2s ease-in-out;
        }

        .error-icon {
            color: #dc3545;
            font-size: 4rem;
            margin-bottom: 20px;
            animation: errorShake 0.5s ease-in-out;
        }

        .warning-icon {
            color: #ffc107;
            font-size: 4rem;
            margin-bottom: 20px;
            animation: warningBounce 1s ease-in-out;
        }

        @keyframes successPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        @keyframes warningBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 2rem;
            font-weight: 600;
        }

        .message {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
            color: #666;
        }

        .success-message {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .error-message {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .warning-message {
            color: #856404;
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            margin-left: 15px;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(108, 117, 125, 0.3);
        }

        .loading {
            display: none;
            margin: 20px 0;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
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

        .pet-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            .btn {
                padding: 10px 20px;
                font-size: 0.9rem;
                margin: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            
            // Primero obtenemos la información de la mascota antes de eliminarla
            $info_sql = "SELECT nombre, especie FROM mascotas WHERE id_mascota = $id";
            $info_result = $mysqli->query($info_sql);
            $mascota_info = $info_result ? $info_result->fetch_assoc() : null;
            
            $sql = "DELETE FROM mascotas WHERE id_mascota = $id";
            
            if ($mysqli->query($sql)) {
                if ($mysqli->affected_rows > 0) {
                    echo '<div class="icon-container">';
                    echo '<i class="fas fa-check-circle success-icon"></i>';
                    echo '</div>';
                    echo '<h1>¡Eliminación Exitosa!</h1>';
                    
                    if ($mascota_info) {
                        echo '<div class="pet-info">';
                        echo '<strong>Mascota eliminada:</strong><br>';
                        echo 'Nombre: ' . htmlspecialchars($mascota_info['nombre']) . '<br>';
                        echo 'Especie: ' . htmlspecialchars($mascota_info['especie']);
                        echo '</div>';
                    }
                    
                    echo '<div class="success-message">';
                    echo '<i class="fas fa-info-circle"></i> La mascota ha sido eliminada correctamente del sistema.';
                    echo '</div>';
                } else {
                    echo '<div class="icon-container">';
                    echo '<i class="fas fa-exclamation-triangle warning-icon"></i>';
                    echo '</div>';
                    echo '<h1>Mascota No Encontrada</h1>';
                    echo '<div class="warning-message">';
                    echo '<i class="fas fa-search"></i> No se encontró ninguna mascota con el ID especificado.';
                    echo '</div>';
                }
            } else {
                echo '<div class="icon-container">';
                echo '<i class="fas fa-times-circle error-icon"></i>';
                echo '</div>';
                echo '<h1>Error en la Eliminación</h1>';
                echo '<div class="error-message">';
                echo '<i class="fas fa-exclamation-triangle"></i> Error al eliminar la mascota: ' . htmlspecialchars($mysqli->error);
                echo '</div>';
            }
        } else {
            echo '<div class="icon-container">';
            echo '<i class="fas fa-exclamation-triangle warning-icon"></i>';
            echo '</div>';
            echo '<h1>ID No Válido</h1>';
            echo '<div class="warning-message">';
            echo '<i class="fas fa-id-card"></i> No se proporcionó un ID válido para la eliminación.';
            echo '</div>';
        }
        ?>
        
        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Redirigiendo...</p>
        </div>
        
        <div style="margin-top: 30px;">
            
            <a href=consulta.php" class="btn btn-secondary" onclick="showLoading()">
                <i class="fas fa-home"></i>
                Inicio
            </a>
        </div>
    </div>

    <script>
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
        }
        
        // Auto-redirect después de 3 segundos en caso de éxito
        <?php if (isset($_GET['id']) && $mysqli->query($sql) && $mysqli->affected_rows > 0): ?>
        setTimeout(function() {
            showLoading();
            window.location.href = 'consulta.php';
        }, 3000);
        <?php endif; ?>
    </script>
</body>
</html>
