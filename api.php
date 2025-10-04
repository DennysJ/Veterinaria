<?php
require_once 'database.php';

header('Content-Type: application/json');

$request_uri = $_SERVER['REQUEST_URI'];

// Enrutamiento
if (preg_match('|^/api/citas/?$|', $request_uri)) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = "
                SELECT 
                    citas.id_citas,
                    citas.fecha,
                    citas.hora,
                    citas.motivo,
                    mascotas.nombre AS mascota,
                    usuarios.nombre AS veterinario
                FROM citas
                JOIN mascotas ON citas.id_mascota = mascotas.id_mascota
                JOIN usuarios ON citas.id_usuario = usuarios.id_usuario
                WHERE usuarios.rol = 3
                ORDER BY citas.fecha, citas.hora
            ";

            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'data' => $citas]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener las citas: ' . $e->getMessage()]);
        }
    }
} else if (preg_match('|^/api/duenos/?$|', $request_uri)) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = "SELECT id_dueno, nombre, telefono, direccion FROM duenos";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $duenos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'data' => $duenos]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener los dueños: ' . $e->getMessage()]);
        }
    }
} else if (preg_match('|^/api/mascotas/?$|', $request_uri)) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = "
                SELECT
                    mascotas.id_mascota,
                    mascotas.nombre,
                    mascotas.raza,
                    mascotas.especie,
                    mascotas.edad,
                    mascotas.genero,
                    mascotas.peso,
                    mascotas.estado,
                    duenos.nombre AS nombre_dueno
                FROM mascotas
                INNER JOIN duenos ON mascotas.id_dueno = duenos.id_dueno
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $mascotas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'data' => $mascotas]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener las mascotas: ' . $e->getMessage()]);
        }
    }
} else if (preg_match('|^/api/veterinarios/?$|', $request_uri)) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = "SELECT id_usuario, nombre, usuario FROM usuarios WHERE rol = 3";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $veterinarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'data' => $veterinarios]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener los veterinarios: ' . $e->getMessage()]);
        }
    }
} else if (preg_match('|^/api/consultas/?$|', $request_uri)) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = "SELECT id_citas, fecha, hora, motivo, mascota, veterinario FROM citas";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'data' => $consultas]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener las consultas: ' . $e->getMessage()]);
        }
    }
} else if (preg_match('|^/api/recetas/?$|', $request_uri)) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        try {
            $query = "SELECT id_receta, mascota, diagnostico, tratamiento, medicamentos, observaciones, fecha, costo_consulta FROM recetas";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $recetas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['status' => 'success', 'data' => $recetas]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Error al obtener las recetas: ' . $e->getMessage()]);
        }
    }
} else {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Endpoint no encontrado']);
}
?>
