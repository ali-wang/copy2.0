<?php
namespace app\grab\controller;
use app\facade\Connectother as con;
use cmf\controller\AdminBaseController;

use think\facade\Request;
use app\grab\validate\UserValidate;
use app\grab\logic\AdduserLogic;
class AdminAdduserController extends AdminBaseController
{

    protected $adduserLogic;
    protected $validate;

    public function __construct()
    {
        parent::__construct();
        $this->validate = new UserValidate();
        $this->adduserLogic = new AdduserLogic();
    }


    public function index(){
        return $this->fetch();
    }

    /**
     * 添加子账户
     * @throws \FormBuilder\Exception\FormBuilderException
     */

    public function addForm(){
        if (Request::isGet()){
            //展示页面

            if ($this->adduserLogic->sonUsernum()){
                //超过5个子账户
                return $this->error('超过指定子账户数，请联系管理员');
            }else{
                return $this->adduserLogic->addForm();
            }

        }
        if (Request::isPost()){
            //接受添加数据
            return $this->adduserLogic->addUser(Request::param('user_login'),Request::param('user_pass'));
        }

    }


    /**
     * 子账户列表
     * @return mixed
     */
    public function sonUserList(){

        $alldata = $this->adduserLogic->userListLogic();
        return con::msg(0,'ok',count($alldata),$alldata);
    }


    public function Userson(){

        if (Request::isGet()){
//            var_dump(Request::param());
            $msg = $this->adduserLogic->deleUserson(Request::param('id'));
            return con::msg(0,$msg);
        }
    }




}