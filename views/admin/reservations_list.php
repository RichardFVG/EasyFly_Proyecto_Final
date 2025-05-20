<?php include __DIR__.'/../partials/header.php'; ?>
<h2>Panel de Reservas</h2>

<table class="table table-striped">
<thead>
<tr>
  <th>ID</th><th>Usuario</th><th>Email</th><th>Destino</th>
  <th>Código</th><th>Vuela el</th><th>Fecha reserva</th><th></th>
</tr>
</thead>
<tbody>
<?php foreach($list as $r): ?>
<tr>
  <td><?= $r['id'] ?></td>
  <td><?= htmlspecialchars($r['nombre']) ?></td>
  <td><?= htmlspecialchars($r['email']) ?></td>
  <td><?= htmlspecialchars($r['pais_destino']) ?></td>
  <td><?= htmlspecialchars($r['codigo_reserva']) ?></td>
  <td><?= (new DateTime($r['fecha_vuelo']))->format('d/m/Y H:i') ?></td>
  <td><?= $r['fecha_reserva'] ?></td>
  <td>
    <a href="default.php?controller=reservation&action=delete&id=<?= $r['id'] ?>"
       onclick="return confirm('¿Eliminar?')" class="btn btn-sm btn-danger">
       Eliminar
    </a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
