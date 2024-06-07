<?php

use App\Model\Theme;
use App\View;

require_once 'vendor/autoload.php';

$newTheme = new Theme();

$newTheme->applyTheme ('#B3D4DE', 'Green Marble', 'Field in Summer');
echo "{$newTheme->selectByUser()}\n";
View::json($newTheme->save());
