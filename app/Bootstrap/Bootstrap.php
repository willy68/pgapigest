<?php

use Middlewares\Whoops;

/* Comment on production */
putenv("ENV=dev");
/* Must be an environnement variable */
putenv('JWT_SECRET=MasuperPhraseSecrete');

$bootstrap = require 'App.php';

$app = (new Framework\App($bootstrap['config']))
    ->addModules($bootstrap['modules']);

if (getenv('ENV') === 'dev') {
    $app->pipe(Whoops::class);
}

$app->middlewares($bootstrap['middlewares']);

return $app;
