<?php
session_start();

require '../vendor/autoload.php';

require '../bootstrap/app.php';

require '../routes/web.php';    

class HomeController
{
    public function index()
    {
        view('index');
    }
}

$controller = new HomeController();
$controller->index();
