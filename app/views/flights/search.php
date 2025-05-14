<h1>Buscar vuelo</h1>
<form action="default.php" method="get" class="row g-3">
  <input type="hidden" name="url" value="flight/search">
  <div class="col-md-4">
    <label class="form-label">Origen</label>
    <input type="text" name="origin" class="form-control" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Destino</label>
    <input type="text" name="destination" class="form-control" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Fecha</label>
    <input type="date" name="date" class="form-control" required>
  </div>
  <div class="col-12 text-end">
    <button type="submit" class="btn btn-success">Buscar</button>
  </div>
</form>
