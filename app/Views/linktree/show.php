<!-- Start Footer -->
<link rel="stylesheet" href="<?= base_url(); ?>/css/link.css">
<link rel="stylesheet" href="<?= base_url(); ?>/css/youtube.css">

<body class="mylinks-body">
   <div class="mylinks">
      <div class="avatar">
         <img width="140" height="140" src="<?= base_url() . $linktree['avatar']; ?>" alt="<?= $user['name']; ?>">
      </div>
      <div class="name"><?= $user['name']; ?></div>
      <div class="description">
         <?= $linktree['tagline']; ?>
      </div>
      <div class="user-profile">
         <?php foreach ($socialKeys as $sc) : ?>
            <?php if (isset($linktree['social'][$sc]) && !empty($linktree['social'][$sc])) : ?>
               <a href="<?= $linktree['social'][$sc]; ?>" target="_blank" class="user-profile-link external" rel="noopener noreferrer nofollow">
                  <img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?= base_url(); ?>assets/<?= $sc; ?>.png">
               </a>
            <?php endif; ?>
         <?php endforeach; ?>
      </div>
      <!-- End Top Social Media -->
      <div class="links">
         <?php foreach ($linktree['links'] as $link) : ?>
            <?php if ($link['type'] === 'standard') : ?>
               <div class="link">
                  <a id="link_count" class="button link-with-image inline-photo show-on-scroll" href="<?= $link['url']; ?>" 
                     target="_blank" rel="noopener">
                     <div class="thumbnail-wrap">
                        <img src="<?= base_url() . $link['icon']; ?>" class="link-image" alt="thumbnail">
                     </div>
                     <span class="link-text"><?= $link['text']; ?></span>
                  </a>
               </div>
            <?php elseif ($link['type'] === 'video') : ?>
               <div class="link youtube-embed">
                  <div class="youtube-player">
                     <div data-id="<?= $link['url']; ?>"><img src="<?= $link['url']; ?>">
                        <div class="play"></div>
                     </div>
                  </div>
               </div>
            <?php endif; ?>
         <?php endforeach; ?>
      </div>
   </div>
</body>