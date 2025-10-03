<form action="guardar_y_generar_receta.php" method="post">
  <input type="hidden" name="id_mascota" value="123"> <!-- reemplazar con el ID real -->
  
  <label>Diagnóstico:</label><br>
  <textarea name="diagnostico" required></textarea><br><br>
  
  <label>Tratamiento:</label><br>
  <textarea name="tratamiento" required></textarea><br><br>
  
  <label>Medicamentos:</label><br>
  <textarea name="medicamentos" required></textarea><br><br>
  
  <label>Observaciones:</label><br>
  <textarea name="observaciones"></textarea><br><br>

  <label>Costo de consulta:</label><br>
  <textarea name="costo de consulta"></textarea><br><br>

  <input type="submit" value="Guardar y Generar Receta">
</form>

