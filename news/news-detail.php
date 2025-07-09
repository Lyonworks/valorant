<?php
include 'data/detail.php';

$id = $_GET['id'] ?? null;

if (!$id || !isset($news_details[$id])) {
  http_response_code(404);
  include '404.php';
  exit;
}

$detail = $news_details[$id];

if ($detail && isset($detail['content'][0]) && $detail['content'][0]['type'] === 'video') {
  $youtubeUrl = $detail['content'][0]['url'] ?? null;
  if ($youtubeUrl) {
    header("Location: $youtubeUrl");
    exit;
  }
}

include 'partials/header.php';

function renderRichText($text) {
  // Jika sudah ada tag HTML (seperti <a> atau <strong>), langsung return tanpa htmlspecialchars
  if (preg_match('/<[^>]+>/', $text)) {
    return $text;
  }

  // Escape karakter khusus
  $escaped = htmlspecialchars($text);

  // Bold Markdown
  $escaped = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $escaped);

  // Auto-link domain/URL
  $escaped = preg_replace_callback(
    '/((https?:\/\/)?[\w.-]+\.[a-z]{2,}(\/\S*)?)/i',
    function ($match) {
      $url = $match[1];
      $href = (strpos($url, 'http') === 0) ? $url : "https://$url";
      return '<a href="' . htmlspecialchars($href) . '" class="valorant-link text-danger" target="_blank" rel="noopener noreferrer">' . htmlspecialchars($url) . '</a>';
    },
    $escaped
  );

  return $escaped;
}

if (!$detail): ?>
  <div class="container py-5 text-center text-danger">
    <h2>News not found.</h2>
  </div>
  <?php include 'partials/footer.php'; exit; ?>
<?php endif; ?>

<section class="news-detail-page theme-id-<?= intval($id) ?>" style="padding-top: 87px;">
    <div class="news-detail-header">
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
          <p class="fs-5 mb-4">
            <?= renderRichText($block['text']) ?>
          </p>

        <?php elseif ($block['type'] === 'heading'): ?>
          <h2 class="mt-5 mb-3 fw-bold text-center"><?= htmlspecialchars($block['text']) ?></h2>

        <?php elseif ($block['type'] === 'list' && !empty($block['items'])): ?>
          <ul class="mb-4 ps-3">
            <?php foreach ($block['items'] as $item): ?>
              <li class="fs-5">
                <?php
                if (is_array($item)) {
                  if (isset($item['text'], $item['url'])) {
                    if (preg_match('/^(.*?)-\s*(.+)$/', $item['text'], $match)) {
                      $platform = trim($match[1]);
                      $handle = trim($match[2]);
                      echo htmlspecialchars($platform) . ' - <a href="' . htmlspecialchars($item['url']) . '" class="valorant-link" target="_blank" rel="noopener noreferrer">' . htmlspecialchars($handle) . '</a>';
                    } else {
                      echo '<a href="' . htmlspecialchars($item['url']) . '" class="valorant-link" target="_blank" rel="noopener noreferrer">' . htmlspecialchars($item['text']) . '</a>';
                    }
                  } elseif (isset($item['text'], $item['items']) && is_array($item['items'])) {
                    echo htmlspecialchars($item['text']);
                    echo '<ul class="mt-2 ps-3">';
                    foreach ($item['items'] as $subItem) {
                      echo '<li class="fs-6">';
                      if (is_string($subItem)) {
                        echo htmlspecialchars($subItem);
                      } elseif (is_array($subItem) && isset($subItem['text'], $subItem['url'])) {
                        echo '<a href="' . htmlspecialchars($subItem['url']) . '" class="valorant-link" target="_blank" rel="noopener noreferrer">' . htmlspecialchars($subItem['text']) . '</a>';
                      }
                      echo '</li>';
                    }
                    echo '</ul>';
                  }
                } elseif (is_string($item)) {
                  if (preg_match('/^(.+?):\s*(.+)$/', $item, $matches)) {
                    $label = htmlspecialchars($matches[1]);
                    $content = trim($matches[2]);
                    $link = null;

                    if (preg_match('/^@([a-zA-Z0-9_]+)$/', $content, $m)) {
                      $link = "https://twitter.com/{$m[1]}";
                    } elseif ($label === 'Facebook' && preg_match('#^/([a-zA-Z0-9_.-]+)$#', $content, $m)) {
                      $link = "https://facebook.com/{$m[1]}";
                    } elseif ($label === 'Instagram' && preg_match('/^@([a-zA-Z0-9_.]+)$/', $content, $m)) {
                      $link = "https://instagram.com/{$m[1]}";
                    } elseif ($label === 'TikTok' && preg_match('/^@([a-zA-Z0-9_.]+)$/', $content, $m)) {
                      $link = "https://www.tiktok.com/@{$m[1]}";
                    } elseif ($label === 'YouTube' && preg_match('#(youtube\.com|youtu\.be)/?#i', $content)) {
                      $link = $content;
                    } elseif (stripos($content, 'flickr.com') !== false) {
                      $link = (strpos($content, 'http') === 0) ? $content : "https://{$content}";
                    } elseif (preg_match('/^https?:\/\//i', $content)) {
                      $link = $content;
                    } elseif (preg_match('/^[\w\-\.]+\.[a-z]{2,}(\/\S*)?$/i', $content)) {
                      $link = "https://{$content}";
                    }

                    if ($link) {
                      echo "$label: <a href=\"" . htmlspecialchars($link) . "\" class=\"valorant-link\" target=\"_blank\" rel=\"noopener noreferrer\">" . htmlspecialchars($content) . "</a>";
                    } else {
                      echo "$label: " . htmlspecialchars($content);
                    }
                  } else {
                    echo htmlspecialchars($item);
                  }
                }
                ?>
              </li>
            <?php endforeach; ?>
          </ul>
        
        <?php elseif ($block['type'] === 'table' && !empty($block['headers']) && !empty($block['rows'])): ?>
          <div class="table-responsive my-4">
            <table class="table custom-valorant-table text-center align-middle">
              <thead>
                <tr>
                  <?php foreach ($block['headers'] as $header): ?>
                    <th><?= htmlspecialchars($header) ?></th>
                  <?php endforeach; ?>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($block['rows'] as $row): ?>
                  <tr>
                    <?php foreach ($row as $cell): ?>
                      <td><?= htmlspecialchars($cell) ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

        <?php elseif ($block['type'] === 'image' && !empty($block['image'])): ?>
          <div class="text-center my-4">
            <img src="<?= htmlspecialchars($block['image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($block['alt'] ?? '') ?>">
            <?php if (!empty($block['caption'])): ?>
              <p class="text-muted mt-2"><?= htmlspecialchars($block['caption']) ?></p>
            <?php endif; ?>
          </div>

        <?php elseif ($block['type'] === 'gif' && !empty($block['url'])): ?>
          <div class="text-center my-4">
            <img src="<?= htmlspecialchars($block['url']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($block['alt'] ?? 'GIF') ?>">
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
</section>

<?php include 'partials/footer.php'; ?>
