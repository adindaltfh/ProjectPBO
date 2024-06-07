<?php

use App\Model\Widget;
use App\View;

require_once 'vendor/autoload.php';

$widget = new Widget();
View::json($widget->getAll());