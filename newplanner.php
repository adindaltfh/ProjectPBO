<?php

use App\Model\Planner;
use App\View;

require_once 'vendor/autoload.php';

$newPlanner = new Planner();

$id_planner = 192344;

$newPlanner->detail($id_planner);

echo "Planner sebelum perubahan:\n";
View::json($newPlanner);

$newPlanner->markAsComplete();

echo "\nPlanner setelah ditandai sebagai selesai:\n";
View::json($newPlanner);