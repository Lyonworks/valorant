<?php
include 'data/detail.php';

$id = $_GET['id'] ?? null;
$detail = $news_details[$id] ?? null;

if ($detail && isset($detail['content'][0]) && $detail['content'][0]['type'] === 'video') {
  $youtubeUrl = $detail['content'][0]['url'] ?? null;
  if ($youtubeUrl) {
    header("Location: $youtubeUrl");
    exit;
  }
}

include 'partials/header.php';

if (!$detail): ?>
  <div class="container py-5 text-center text-danger">
    <h2>News not found.</h2>
  </div>
  <?php include 'partials/footer.php'; exit; ?>
<?php endif; ?>

<section class="news-detail-page" style="background-color: #ece8e1; padding-top: 87px;">
  <div class="">
    <div class="news-detail-header mb-4">
      <?php if (!empty($detail['image'])): ?>
        <div class="mb-4 text-center">
          <img src="<?= htmlspecialchars($detail['image']) ?>" alt="<?= htmlspecialchars($detail['title']) ?>" class="img-fluid rounded">
        </div>
      <?php endif; ?>

      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-5">
        <div class="flex-shrink-0">
          <p class="news-detail-d-a mb-0">
            <span class="d-block"><?= htmlspecialchars($detail['date']) ?></span>
            <span class="d-block"><?= htmlspecialchars($detail['author']) ?></span>
          </p>
        </div>
        <div class="flex-grow-1">
          <h1 class="news-detail-title mb-0"><?= htmlspecialchars($detail['title']) ?></h1>
          <?php if (!empty($detail['description'])): ?>
            <p class="fs-5 mt-3"><?= htmlspecialchars($detail['description']) ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="news-detail-content">
      <?php foreach ($detail['content'] as $block): ?>
        <?php if ($block['type'] === 'paragraph'): ?>
          <p class="text-dark fs-5 mb-4"><?= nl2br(htmlspecialchars($block['text'])) ?></p>

        <?php elseif ($block['type'] === 'heading'): ?>
          <h2 class="mt-5 mb-3 fw-bold text-dark"><?= htmlspecialchars($block['text']) ?></h2>

        <?php elseif ($block['type'] === 'list' && !empty($block['items'])): ?>
          <ul class="mb-4">
            <?php foreach ($block['items'] as $item): ?>
              <li class="text-dark fs-5"><?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
          </ul>

        <?php elseif ($block['type'] === 'image' && !empty($block['src'])): ?>
          <div class="text-center my-4">
            <img src="<?= htmlspecialchars($block['src']) ?>" class="img-fluid" alt="<?= htmlspecialchars($block['alt'] ?? '') ?>">
            <?php if (!empty($block['caption'])): ?>
              <p class="text-muted mt-2"><?= htmlspecialchars($block['caption']) ?></p>
            <?php endif; ?>
          </div>

        <?php elseif ($block['type'] === 'video' && !empty($block['url'])): ?>
          <?php
            preg_match('/(?:v=|\/)([a-zA-Z0-9_-]{11})/', $block['url'], $matches);
            $videoId = $matches[1] ?? null;
          ?>
          <?php if ($videoId): ?>
            <div class="ratio ratio-16x9 my-4">
              <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($videoId) ?>" 
                      title="YouTube Video" 
                      allowfullscreen></iframe>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include 'partials/footer.php'; ?>
