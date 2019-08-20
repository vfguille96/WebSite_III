<?php
class App
{
    function showSignIn()
    {
        header('Location: ../htmldocs/signin_page.html');
    }


    function loginCorrect($username){
            echo "<p><h1 style='color: green'>Welcome, ".$username."!</h1></p>";
        }

    function failed($message){
        echo "<p><h1 style='color: darkred'>".$message."</h1></p>";
    }
}