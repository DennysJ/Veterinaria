<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);

// Si se hizo una búsqueda
$busqueda = "";
if (isset($_GET['buscar'])) {
    $busqueda = $mysqli->real_escape_string($_GET['buscar']);
    $sql = "SELECT id_recetas, mascota, diagnostico, tratamiento, medicamentos, observaciones, fecha, costo_consulta
            FROM recetas
            WHERE mascota LIKE '%$busqueda%'
               OR diagnostico LIKE '%$busqueda%'
               OR tratamiento LIKE '%$busqueda%'
               OR medicamentos LIKE '%$busqueda%'
               OR observaciones LIKE '%$busqueda%'";
} else {
    $sql = "SELECT id_recetas, mascota, diagnostico, tratamiento, medicamentos, observaciones, fecha, costo_consulta
            FROM recetas";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultas - MoritosPet</title>
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
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .header .subtitle {
            font-size: 1.2em;
            opacity: 0.9;
        }

        .search-section {
            padding: 30px;
            background: #f8f9ff;
            border-bottom: 1px solid #e1e8ff;
        }

        .search-form {
            display: flex;
            gap: 15px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            max-width: 400px;
            padding: 15px 20px;
            border: 2px solid #e1e8ff;
            border-radius: 50px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #4facfe;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 172, 254, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .table-container {
            padding: 30px;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .data-table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .data-table th {
            padding: 20px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: top;
        }

        .data-table tbody tr {
            transition: all 0.3s ease;
        }

        .data-table tbody tr:hover {
            background: #f8f9ff;
            transform: scale(1.01);
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .action-btn {
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin: 2px;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #28a745;
            color: white;
        }

        .btn-edit:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .no-results i {
            font-size: 4em;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .footer {
            padding: 30px;
            text-align: center;
            background: #f8f9ff;
            border-top: 1px solid #e1e8ff;
        }

        .back-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .cost {
            font-weight: 600;
            color: #28a745;
        }

        .date {
            font-size: 0.9em;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .search-form {
                flex-direction: column;
            }
            
            .search-input {
                max-width: 100%;
            }
            
            .data-table {
                font-size: 14px;
            }
            
            .data-table th,
            .data-table td {
                padding: 10px 8px;
            }
            
            .header h1 {
                font-size: 2em;
            }
        }

        .pet-icon {
            display: inline-block;
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            color: white;
            margin-right: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-stethoscope"></i> MoritosPet</h1>
            <div class="subtitle">Sistema de Gestión Veterinaria - Consultas de Recetas</div>
        </div>

        <div class="search-section">
            <form method="get" class="search-form">
                <input type="text" name="buscar" class="search-input" 
                       placeholder="🔍 Buscar por mascota, diagnóstico, tratamiento..." 
                       value="<?php echo htmlspecialchars($busqueda); ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Buscar
                </button>
                <a href="consultaci.php" class="btn btn-secondary">
                    <i class="fas fa-eraser"></i> Limpiar
                </a>
            </form>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th><i class="fas fa-paw"></i> Mascota</th>
                        <th><i class="fas fa-diagnoses"></i> Diagnóstico</th>
                        <th><i class="fas fa-notes-medical"></i> Tratamiento</th>
                        <th><i class="fas fa-pills"></i> Medicamentos</th>
                        <th><i class="fas fa-clipboard"></i> Observaciones</th>
                        <th><i class="fas fa-calendar"></i> Fecha</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result = $mysqli->query($sql)) {
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><span class='pet-icon'><i class='fas fa-paw'></i></span>" . htmlspecialchars($row["mascota"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["diagnostico"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["tratamiento"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["medicamentos"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["observaciones"]) . "</td>";
                                echo "<td class='date'>" . date('d/m/Y', strtotime($row["fecha"])) . "</td>";
                                
                                echo "<td>";
                                echo "<a href='editarcita.php?id=" . $row["id_recetas"] . "' class='action-btn btn-edit' title='Editar receta'>";
                                echo "<i class='fas fa-edit'></i> Editar";
                                echo "</a>";
                                echo "<a href='borrarcita.php?id=" . $row["id_recetas"] . "' class='action-btn btn-delete' title='Eliminar receta' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta receta?\")'>";
                                echo "<i class='fas fa-trash'></i> Eliminar";
                                echo "</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>";
                            echo "<div class='no-results'>";
                            echo "<i class='fas fa-search'></i>";
                            echo "<h3>No se encontraron resultados</h3>";
                            echo "<p>Intenta con otros términos de búsqueda</p>";
                            echo "</div>";
                            echo "</td></tr>";
                        }
                        $result->free();
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <a href='veterinario.html' class="back-btn">
                <i class="fas fa-arrow-left"></i> Regresar al Menú Principal
            </a>
        </div>
    </div>

    <script>
        // Animación suave al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.data-table tbody tr');
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
        function confirmarEliminacion(mascota) {
            return confirm(`¿Estás seguro de que deseas eliminar la receta de ${mascota}?\n\nEsta acción no se puede deshacer.`);
        }
    </script>
</body>
</html>

