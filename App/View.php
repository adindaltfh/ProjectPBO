<?php
namespace App;


class View
{
    public static function json($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
    }
}
