<?php
include_once 'app.php';

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
    } else {
        $filename = 'usersxml.xml';
        $doc = new DOMDocument('1.0');
        $doc->formatOutput = true;
        $doc->load($filename);
        // Convert the stream into string xml format.
        $xmlString = $doc->saveXML();
        $users = new SimpleXMLElement($xmlString);
        $exists = false;
        // Checks if the username already exists and activate the flag.
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

            $users->asXML($filename);
            $exists = false;
            $app->showSignIn();
        }
    }
    $app->failed("Unable to register the user.");
}