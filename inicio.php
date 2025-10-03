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
    $stmt->bindParam(':clave', $clave);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    echo '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <style>
            body, html {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                font-family: Arial, sans-serif;
                color: white;
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                background: url("fone.jpeg") no-repeat center center fixed;
                background-size: cover;
            }

            .message {
                font-size: 18px;
                margin-bottom: 20px;
                text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            }

            a {
                display: inline-block;
                margin: 10px;
                padding: 10px 20px;
                text-decoration: none;
                background-color: rgba(0, 123, 255, 0.8);
                color: white;
                border-radius: 5px;
                font-weight: bold;
                transition: background-color 0.3s ease;
            }
            
            a:hover {
                background-color: rgba(0, 86, 179, 0.8);
            }
        </style>
    </head>
    <body>';
    
        if ($resultado) {
            $nombre = htmlspecialchars($resultado['nombre']); 
            $rol = $resultado['rol'];
        
    
            if (empty($rol)) {
                $rol = 2;
            }
        
            if ($rol == administrador) {
                echo '<p class="message">Bienvenid@ administrador ' . $nombre . '.</p>';
                echo '<br><center><img src="admin.gif" height="200px" width="200px"></center>';
                echo '<a href="admin.html">Continuar</a>'; 
    

                echo '<a href="inndex.html">Cerrar sesión</a>';
            } elseif ($rol == recepcionista) {
                echo '<p class="message">Bienvenid@ ' . $nombre . '.</p>';
                echo '<br><center><img src="cliente.gif" height="200px" width="200px"></center>';
                echo '<a href="tienda.php">Ir de compras</a>'; 
                echo '<a href="inndex.html">Cerrar sesión</a>';
            } elseif ($rol == veterinario) {
                echo '<p class="message">Bienvenid@ ' . $nombre . '.</p>';
                echo '<br><center><img src="cliente.gif" height="200px" width="200px"></center>';
                echo '<a href="tienda.php">Ir de compras</a>'; 
                echo '<a href="inndex.html">Cerrar sesión</a>';
            } else {
                echo '<p class="message">Perfil desconocido.</p>';
                echo '<br><center><img src="incorrecto.gif" height="200px" width="200px"></center>';
                echo '<a href="inndex.html">Volver a intentar</a>'; 
            }
        } else {
            echo '<p class="message">Usuario o contraseña incorrectos.</p>';
            echo '<br><center><img src="incorrecto.gif" height="200px" width="200px"></center>';
            echo '<a href="inndex.html">Volver a intentar</a>';
        }
        

    echo '</body>
    </html>';
}
?>