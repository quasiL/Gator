<?php

namespace Gator\Backend\Controllers;

use Gator\Core\Attributes\Route;
use Gator\Core\Controller;
use Gator\Core\Http\HttpRequest;
use Gator\Core\Http\HttpResponse;

class SecondController extends Controller
{
    #[Route('/game', 'GET')]
    public function getGame(HttpRequest $request, HttpResponse $response)
    {
        echo 'gameee msg';
    }
}