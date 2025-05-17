
<?php include __DIR__.'/partials/header.php'; ?>
<h2>Vuelos Disponibles</h2>
<?php if(isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
<table class="table table-bordered">
<thead><tr><th>Destino</th><th>Aerolíneas</th><th>Plazas</th><th>Acción</th></tr></thead>
<tbody>
<?php foreach(($flights??[]) as $f): ?>
<tr>
<td><?= htmlspecialchars($f['pais_destino']) ?></td>
<td><?= htmlspecialchars($f['aerolineas']) ?></td>
<td><?= $f['plazas_disponibles'] ?>/<?= $f['capacidad'] ?></td>
<td>
<?php if($f['plazas_disponibles']>0): ?>
<a class="btn btn-primary btn-sm" href="default.php?controller=reservation&action=reserve&id=<?= $f['id'] ?>">Reservar</a>
<?php else: ?>Completo<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php include __DIR__.'/partials/footer.php'; ?>
