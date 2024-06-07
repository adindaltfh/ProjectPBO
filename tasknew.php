<?php

use App\Model\Task;
use App\View;

require_once 'vendor/autoload.php';

$newTask = new Task();

$newTask->idTask(372611);
$newTask->addTask('Facial Treatment', 'Personal', '2023-12-30', '10:00:00');
$newTask->addDescription('Perawatan wajah rutin di salon sampai akhir tahun');
$newTask->addRepeat('Sat');
$newTask->newTask();
View::json($newTask->save());
