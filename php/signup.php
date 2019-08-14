<?php
include_once 'app.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = $_POST['user'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $app = new App();
    $regexpPassword = '/(?i)^(?=.*[a-z])(?=.*\d).{8,}$/';
    $regexpUsername = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/';

    if (empty($user)) {
        $app->failed("Username is empty.");
    } else if (empty($password)) {
        $app->failed("Password is empty.");
    } else if (empty($name)) {
        $app->failed("Name is empty.");
    } else if (empty($surname)) {
        $app->failed("Surname is empty.");
    } else {
        // Make a connection to DB and check if user exists.
        if (!$app->getDao()->isConnected()) {
            echo "<p>" . $app->getDao()->error . "</p>";
        } elseif (!$app->getDao()->validateUserSignUp($user)) {
            if(!preg_match($regexpPassword, $password)) {
                $app->failed("Password does not contain at least 1 number/letter, 8 character minimum requirement.");
            }else if (!preg_match($regexpUsername, $user)){
                $app->failed("Username must be a email direction.");
            }else{
                if ($app->getDao()->registerUser($user, $password, $name, $surname)){
                    $app->showSignIn();
                }else{
                    $app->failed("Unable to register the user.");
                }
            }
        } else {
            $app->failed("Username already exists.");
        }
    }
}