<?php
require_once 'database.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['eliminar_id'])) {
    $id_eliminar = intval($_POST['eliminar_id']);
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_eliminar]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$sql = "SELECT id_usuario, rol, nombre, usuario,
        CASE rol
            WHEN '1' THEN 'Administrador'
            WHEN '2' THEN 'Recepcionista'
            WHEN '3' THEN 'Veterinario'
        END AS rol_nombre
        FROM usuarios";
$stmt = $pdo->query($sql);
if (!$stmt) {
    die("Error en la consulta");
}
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Veterinaria VetCare</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
            padding: 30px;
            text-align: center;
            color: white;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="70" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="70" cy="80" r="2.5" fill="rgba(255,255,255,0.1)"/></svg>');
            pointer-events: none;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header .subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .header .icon {
            font-size: 3rem;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 30px;
        }

        .stats-bar {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 5px solid #4caf50;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #2e7d32;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, #2e7d32 0%, #388e3c 100%);
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px 12px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }

        tr:hover {
            background-color: #f5f5f5;
            transform: scale(1.01);
            transition: all 0.3s ease;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .rol-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rol-administrador {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        .rol-recepcionista {
            background: #e3f2fd;
            color: #1565c0;
            border: 1px solid #bbdefb;
        }

        .rol-veterinario {
            background: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .btn {
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin: 0 3px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-edit {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
            border: none;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 152, 0, 0.4);
        }

        .btn-delete {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: white;
            border: none;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.4);
        }

        .btn-back {
            background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(46, 125, 50, 0.3);
        }

        .actions-column {
            white-space: nowrap;
        }

        .user-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4caf50 0%, #81c784 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 10px;
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }
            
            .header {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
            
            .content {
                padding: 20px;
            }
            
            .stats-bar {
                flex-direction: column;
                gap: 15px;
            }
            
            table {
                font-size: 0.9rem;
            }
            
            th, td {
                padding: 10px 8px;
            }
            
            .btn {
                padding: 6px 10px;
                font-size: 0.8rem;
            }
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <h1>Gestión de Usuarios</h1>
            <p class="subtitle">Sistema de Administración - Veterinaria VetCare</p>
        </div>

        <div class="content">
            <?php
            $total_usuarios = count($usuarios);
            $administradores = count(array_filter($usuarios, function($u) { return $u['rol'] == '1'; }));
            $recepcionistas = count(array_filter($usuarios, function($u) { return $u['rol'] == '2'; }));
            $veterinarios = count(array_filter($usuarios, function($u) { return $u['rol'] == '3'; }));
            ?>

            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $total_usuarios; ?></div>
                    <div class="stat-label">Total Usuarios</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $administradores; ?></div>
                    <div class="stat-label">Administradores</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $recepcionistas; ?></div>
                    <div class="stat-label">Recepcionistas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $veterinarios; ?></div>
                    <div class="stat-label">Veterinarios</div>
                </div>
            </div>

            <div class="table-container">
                <?php if($total_usuarios > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Usuario</th>
                            <th><i class="fas fa-id-badge"></i> Usuario Sistema</th>
                            <th><i class="fas fa-user-tag"></i> Rol</th>
                            <th><i class="fas fa-cogs"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($usuarios as $fila): ?>
                            <tr>
                                <td><strong><?php echo $fila['id_usuario']; ?></strong></td>
                                <td>
                                    <div class="user-icon">
                                        <?php echo strtoupper(substr($fila['nombre'], 0, 1)); ?>
                                    </div>
                                    <?php echo htmlspecialchars($fila['nombre']); ?>
                                </td>
                                <td><code><?php echo htmlspecialchars($fila['usuario']); ?></code></td>
                                <td>
                                    <span class="rol-badge rol-<?php echo strtolower(str_replace(' ', '', $fila['rol_nombre'])); ?>">
                                        <?php 
                                        $iconos = [
                                            'Administrador' => 'fas fa-crown',
                                            'Recepcionista' => 'fas fa-desktop',
                                            'Veterinario' => 'fas fa-stethoscope'
                                        ];
                                        echo '<i class="' . $iconos[$fila['rol_nombre']] . '"></i> ' . $fila['rol_nombre'];
                                        ?>
                                    </span>
                                </td>
                                <td class="actions-column">
                                    <a class="btn btn-edit" href="editarusuario.php?id=<?php echo $fila['id_usuario']; ?>">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar al usuario <?php echo htmlspecialchars($fila['nombre']); ?>?\n\nEsta acción no se puede deshacer.');">
    <input type="hidden" name="eliminar_id" value="<?php echo $fila['id_usuario']; ?>">
    <button type="submit" class="btn btn-delete">
        <i class="fas fa-trash"></i> Eliminar
    </button>
</form>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <h3>No hay usuarios registrados</h3>
                    <p>Aún no se han registrado usuarios en el sistema.</p>
                </div>
                <?php endif; ?>
            </div>

            <a href="adminpantalla.html" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver al Menú Principal
            </a>
             <a href="agregar.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Agregar
            </a>
        </div>
    </div>
</body>
</html>
