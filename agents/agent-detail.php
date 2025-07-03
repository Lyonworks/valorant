<?php
include 'partials/header.php';
include 'data/agents.php';

$id = $_GET['id'] ?? null;
$agent = null;

foreach ($agents as $a) {
  if ($a['id'] === $id) {
    $agent = $a;
    break;
  }
}
?>

<?php if (!$agent): ?>
  <div class="container text-center py-5">
    <h2 class="text-white">Agent not found</h2>
    <a href="index.php" class="btn btn-outline-light mt-3">← Back to Agents</a>
  </div>
<?php else: ?>
  <section class="agent-detail-section text-white py-5">
    <div class="container">
      <div class="row align-items-center mb-5">
        <div class="col-md-6 text-center mb-4 mb-md-0">
          <img src="<?= $agent['image'] ?>" alt="<?= $agent['name'] ?>" class="img-fluid agent-detail-img">
        </div>
        <div class="col-md-6">
          <h1 class="agent-detail-name"><?= $agent['name'] ?></h1>
          <h4 class="text-danger text-uppercase"><?= $agent['role'] ?></h4>
          <p class="mt-4"><?= $agent['description'] ?></p>
        </div>
      </div>

      <!-- Abilities Section -->
      <div class="abilities-section mt-5">
        <h2 class="text-uppercase mb-4">Abilities</h2>
        <div class="row">
          <?php foreach ($agent['abilities'] as $ability): ?>
            <div class="col-6 col-md-3 mb-4">
              <div class="ability-card text-center p-3 h-100 bg-dark border border-light rounded">
                <img src="<?= $ability['icon'] ?>" alt="<?= $ability['name'] ?>" class="img-fluid mb-2 ability-icon" />
                <h5 class="text-uppercase text-white"><?= $ability['name'] ?></h5>
                <p class="text-light small"><?= $ability['description'] ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <a href="index.php" class="btn btn-outline-light mt-4">← Back to Agents</a>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php include 'partials/footer.php'; ?>
