<?php
$usuario = "root";
$clave = "";
$bd = "vete";
$mysqli = new mysqli("localhost", $usuario, $clave, $bd);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $mysqli->prepare("DELETE FROM mascotas WHERE id_mascota = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT id_mascota, foto, nombre FROM mascotas";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mascotas Registradas - Veterinaria VeteCare</title>
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
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .pets-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 40px;
        }

        .pet-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .pet-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .pet-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 3px solid #4facfe;
        }

        .pet-info {
            padding: 20px;
        }

        .pet-name {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .delete-btn {
            background: linear-gradient(135deg, #ff6b6b, #ee5a24);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #ee5a24, #ff6b6b);
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        }
        .edit-btn {
    background: linear-gradient(135deg, #38ada9, #78e08f);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: bold;
    font-size: 0.9rem;
}

.edit-btn:hover {
    background: linear-gradient(135deg, #78e08f, #38ada9);
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(56, 173, 169, 0.4);
}


        .back-btn {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: bold;
            font-size: 1rem;
            margin: 20px auto;
            display: block;
            width: fit-content;
        }

        .back-btn:hover {
            background: linear-gradient(135deg, #00f2fe, #4facfe);
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        }

        .no-pets {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-pets i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
        }

        .stats {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            padding: 20px;
            margin: 20px 40px;
            border-radius: 15px;
            text-align: center;
        }

        .stats h3 {
            color: #333;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .pets-grid {
                grid-template-columns: 1fr;
                padding: 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .stats {
                margin: 20px;
            }
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .loading i {
            font-size: 2rem;
            animation: spin 2s linear infinite;
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
            <h1><i class="fas fa-paw"></i> MoritosPet</h1>
            <p>Mascotas Registradas en Nuestro Sistema</p>
        </div>

        <?php
        $count = 0;
        if ($result = $mysqli->query($sql)) {
            $pets = [];
            while ($row = $result->fetch_assoc()) {
                $pets[] = $row;
                $count++;
            }
            $result->free();
        ?>

        <div class="stats">
            <h3><i class="fas fa-chart-bar"></i> Total de Mascotas Registradas: <?php echo $count; ?></h3>
        </div>

        <?php if ($count > 0): ?>
            <div class="pets-grid">
                <?php foreach ($pets as $pet): ?>
                    <div class="pet-card">
                        <img src="<?php echo htmlspecialchars($pet['foto']); ?>" 
                             alt="Foto de <?php echo htmlspecialchars($pet['nombre']); ?>" 
                             class="pet-image"
                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0xMDAgMTIwQzExMC40NTcgMTIwIDExOSAxMTEuNDU3IDExOSAxMDFDMTE5IDkwLjU0MyAxMTAuNDU3IDgyIDEwMCA4MkM4OS41NDMgODIgODEgOTAuNTQzIDgxIDEwMUM4MSAxMTEuNDU3IDg5LjU0MyAxMjAgMTAwIDEyMFoiIGZpbGw9IiM5Q0E0QUYiLz4KPHBhdGggZD0iTTEzNSA5NUMxNDAuNTIzIDk1IDE0NSA5MC41MjMgMTQ1IDg1QzE0NSA3OS40NzcgMTQwLjUyMyA3NSAxMzUgNzVDMTI5LjQ3NyA3NSAxMjUgNzkuNDc3IDEyNSA4NUMxMjUgOTAuNTIzIDEyOS40NzcgOTUgMTM1IDk1WiIgZmlsbD0iIzlDQTRBRiIvPgo8cGF0aCBkPSJNNjUgOTVDNzAuNTIzIDk1IDc1IDkwLjUyMyA3NSA4NUM3NSA3OS40NzcgNzAuNTIzIDc1IDY1IDc1QzU5LjQ3NyA3NSA1NSA3OS40NzcgNTUgODVDNTUgOTAuNTIzIDU5LjQ3NyA5NSA2NSA5NVoiIGZpbGw9IiM5Q0E0QUYiLz4KPHN2Zz4K';">
                        <div class="pet-info">
                            <div class="pet-name">
                                <i class="fas fa-heart" style="color: #ff6b6b;"></i>
                                <?php echo htmlspecialchars($pet['nombre']); ?>
                            </div>
                            <div class="pet-actions">
    <a href="editarmascota.php?id=<?php echo $pet['id_mascota']; ?>" 
       class="edit-btn">
       <i class="fas fa-edit"></i>
       Editar
    </a>
    
    <form method="post" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar a <?php echo htmlspecialchars($pet['nombre']); ?>? 🐾');">
    <input type="hidden" name="delete_id" value="<?php echo $pet['id_mascota']; ?>">
    <button type="submit" class="delete-btn">
        <i class="fas fa-trash-alt"></i> Eliminar
    </button>
</form>

</div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-pets">
                <i class="fas fa-paw"></i>
                <h3>No hay mascotas registradas</h3>
                <p>Aún no se han registrado mascotas en el sistema.</p>
            </div>
        <?php endif; ?>

        <?php } else { ?>
            <div class="loading">
                <i class="fas fa-spinner"></i>
                <p>Cargando mascotas...</p>
            </div>
        <?php } ?>

        <div class="footer">
            <a href="consulta.php" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Regresar al Panel Principal
            </a>
        </div>
    </div>

    <script>
        // Animación de entrada para las tarjetas
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.pet-card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    card.style.transition = 'all 0.6s ease';
                    
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 100);
            });
        });

        // Mejorar la confirmación de eliminación
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const petName = this.closest('.pet-card').querySelector('.pet-name').textContent.trim();
                if (!confirm(`¿Estás completamente seguro de eliminar a ${petName}?\n\n⚠️ Esta acción no se puede deshacer.`)) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
