<?php include 'partials/header.php'; ?>
<?php include 'data/news.php'; ?>

<section>
  <div class="news">
    <h2 class="news-name">NEWS</h2>
  </div>  
  <div class="container-lg news-container">
    <div class="row g-5">
      <?php foreach ($news as $index => $item): ?>
        <div class="col-sm-6 col-md-4 news-item d-none" data-index="<?= $index ?>">
          <a href="news-detail.php?id=<?= $item['id'] ?>" class="news-card text-decoration-none d-block">
            <div class="news-img-container">
              <img src="<?= $item['image'] ?>" class="card-img-top" alt="<?= $item['title'] ?>">
            </div>
            <div class="card-body">
              <div class="news-meta d-flex text-uppercase mb-2">
                <span class="news-category"><?= $item['category'] ?></span>
                <div class="news-line"></div>
                <span class="news-date"><?= $item['date'] ?></span>
              </div>
              <h5 class="card-title fw-bold text-dark"><?= $item['title'] ?></h5>
              <p class="card-text text-dark"><?= $item['excerpt'] ?></p>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="divider">
      <div class="button">
        <button class="show-more">
          <span>SHOW MORE</span>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus plus" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const newsItems = document.querySelectorAll(".news-item");
    const showMoreButton = document.querySelector(".show-more");
    let visibleCount = 12;

    function updateVisibleItems() {
      newsItems.forEach((item, index) => {
        if (index < visibleCount) {
          item.classList.remove("d-none");
        } else {
          item.classList.add("d-none");
        }
      });

      if (visibleCount >= newsItems.length) {
        showMoreButton.style.display = "none";
      }
    }

    showMoreButton.addEventListener("click", () => {
      visibleCount += 12;
      updateVisibleItems();
    });

    updateVisibleItems();
  });
</script>

<?php include 'partials/footer.php'; ?>
