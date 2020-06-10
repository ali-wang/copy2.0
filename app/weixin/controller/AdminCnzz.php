<?php
namespace app\weixin\controller;
use app\weixin\logic\AdminCnzzLogic;
use cmf\controller\BaseController;

class AdminCnzz extends BaseController
{

    protected $adminCnzzLogin;
    public function __construct()
    {
        parent::__construct();
        $this->adminCnzzLogin = new AdminCnzzLogic();
    }


}