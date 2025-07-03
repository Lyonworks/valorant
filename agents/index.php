<?php include 'partials/header.php'; ?>
<?php include 'data/agents.php'; ?>

<section>
  <div class="main-agents">
    <h2 class="agents-name">AGENTS</h2>
  </div>  
  <div class="row g-5 container">
    <?php foreach ($agents as $agent): ?>
      <div class="col-sm-6 col-md-4 col-lg-3">
        <a href="agent-detail.php?id=<?= $agent['id'] ?>" class="agent-card text-decoration-none d-block">
          <div class="agent-img-container">
            <img src="<?= $agent['image'] ?>" class="img-fluid agent-img" alt="<?= $agent['name'] ?>">
          </div>
          <div class="agent-name text-uppercase fw-bold"><?= $agent['name'] ?></div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>  
</section>

<?php include 'partials/footer.php'; ?>