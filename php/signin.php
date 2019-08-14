<?php
include_once 'app.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user = $_POST['user'];
    $password = $_POST['password'];
    if (empty($user)) {
        echo "<p>Username is empty.</p>";
    } else if (empty($password)) {
        echo "<p>Password is empty.</p>";
    } else {
        // Make a connection to DB and check if user exists.
        $app = new App();

        if (!$app->getDao()->isConnected()) {
            echo "<p>" . $app->getDao()->error . "</p>";
        } elseif ($app->getDao()->validateUser($user, $password)) {
            // Save user session.
            $app->init_session($user);
            $app->loginCorrect($user);
        } else {
            $app->failed("Wrong username or password.");
        }
    }
}