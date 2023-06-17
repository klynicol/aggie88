<?= view('header', ['title' => "{$user['name']} Profile"]); ?>

<!--logout button -->
<a href="/logout" class="btn btn-danger">Logout</a>

<?php foreach ($linktrees as $linktree) : ?>
   <?= json_encode($linktree); ?>

<?php endforeach; ?>

<!-- Add new linktree -->
<a href="/linktree/create" class="btn btn-primary mt-4">Add New Linktree</a>