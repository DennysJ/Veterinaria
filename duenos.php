<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}
$sql = "SELECT id_dueno, nombre, telefono, direccion FROM duenos";
if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    $stmt = $mysqli->prepare("DELETE FROM duenos WHERE id_dueno = ?");
    $stmt->bind_param("i", $idEliminar);
    $stmt->execute();
    $stmt->close();

    // Redirigir para evitar reenvío de formulario y mantener URL limpia
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dueños Registrados - Veterinaria</title>
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
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="rgba(255,255,255,0.1)"><polygon points="0,0 1000,0 1000,60 0,100"/></svg>');
            background-size: cover;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header .subtitle {
            font-size: 1.2em;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .header i {
            font-size: 3em;
            margin-bottom: 15px;
            opacity: 0.8;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 30px;
        }

        .stats {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .stat-card {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            min-width: 150px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-card i {
            font-size: 2em;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        th {
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 1.1em;
        }

        tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8eaff 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        td {
            padding: 20px 15px;
            vertical-align: middle;
        }

        .name-cell {
            font-weight: 600;
            color: #333;
            font-size: 1.1em;
        }

        .contact-info {
            color: #666;
            font-size: 0.95em;
        }

        .contact-info i {
            margin-right: 8px;
            color: #4CAF50;
        }

        .action-btn {
            display: inline-block;
            padding: 8px 15px;
            margin: 2px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }

        .edit-btn {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.4);
        }

        .delete-btn {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
        }

        .delete-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }

        .back-btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 1.1em;
        }

        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .back-btn i {
            margin-right: 10px;
        }

        .no-records {
            text-align: center;
            padding: 50px;
            color: #666;
            font-size: 1.2em;
        }

        .no-records i {
            font-size: 4em;
            color: #ddd;
            margin-bottom: 20px;
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
                font-size: 2em;
            }
            
            .content {
                padding: 20px;
            }
            
            table {
                font-size: 0.9em;
            }
            
            th, td {
                padding: 12px 8px;
            }
            
            .action-btn {
                padding: 6px 12px;
                font-size: 0.8em;
            }
        }

        .loading {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-paw"></i>
            <h1>Moitos Pet</h1>
            <p class="subtitle">Registro de Dueños de Mascotas</p>
        </div>
        
        <div class="content">
            <?php
            if ($result = $mysqli->query($sql)) {
                $total_duenos = $result->num_rows;
                ?>
                <div class="stats">
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <div class="number"><?php echo $total_duenos; ?></div>
                        <div>Dueños Registrados</div>
                    </div>
                </div>
                
                <?php if ($total_duenos > 0) { ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user"></i> Nombre</th>
                                    <th><i class="fas fa-phone"></i> Teléfono</th>
                                    <th><i class="fas fa-map-marker-alt"></i> Dirección</th>
                                    <th><i class="fas fa-cogs"></i> Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td class="name-cell">
                                            <i class="fas fa-user-circle" style="color: #4CAF50; margin-right: 10px;"></i>
                                            <?php echo htmlspecialchars($row["nombre"]); ?>
                                        </td>
                                        <td class="contact-info">
                                            <i class="fas fa-phone"></i>
                                            <?php echo htmlspecialchars($row["telefono"]); ?>
                                        </td>
                                        <td class="contact-info">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($row["direccion"]); ?>
                                        </td>
                                        <td>
                                            <a href="editardue.php?id=<?php echo $row["id_dueno"]; ?>" class="action-btn edit-btn">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                           <a href="?eliminar=<?php echo $row["id_dueno"]; ?>"
   class="action-btn delete-btn"
   onclick="return confirm('¿Estás seguro de que deseas eliminar a <?php echo htmlspecialchars($row["nombre"]); ?>?')">
   <i class="fas fa-trash"></i> Eliminar
</a>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="no-records">
                        <i class="fas fa-inbox"></i>
                        <h3>No hay dueños registrados</h3>
                        <p>Aún no se han registrado dueños de mascotas en el sistema.</p>
                    </div>
                <?php } ?>
                
                <?php $result->free(); ?>
            <?php } ?>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="consulta.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i>
                    Volver al Inicio
                </a>
            </div>
        </div>
    </div>

    <script>
        // Animación de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Confirmación mejorada para eliminar
        function confirmarEliminacion(nombre, url) {
            if (confirm(`¿Estás seguro de que deseas eliminar a ${nombre}?\n\nEsta acción no se puede deshacer.`)) {
                window.location.href = url;
            }
            return false;
        }
    </script>
</body>
</html>
<?php $mysqli->close(); ?>
