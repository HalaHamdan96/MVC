<?php

class IndexController extends BaseController{

    public function mainAction(){
        include CURR_VIEW_PATH . "main.html";
        $this->loader->library("Captcha");
        $captcha = new Captcha;
        $captcha->hello();
        $userModel = new UserModel("user");
        $users = $userModel->getUsers();
    }

    public function indexAction(){
        $userModel = new UserModel("user");
        $users = $userModel->getUsers();
        include CURR_VIEW_PATH. "index.html";
    }

    public function menuAction(){
        include CURR_VIEW_PATH . "menu.html";
    }

    public function dragAction(){
        include CURR_VIEW_PATH . "drag.html";
    }

    public function topAction(){
        include CURR_VIEW_PATH . "top.html";
    }
}
?>