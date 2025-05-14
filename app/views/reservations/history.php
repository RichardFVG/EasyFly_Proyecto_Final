<h1>Mis reservas</h1>
<?php if(empty($reservations)): ?>
  <div class="alert alert-info">Aún no has reservado vuelos.</div>
<?php else: ?>
<table class="table table-striped">
  <thead><tr><th>Origen</th><th>Destino</th><th>Fecha del vuelo</th><th>Precio</th><th>Fecha reserva</th></tr></thead>
  <tbody>
    <?php foreach($reservations as $r): ?>
      <tr>
        <td><?=$r['origin']?></td>
        <td><?=$r['destination']?></td>
        <td><?=$r['flight_date']?></td>
        <td><?=number_format($r['price'],2)?> €</td>
        <td><?=$r['reserved_at']?></td>
      </tr>
    <?php endforeach;?>
  </tbody>
</table>
<?php endif; ?>
