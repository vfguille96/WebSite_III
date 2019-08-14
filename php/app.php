<?php
include_once 'dao.php';

class App
{
    protected $dao;

    function __construct()
    {
        $this->dao = new Dao();
    }

    function getDao()
    {
        return $this->dao;
    }

    /**
     * Function that saves username into $SESSION variable when user signin (signin.php)
     */
    function init_session($user)
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['user'] = $user;
        }
    }

    function invalidate_session()
    {
        if (isset($_SESSION['user'])) {
            unset ($_SESSION['user']);
        }
        session_destroy();
        $this->showSignIn();
    }

    function showSignIn()
    {
        header('Location: ../htmldocs/signin_page.html');
    }

    function isLogged()
    {
        return isset($_SESSION['user']);
    }

    function validateSession()
    {
        session_start();
        if (!$this->isLogged()) {
            $this->showSignIn();
        }
    }

    function loginCorrect($username){
        $nameSurname = $this->dao->showNameSurname($username);
        foreach ($nameSurname as $item){
            echo "<p><h1 style='color: green'>Welcome, ".$item['name']." ".$item['surname']."!</h1></p>";
        }
    }

    function failed($message){
        echo "<p><h1 style='color: darkred'>".$message."</h1></p>";
    }
}