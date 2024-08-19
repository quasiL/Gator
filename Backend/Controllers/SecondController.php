<?php

namespace Gator\Backend\Controllers;

use Delight\Auth\AttemptCancelledException;
use Delight\Auth\AuthError;
use Gator\Core\Attributes\Route;
use Gator\Core\AuthService;
use Gator\Core\Controller;
use Gator\Core\Http\HttpRequest;
use Gator\Core\Http\HttpResponse;

class SecondController extends Controller
{
    #[Route('/register', 'POST')]
    public function registerUser(HttpRequest $request, HttpResponse $response)
    {
        $body = $request->getBody();
        //$_SERVER['REMOTE_ADDR'] = '127.0.0.4';
        var_dump(AuthService::registerWithoutEmailConfirmation(credentials: $body));
    }

    #[Route('/login', 'POST')]
    public function loginUser(HttpRequest $request, HttpResponse $response)
    {
        $body = $request->getBody();

        try {
            AuthService::getAuth()->login($body['email'], $body['password']);

            echo 'User is logged in';
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Wrong email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Wrong password');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        } catch (AttemptCancelledException $e) {
        } catch (AuthError $e) {
        }
    }

    /**
     * @throws AuthError
     */
    #[Route('/logout', 'POST')]
    public function logoutUser(HttpRequest $request, HttpResponse $response)
    {
        AuthService::getAuth()->logOut();
        echo 'User is logged out';
    }

    #[Route('/check-login', 'POST')]
    public function checkLogin(HttpRequest $request, HttpResponse $response)
    {
        if (AuthService::getAuth()->isLoggedIn()) {
            echo 'User is logged in ' . AuthService::getAuth()->getUserId();;
        } else {
            echo 'User is not logged in';
        }
    }

}