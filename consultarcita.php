<?php
$conexion = new mysqli("localhost", "root", "", "vete");

$query = "
SELECT 
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

$resultado = $conexion->query($query);

echo "<h2>Listado de Citas</h2>";
echo "<table border='1'>
<tr><th>Fecha</th><th>Hora</th><th>Motivo</th><th>Mascota</th><th>Veterinario</th></tr>";

while ($fila = $resultado->fetch_assoc()) {
    echo "<tr>
        <td>{$fila['fecha']}</td>
        <td>{$fila['hora']}</td>
        <td>{$fila['motivo']}</td>
        <td>{$fila['mascota']}</td>
        <td>{$fila['veterinario']}</td>
    </tr>";
}
echo "</table>";
?>