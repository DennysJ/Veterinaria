<?php
$host = "localhost";
$dbname = "vete";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['txtusuario'];
    $contrasena = $_POST['txtcontrasena'];
    
    $stmt = $pdo->prepare("SELECT rol, nombre FROM usuarios WHERE usuario = :usuario AND contrasena = :contrasena");
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':contrasena', $contrasena);
    $stmt->execute();
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MoritosPet - Sistema de Gestión Veterinaria</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            
            body {
                font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
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
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                padding: 40px;
                text-align: center;
                max-width: 500px;
                width: 100%;
                animation: slideIn 0.6s ease-out;
            }
            
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .logo {
                width: 80px;
                height: 80px;
                background: linear-gradient(135deg, #4CAF50, #45a049);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                box-shadow: 0 10px 25px rgba(76, 175, 80, 0.3);
            }
            
            .logo i {
                font-size: 2.5rem;
                color: white;
            }
            
            h1 {
                color: #333;
                font-size: 2rem;
                margin-bottom: 10px;
                font-weight: 300;
            }
            
            .subtitle {
                color: #666;
                margin-bottom: 30px;
                font-size: 1.1rem;
            }
            
            .welcome-message {
                background: linear-gradient(135deg, #4CAF50, #45a049);
                color: white;
                padding: 20px;
                border-radius: 15px;
                margin-bottom: 25px;
                box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
            }
            
            .welcome-message h2 {
                font-size: 1.5rem;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }
            
            .welcome-message p {
                font-size: 1rem;
                opacity: 0.9;
            }
            
            .error-message {
                background: linear-gradient(135deg, #ff6b6b, #ee5a52);
                color: white;
                padding: 20px;
                border-radius: 15px;
                margin-bottom: 25px;
                box-shadow: 0 8px 20px rgba(238, 90, 82, 0.3);
            }
            
            .error-message h2 {
                font-size: 1.3rem;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }
            
            .role-badge {
                display: inline-block;
                padding: 5px 15px;
                border-radius: 20px;
                font-size: 0.9rem;
                font-weight: 500;
                margin-top: 10px;
                color: white;
            }
            
            .admin-badge {
                background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            }
            
            .user-badge {
                background: linear-gradient(135deg, #4ecdc4, #44a08d);
            }
            
            .vet-badge {
                background: linear-gradient(135deg, #667eea, #764ba2);
            }
            
            .buttons {
                display: flex;
                flex-direction: column;
                gap: 15px;
                margin-top: 25px;
            }
            
            .btn {
                padding: 15px 25px;
                border: none;
                border-radius: 12px;
                font-size: 1rem;
                font-weight: 500;
                text-decoration: none;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                transition: all 0.3s ease;
                cursor: pointer;
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #4CAF50, #45a049);
                color: white;
                box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(76, 175, 80, 0.4);
            }
            
            .btn-secondary {
                background: linear-gradient(135deg, #6c757d, #5a6268);
                color: white;
                box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
            }
            
            .btn-secondary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
            }
            
            .btn-danger {
                background: linear-gradient(135deg, #dc3545, #c82333);
                color: white;
                box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
            }
            
            .btn-danger:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
            }
            
            @media (max-width: 600px) {
                .container {
                    padding: 30px 20px;
                    margin: 10px;
                }
                
                h1 {
                    font-size: 1.5rem;
                }
                
                .buttons {
                    gap: 12px;
                }
                
                .btn {
                    padding: 12px 20px;
                    font-size: 0.95rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="logo">
                <i class="fas fa-heart"></i>
            </div>
            <h1>MoritosPet</h1>
            <p class="subtitle">Sistema de Gestión Veterinaria</p>';
   
    if ($resultado) {
        $nombre = htmlspecialchars($resultado['nombre']);
        $perfil = $resultado['rol'];
        
        if (empty($perfil)) {
            $perfil = 2;
        }
        
        echo '<div class="welcome-message">
                <h2><i class="fas fa-paw"></i> ¡Bienvenid@!</h2>';
        
        if ($perfil == 1) {
            echo '<p>Hola <strong>' . $nombre . '</strong></p>
                  <div class="role-badge admin-badge">
                      <i class="fas fa-crown"></i> Administrador
                  </div>';
        } elseif ($perfil == 2) {
            echo '<p>Hola <strong>' . $nombre . '</strong></p>
                  <div class="role-badge user-badge">
                      <i class="fas fa-user"></i> Recepcionista
                  </div>';
        } elseif ($perfil == 3) {
            echo '<p>Hola <strong>' . $nombre . '</strong></p>
                  <div class="role-badge vet-badge">
                      <i class="fas fa-user-md"></i> Veterinario
                  </div>';
        }
        
        echo '</div>
              <div class="buttons">';
        
        if ($perfil == 1) {
    echo '<a href="adminpant alla.html" class="btn btn-primary">
              <i class="fas fa-clipboard-list"></i>
              Acceder al Sistema
          </a>';
} elseif ($perfil == 2) {
    echo '<a href="consulta.php" class="btn btn-primary">
              <i class="fas fa-clipboard-list"></i>
              Acceder al Sistema
          </a>';
} elseif ($perfil == 3) {
    echo '<a href="veterinario.html" class="btn btn-primary">
              <i class="fas fa-clipboard-list"></i>
              Acceder al Sistema
          </a>';
} else {
    echo '<div class="error-message">
              <h2><i class="fas fa-exclamation-triangle"></i> Perfil Desconocido</h2>
              <p>No se pudo identificar tu tipo de usuario</p>
          </div>
          <div class="buttons">
              <a href="index.html" class="btn btn-danger">
                  <i class="fas fa-redo"></i>
                  Intentar de Nuevo
              </a>
          </div>';
}

echo '<a href="index.html" class="btn btn-secondary">
          <i class="fas fa-sign-out-alt"></i>
          Cerrar Sesión
      </a>';

        
    } else {
        echo '<div class="error-message">
                  <h2><i class="fas fa-times-circle"></i> Acceso Denegado</h2>
                  <p>Usuario o contraseña incorrectos</p>
              </div>
              <div class="buttons">
                  <a href="index.html" class="btn btn-danger">
                      <i class="fas fa-redo"></i>
                      Intentar de Nuevo
                  </a>
              </div>';
    }
    
    echo '    </div>
    </body>
    </html>';
}
?>