<?php
$mysqli = new mysqli("localhost", "root", "", "vete");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Eliminar cita si se presionó el botón "Eliminar"
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['eliminar'])) {
    $id = $_POST['id_cita'];
    $stmt = $mysqli->prepare("DELETE FROM citas WHERE id_citas = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Buscar citas
$busqueda = "";
if (isset($_GET['buscar']) && !empty(trim($_GET['buscar']))) {
    $busqueda = "%" . trim($_GET['buscar']) . "%";
    $stmt = $mysqli->prepare("SELECT * FROM citas WHERE mascota LIKE ? OR veterinario LIKE ? OR motivo LIKE ?");
    $stmt->bind_param("sss", $busqueda, $busqueda, $busqueda);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $resultado = $mysqli->query("SELECT * FROM citas");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Citas - Veterinaria</title>
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
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .back-btn {
            position: absolute;
            left: 30px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            transform: translateY(-50%) translateX(-3px);
        }

        .back-btn::before {
            content: "←";
            font-size: 18px;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .subtitle {
            font-size: 1.1em;
            opacity: 0.9;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .subtitle::before {
            content: "🐾";
            font-size: 1.2em;
        }

        .content {
            padding: 40px;
        }

        .search-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            border-left: 5px solid #4caf50;
        }

        .buscar-form {
            display: flex;
            justify-content: center;
            gap: 15px;
            align-items: center;
        }

        .search-input {
            padding: 12px 20px;
            width: 400px;
            border: 2px solid #e0e0e0;
            border-radius: 25px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: #4caf50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        .search-btn {
            background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .search-btn::before {
            content: "🔍";
        }

        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);
            color: white;
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 14px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        tr:hover {
            background: #f8f9fa;
            transition: background 0.2s ease;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-editar {
            background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
            color: white;
        }

        .btn-editar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
        }

        .btn-editar::before {
            content: "✏️";
        }

        .btn-eliminar {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: white;
        }

        .btn-eliminar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(244, 67, 54, 0.3);
        }

        .btn-eliminar::before {
            content: "🗑️";
        }

        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
            font-size: 18px;
        }

        .no-results::before {
            content: "🐕";
            display: block;
            font-size: 3em;
            margin-bottom: 15px;
        }

        .id-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 12px;
        }

        .date-time {
            color: #2e7d32;
            font-weight: 600;
        }

        .mascota-name {
            color: #ff7043;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 10px;
            }
            
            .header {
                padding: 20px;
            }
            
            .back-btn {
                position: static;
                transform: none;
                margin-bottom: 15px;
            }
            
            .content {
                padding: 20px;
            }
            
            .search-input {
                width: 100%;
                max-width: 300px;
            }
            
            .buscar-form {
                flex-direction: column;
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 10px 8px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="veterinario.html" class="back-btn">Regresar al Menú</a>
            <h1>Lista de Citas</h1>
            <p class="subtitle">Gestión de citas veterinarias</p>
        </div>

        <div class="content">
            <div class="search-section">
                <form class="buscar-form" method="GET">
                    <input type="text" name="buscar" class="search-input" 
                           placeholder="Buscar por mascota, motivo o veterinario" 
                           value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
                    <button type="submit" class="search-btn">Buscar</button>
                </form>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Motivo</th>
                            <th>Mascota</th>
                            <th>Veterinario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado->num_rows > 0): ?>
                            <?php while ($fila = $resultado->fetch_assoc()) { ?>
                            <tr>
                                <td><span class="id-badge">#<?php echo $fila['id_citas']; ?></span></td>
                                <td class="date-time"><?php echo date('d/m/Y', strtotime($fila['fecha'])); ?></td>
                                <td class="date-time"><?php echo date('H:i', strtotime($fila['hora'])); ?></td>
                                <td><?php echo htmlspecialchars($fila['motivo']); ?></td>
                                <td class="mascota-name"><?php echo htmlspecialchars($fila['mascota']); ?></td>
                                <td><?php echo htmlspecialchars($fila['veterinario']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <form method="GET" action="modificarcita.php" style="display: inline;">
                                            <input type="hidden" name="id" value="<?php echo $fila['id_citas']; ?>">
                                            <button type="submit" class="btn btn-editar">Editar</button>
                                        </form>
                                        <form method="POST" style="display: inline;" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta cita?');">
                                            <input type="hidden" name="id_cita" value="<?php echo $fila['id_citas']; ?>">
                                            <button type="submit" name="eliminar" class="btn btn-eliminar">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="no-results">
                                    No se encontraron citas registradas
                                    <br><small>Intenta con otros términos de búsqueda</small>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

