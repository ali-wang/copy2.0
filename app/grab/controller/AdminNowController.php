<?php
namespace app\grab\controller;
use app\facade\Connectother as con;
use app\grab\logic\AdminNowLogic;
use cmf\controller\AdminBaseController;
use think\facade\Request;

/**
 * Class AdminNowController
 * 实时统计当前访问信息
 *
 * @package app\grab\controller
 */
class AdminNowController extends AdminBaseController
{
    protected $adminNow;

    public function __construct()
    {
        parent::__construct();
        $this->adminNow= new AdminNowLogic();
    }

    public function indexlist(){

        $url = con::cnzzurls();
        $this->assign('url',$url);
        return $this->fetch();

    }

    public function nowtimes(){
//        var_dump(Request::param());
       return $this->adminNow->nowList(Request::param('url'));
    }


}