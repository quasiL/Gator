<?php

namespace Gator\Backend\Controllers;

use Gator\Backend\Models\User;
use Gator\Core\Attributes\Route;
use Gator\Core\Controller;
use Gator\Core\Database\Burt;
use Gator\Core\Http\HttpRequest;
use Gator\Core\Http\HttpResponse;

class TestController extends Controller
{
    #[Route('/test', 'GET')]
    public function getTest(HttpRequest $request, HttpResponse $response)
    {
        User::create([
            'email' => 'gdfgrt@example.com',
            'firstname' => 'Alex',
            'lastname' => 'Smith',
            'password' => 'gghghgj',
            'status' => 1
        ])->persist();
    }

    #[Route('/about', 'GET')]
    public function getAbout(HttpRequest $request, HttpResponse $response)
    {
        $users = Burt::table('users')
            ->select()
            ->where('username', '=', 'user')
            ->getAll();
//        $response->view('about', new ViewModel([
//            'users' => $users
//        ]));
    }
}