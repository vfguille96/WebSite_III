<?php
include_once 'app.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = $_POST['user'];
    $password = $_POST['password'];
    if (empty($user)) {
        echo "<p>Username is empty.</p>";
    } else if (empty($password)) {
        echo "<p>Password is empty.</p>";
    } else {
        $app = new App();
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
                $app->loginCorrect($user);
                return;
            }
        }

        if (!$exists) {
            $exists = false;
            $app->failed("Wrong username or password.");
        }
    }
}