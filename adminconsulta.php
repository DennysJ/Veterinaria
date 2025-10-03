
<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);

// Verificar conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$sql = "SELECT mascotas.foto, mascotas.nombre, mascotas.raza, mascotas.especie, mascotas.edad, mascotas.genero, mascotas.peso, mascotas.estado, duenos.nombre AS nombre_dueno 
       FROM mascotas INNER JOIN duenos ON mascotas.id_dueno = duenos.id_dueno;";

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Mascotas - MoritosPet</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 30px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            border-radius: 15px;
        }

        .logo-placeholder {
            font-size: 20px;
            font-weight: bold;
            color: white;
        }

        .title-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .title-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #42b883;
            color: white;
        }

        .btn-primary:hover {
            background: #369970;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .content {
            padding: 40px;
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(240, 147, 251, 0.3);
        }

        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .table-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .table-header h2 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .table-header p {
            opacity: 0.9;
            font-size: 0.9rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            padding: 15px 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px 12px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .pet-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .pet-photo:hover {
            transform: scale(1.1);
            border-color: #667eea;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .pet-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .health-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .health-bueno {
            background: #d4edda;
            color: #155724;
        }

        .health-regular {
            background: #fff3cd;
            color: #856404;
        }

        .health-malo {
            background: #f8d7da;
            color: #721c24;
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .no-data img {
            width: 100px;
            height: 100px;
            opacity: 0.5;
            margin-bottom: 20px;
        }

        .footer {
            background: #f8f9fa;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }

        .footer p {
            color: #6c757d;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }

            .header {
                padding: 20px;
                text-align: center;
            }

            .title-section h1 {
                font-size: 2rem;
            }

            .content {
                padding: 20px;
            }

            .stats-section {
                grid-template-columns: 1fr;
            }

            .table-container {
                overflow-x: auto;
            }

            table {
                min-width: 800px;
            }

            th, td {
                padding: 10px 8px;
                font-size: 0.8rem;
            }

            .pet-photo {
                width: 60px;
                height: 60px;
            }
        }

        .search-section {
            margin-bottom: 30px;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 12px 20px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="header">
            <div class="logo-section">
                <div class="logo">
                
                    <div class="logo-placeholder">🐾</div>
                </div>
                <div class="title-section">
                    <h1>MoritosPet</h1>
                    <p>Hospital Veterinario  </p>
                </div>
            </div>
            <div class="header-actions">
           <a href="agdue.php" class="btn btn-primary">👤Agregar Dueños</a>
            <a href="duenos.php" class="btn btn-primary">📋Listado de Dueños</a>
            <a href="borrar.php" class="btn btn-primary">➖✍🏻Eliminar y Editar Mascota</a>    
            <a href="alta.php" class="btn btn-primary">➕ Agregar Mascota</a>
                <a href="adminpant alla.html" class="btn btn-secondary">🚪 Volver al menú</a>
            </div>
        </div>

        <div class="content">
            
            <div class="stats-section">
                <?php
               
                $count_result = $mysqli->query("SELECT COUNT(*) as total FROM mascotas");
                $total_mascotas = $count_result->fetch_assoc()['total'];
                
                
                $healthy_result = $mysqli->query("SELECT COUNT(*) as total FROM mascotas WHERE estado = 'Activo'");
                $mascotas_saludables = $healthy_result->fetch_assoc()['total'];
                ?>
                
            </div>

            
            <div class="search-section">
                <input type="text" class="search-input" placeholder="🔍 Buscar mascota por nombre, raza o dueño..." id="searchInput">
                
            </div>

            
            <div class="table-container">
                <div class="table-header">
                    <h2>📋 Registro de Mascotas</h2>
                    <p>Mascotas en observación</p>
                </div>

               
               <?php
                if($result = $mysqli->query($sql)){
                    if($result->num_rows > 0) {
                        echo "<table id='petsTable'>";
                        echo "<thead>
                        <tr>
                            <th>📸 Foto</th>
                            <th>🐕 Nombre</th>
                           <th>🐶🐱Especie</th>
                            <th>🧬 Raza</th>
                            <th>📅 Edad</th>
                            <th>⚧ Género</th>
                            <th>⚖️ Peso</th>
                            <th>👤 Dueño</th>
                            <th>🏥 Estado</th>
                        </tr>
                        </thead>";
                        echo "<tbody>";
                        
                        while($row = $result->fetch_assoc()){
                            echo "<tr>";
                            $foto = !empty($row["foto"]) ? htmlspecialchars($row["foto"]) : "https://via.placeholder.com/80x80/cccccc/666666?text=Sin+Foto";
echo "<td><img src='$foto' alt='Foto de " . htmlspecialchars($row["nombre"]) . "' class='pet-photo'></td>";

                            echo "<td class='pet-name'>" . htmlspecialchars($row["nombre"]) . "</td>";
                             echo "<td>" . htmlspecialchars($row["especie"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["raza"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["edad"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["genero"]) . "</td>";
                            echo "<td>" . htmlspecialchars($row["peso"]) . " kg</td>";
                            
                            
                            
                            
                            echo "<td>" . htmlspecialchars($row["nombre_dueno"]) . "</td>";

                            echo "<td>" . htmlspecialchars($row["estado"]) . "</td>";

                            echo "</tr>";
                        }
                        echo "</tbody></table>";
                        $result->free();
                    } else {
                        echo "<div class='no-data'>";
                        echo "<div style='font-size: 4rem; margin-bottom: 20px;'>🐾</div>";
                        echo "<h3>No hay mascotas registradas</h3>";
                        echo "<p>Comienza agregando la primera mascota al sistema</p>";
                        echo "<a href='#' class='btn btn-primary' style='margin-top: 20px; display: inline-block;'>Agregar Primera Mascota</a>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='no-data'>";
                    echo "<div style='font-size: 4rem; margin-bottom: 20px;'>⚠️</div>";
                    echo "<h3>Error en la consulta</h3>";
                    echo "<p>No se pudieron cargar los datos de las mascotas</p>";
                    echo "</div>";
                }
                
                $mysqli->close();
                ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2025 MoritosPet - Centro Veterinario Integral</p>
            <p>Cuidando a tus mascotas con amor y profesionalismo 🐾</p>
        </div>
    </div>

    <script>
        
        document.getElementById('searchInput').addEventListener('keyup', function() {
            filterTable();
        });

        document.getElementById('filterHealth').addEventListener('change', function() {
            filterTable();
        });

        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('petsTable');
            
            if (!table) return;
            
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                const nombre = cells[1]?.textContent.toLowerCase() || '';
                const raza = cells[3]?.textContent.toLowerCase() || '';
                const estado = cells[7]?.textContent.toLowerCase() || '';
                const dueno = cells[8]?.textContent.toLowerCase() || '';

                const matchesSearch = nombre.includes(searchTerm) || 
                                    raza.includes(searchTerm) || 
                                    dueno.includes(searchTerm);
                
                               
               if (matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.animation = 'slideInUp 0.6s ease forwards';
            }, index * 200);
        });

        
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>