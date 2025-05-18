<?php include __DIR__.'/partials/header.php'; ?>
<h2>Mis Reservas</h2>

<table class="table">
    <thead>
        <tr>
            <th>Destino</th>
            <th>Código</th>
            <th>Fecha</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($reservations as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['pais_destino']) ?></td>
            <td><?= htmlspecialchars($r['codigo_reserva']) ?></td>
            <td><?= htmlspecialchars($r['fecha_reserva']) ?></td>
            <td>
                <a href="default.php?controller=reservation&action=delete&id=<?= $r['id'] ?>"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('¿Cancelar esta reserva?');">
                    Eliminar
                </a>
            </td>
        </tr>
    <?php endforeach; ?>

    <?php if (empty($reservations)): ?>
        <tr>
            <td colspan="4" class="text-center">Sin reservas</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<?php if (!Auth::isAdmin()): ?>
<hr>
<h3>Otras opciones de la cuenta</h3>
<a href="default.php?controller=user&action=deleteAccount"
   class="btn btn-outline-danger"
   onclick="return confirm('¿Seguro que deseas borrar tu cuenta? Esta acción eliminará también todas tus reservas y no se puede deshacer.');">
   Borrar Cuenta
</a>
<?php endif; ?>

<?php include __DIR__.'/partials/footer.php'; ?>
