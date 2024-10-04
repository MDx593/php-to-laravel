<?php

use core\library\App;
use core\templates\Latte;
use core\templates\Plates;
use core\templates\Smarty;
use core\templates\Twig;

$app = App::create()
    ->withEnvironmentVariables()
    ->withTemplateEngine(Plates::class)
    ->withErrorPage()
    ->withContainer();
