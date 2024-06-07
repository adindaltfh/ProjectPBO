<?php

use App\Model\Theme;
use App\View;

require_once 'vendor/autoload.php';

$theme = new Theme();
View::json($theme->getAll());