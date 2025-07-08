<?php include 'partials/header.php'; ?>

<style>
  .not-found-fullscreen {
    min-height: 90vh;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    background-color: #ebebeb;
    padding: 2rem;
  }
</style>

<div class="not-found-fullscreen text-center">
  <h1 class="display-1 fw-bold text-danger">404</h1>
  <p class="fs-4 text-dark">Oops! The page you're looking for doesn't exist.</p>
  <a href="index.php" class="btn btn-danger mt-3 px-4 py-2 fs-5">Back to News</a>
</div>

<?php include 'partials/footer.php'; ?>
