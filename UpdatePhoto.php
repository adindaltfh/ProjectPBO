<?php

use App\Model\User;
use App\View;

require_once 'vendor/autoload.php';

$id_user = 653129; 
$updatePhoto = 'new_photopuan.jpg';

if ($id_user && $updatePhoto) {
    $user = new User;
    $user->detail($id_user);
    echo "Current Photo: {$user->photo}\n";

    $user->setPhoto($updatePhoto);
    $user->save();
    echo "Updated Photo: {$user->photo}\n";

    View::json($user);
} else {
    echo("Missing parameters");
}