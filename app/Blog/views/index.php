<?php

echo $renderer->render('header', ['title' => 'Mes articles']) ?>

<h1>Bienvenue sur le blog</h1>
<div>
  <ul>
  <?php foreach ($posts as $post) {?>
      <li>
        <a href="<?php echo $router->generateUri('blog.show', [
            'slug' => $post->slug,
            'id' => $post->id]); ?>">
          <?php echo $post->name ?>
        </a>
      </li>
  <?php } ?>
  </ul>
</div>

<?php echo $renderer->render('footer') ?>