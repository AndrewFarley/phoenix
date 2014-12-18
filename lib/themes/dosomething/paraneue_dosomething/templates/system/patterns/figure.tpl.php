<div class="tile tile--figure">
  <?php if (!empty($content['image'])): ?>
    <div class="__media">
      <?php if (!empty($content['url'])): ?>
        <a href="<?php print $content['url']; ?>">
          <?php print $content['image']; ?>
        </a>
      <?php else: ?>
        <?php print $content['image']; ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($content['title'])): ?>
    <h3 class="__title">
      <?php print $content['title']; ?>
    </h3>
  <?php endif; ?>
  <?php if (!empty($content['description'])): ?>
    <div class="__description">
      <?php print $content['description']; ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($content['link'])): ?>
    <a class="btn" href="<?php print $content['link'] . '#prove'; ?>">Prove It</a>  
  <?php endif; ?>
</div>
