<?php

use App\Model\Planner;
use App\View;

require_once 'vendor/autoload.php';

$planner = new Planner();
View::json($planner->getAll());