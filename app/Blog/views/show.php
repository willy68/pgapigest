<?php

echo $renderer->render('header', ['title' => $post->name]); ?>

<div>
  <h1><?php echo $post->name ?></h1> 
  
  <?php echo nl2br($post->content);?>
</div>
<?php echo $renderer->render('footer'); ?>