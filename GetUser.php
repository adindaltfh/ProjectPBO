<?php

use App\Model\User;
use App\View;

require_once 'vendor/autoload.php';

$user = new User();
View::json($user->getAll());