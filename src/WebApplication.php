<?php
namespace Tudublin;

class WebApplication
{
    private $mainController;
    private $commentController;
    private $loginController;
    private $profileManager;
    private $reviewController;
    private $shopController;
    private $accountManager;

    public function __construct()
    {
        $this->commentController = new CommentController();
        $this->mainController = new MainController();
        $this->loginController = new LogInController();
        $this->profileManager = new ProfileManager();
        $this->reviewController = new ReviewController();
        $this->shopController = new ShopController();
        $this->accountManager = new AccountManager();
    }

    public function run()
    {
        $action = filter_input(INPUT_GET, 'action');
        if(empty($action))
        {
            $action = filter_input(INPUT_POST, 'action');
        }

        switch ($action)
        {
            case 'about':
                $this->mainController->about();
                break;

            case 'review':
                $this->mainController->review();
                break;

            case 'login':
                $this->mainController->login();
                break;

            case 'processLogin':
                $this->loginController->processLogin();
                break;

            case 'processShop':
                if('staff' == $_SESSION['role'])
                {
                    $this->shopController->processShop();
                }
                else{
                    $this->mainController->error();
                }
                break;

            case 'logout':
                $this->mainController->logOut();
                break;

            case 'secret':
                if('admin' == $_SESSION['role']) {
                    $this->mainController->accountManager();
                }
                else{
                    $this->mainController->error();
                }
                break;

            case 'craeteAccount':
                if('admin' == $_SESSION['role']) {
                    $this->accountManager->createACC();
                }
                else{
                    $this->mainController->error();
                }
                break;

            case 'deleteAccount':
                if('admin' == $_SESSION['role']) {
                    $this->accountManager->deleteUser();
                }
                else{
                    $this->mainController->error();
                }
                break;

            case 'deleteComment':
                    $this->commentController->deleteComment();
                break;

            case 'permitComment':
                     $this->commentController->permitComment();
                break;

            case 'profile':
                if(1 == $_SESSION['ifPayed'] and isset($_SESSION['userName']))
                {
                    $this->mainController->profile($error =[]);

                }else{
                    $this->mainController->error();
                }
                break;

            case 'profileSubmit':
                if(1 == $_SESSION['ifPayed'] and isset($_SESSION['userName']))
                {
                    $this->profileManager->updateProfile();
                }else{
                    $this->mainController->error();
                }
                break;

            case 'profileDisplay':
                $this->profileManager->displayProfile();
                break;

            case 'processComment':
                $this->commentController->processComment();
                break;

            case 'deleteShop':
                if('staff' == $_SESSION['role'])
                {
                    $this->shopController->deleteShop();
                }
                else{
                    $this->mainController->error();
                }
                break;

            case 'processReview':
                    $this->reviewController->processReview();
                break;

            case 'deleteReview':
                    $this->reviewController->deleteReview();
                 break;

            default:
                $this->mainController->home();
        }
    }
}