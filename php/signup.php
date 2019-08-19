<?php
include_once 'app.php';
include_once 'usersxml.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = $_POST['user'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $app = new App();
    $regexpPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
    $regexpUsername = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/';

    if (empty($user)) {
        $app->failed("Username is empty.");
    } else if (empty($password)) {
        $app->failed("Password is empty.");
    } else if (empty($name)) {
        $app->failed("Name is empty.");
    } else if (empty($surname)) {
        $app->failed("Surname is empty.");
    } else if (!preg_match($regexpPassword, $password)) {
        $app->failed("Password does not contain at least 1 number/letter, 8 character minimum requirement.");
    } else if (!preg_match($regexpUsername, $user)) {
        $app->failed("Username must be a email direction.");
    }
    // Write user in XML file and show Sign In page.
    $users = new SimpleXMLElement($xmlstr);
    $exists = false;
    foreach ($users->xpath('//user') as $userCollection) {
        if ($userCollection->username == $user) {
            $exists = true;
            $app->failed("Username already exists");
        }
    }

    if (!$exists) {
        $newUser = $users->addChild('user');
        $newUser->addChild('username', $user);
        $newUser->addChild('password', $password);
        $newUser->addChild('name', $name);
        $newUser->addChild('surname', $surname);
    }

    echo $users->asXML();
    $users->asXML('')


    //$app->showSignIn();
    //$app->failed("Unable to register the user.");
    $exists = false;
}