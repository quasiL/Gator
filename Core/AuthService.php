<?php

namespace Gator\Core;

use Delight\Auth\Auth;
use Delight\Auth\AuthError;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UserAlreadyExistsException;
use Exception;
use PDO;

class AuthService
{
    private static ?Auth $auth = null;

    /**
     * Return Auth instance if it exists, otherwise create it
     *
     * @return Auth Auth instance
     */
    public static function getAuth(): Auth
    {
        if (self::$auth === null) {
            $pdo = new PDO('mysql:host=database;dbname=my_database', 'user', 'password');
            self::$auth = new Auth($pdo);
        }
        return self::$auth;
    }


    /**
     * Register new user without email confirmation
     *
     * @param array<string> $credentials Email, password, and username
     * @return array<string, string|int|bool> Result of the registration process
     */
    public static function registerWithoutEmailConfirmation(array $credentials): array
    {
        try {
            $userId = self::getAuth()
                ->register($credentials['email'], $credentials['password'], $credentials['username']);
            return [
                'success' => true,
                'userId'  => $userId
            ];
        } catch (InvalidEmailException) {
            return [
                'success' => false,
                'error'   => 'Invalid email address'
            ];
        } catch (InvalidPasswordException) {
            return [
                'success' => false,
                'error'   => 'Invalid password'
            ];
        } catch (UserAlreadyExistsException) {
            return [
                'success' => false,
                'error'   => 'User already exists'
            ];
        } catch (TooManyRequestsException) {
            return [
                'success' => false,
                'error'   => 'Too many requests'
            ];
        } catch (Exception) {
            return [
                'success' => false,
                'error'   => 'An unexpected error occurred'
            ];
        }
    }
}