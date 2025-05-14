<h1>Resultados</h1>
<?php if(empty($flights)): ?>
  <div class="alert alert-warning">No hay vuelos para los criterios seleccionados.</div>
<?php else: ?>
<table class="table table-striped">
  <thead><tr><th>Origen</th><th>Destino</th><th>Fecha</th><th>Precio</th><th>Plazas</th><th></th></tr></thead>
  <tbody>
    <?php foreach($flights as $flight): ?>
      <tr>
        <td><?=htmlspecialchars($flight['origin'])?></td>
        <td><?=htmlspecialchars($flight['destination'])?></td>
        <td><?=htmlspecialchars($flight['flight_date'])?></td>
        <td><?=number_format($flight['price'],2)?> €</td>
        <td><?=htmlspecialchars($flight['seats'])?></td>
        <td>
          <?php if($flight['seats']>0): ?>
            <a class="btn btn-sm btn-primary" href="default.php?url=reservation/book/<?=$flight['id']?>">Reservar</a>
          <?php else: ?>
            <span class="badge bg-secondary">Sin plazas</span>
          <?php endif;?>
        </td>
      </tr>
    <?php endforeach;?>
  </tbody>
</table>
<?php endif; ?>
<a href="default.php" class="btn btn-link">← Nueva búsqueda</a>
