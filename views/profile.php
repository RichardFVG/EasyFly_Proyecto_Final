
<?php include __DIR__.'/partials/header.php'; ?>
<h2>Mis Reservas</h2>
<table class="table">
<thead><tr><th>Destino</th><th>Código</th><th>Fecha</th></tr></thead>
<tbody>
<?php foreach($reservations as $r): ?>
<tr>
<td><?= htmlspecialchars($r['pais_destino']) ?></td>
<td><?= htmlspecialchars($r['codigo_reserva']) ?></td>
<td><?= htmlspecialchars($r['fecha_reserva']) ?></td>
</tr>
<?php endforeach; ?>
<?php if(empty($reservations)): ?><tr><td colspan="3" class="text-center">Sin reservas</td></tr><?php endif; ?>
</tbody>
</table>
<?php include __DIR__.'/partials/footer.php'; ?>
