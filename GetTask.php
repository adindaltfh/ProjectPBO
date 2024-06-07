<?php

use App\Model\Task;
use App\View;

require_once 'vendor/autoload.php';

$task = new Task();
View::json($task->getAll());