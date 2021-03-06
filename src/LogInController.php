<?php
namespace Tudublin;


class LogInController
{
    private $dB;
    private $mainController;

    public function __construct()
    {
        $this->dB = new Db();
        $this->mainController = new MainController();
    }
    //takes user input from login page and will check if details matches the one in db
    public function processLogin()
    {
        $userName = filter_input(INPUT_POST, "userName",FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);

        $acc = $this->getUser($userName, $password);

        $roleCheck = $this->getRoleAdmin();

        if(null == $acc)
        {
            $this->mainController->login('wrong password or username');
        }
        //will check if one of admins already connected
        elseif($roleCheck != '')
        {
            $this->mainController->login($roleCheck);
        }
        else{
            $_SESSION['role'] = $acc->getRole();
            $_SESSION['userName'] = $acc->getUserName();
            $_SESSION['ifPayed'] = $acc->getPayed();

            $this->mainController->home();
        }
    }
    //find user and verify pass
    private function getUser($userName, $password)
    {
        $acc = $this->dB->findUser($userName);

        if(null == $acc)
        {
            return null;
        }

        $passDB = $acc->getPassword();

        if(password_verify($password, $passDB))
        {
            return $acc;
        }
        return null;
    }

    //check if admin is already logged in
    private function getRoleAdmin()
    {
        if(isset($_SESSION['role']))
        {
            if($_SESSION['role'] == 'admin')
            {
                return 'Administrator is already logged in';
            }
        }
        return '';
    }
}