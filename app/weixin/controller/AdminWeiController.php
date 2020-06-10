<?php
namespace app\weixin\controller;
use cmf\controller\AdminBaseController;
use think\facade\Request;
use app\weixin\logic\AdminWeiLogic;

class AdminWeiController extends AdminBaseController
{
    protected $AdminWeiLogic;

    public function __construct()
    {
        parent::__construct();
        $this->AdminWeiLogic = new AdminWeiLogic;
    }


    /**
     * @return mixed
     * 微信总列表
     */
    public function index(){
        if (Request::isPost()){
            var_dump($this->AdminWeiLogic->selectWx());
        }
       $wxdata =  $this->AdminWeiLogic->selectWx();
        $this->assign("mywxdata",$wxdata);
         return $this->fetch();
    }

    /**
     * 创建分组
     */

    public function saveGroup(){

        //创建添加组表单
        if(Request::isGet()){
            return $this->AdminWeiLogic->creatGroup();
        }
        //保存组信息
        if (Request::isPost()){
            return $this->AdminWeiLogic->saveGroup(Request::param());
        }

        //删除分组

        if (Request::isDelete()){
//            var_dump(Request::param());
            return $this->AdminWeiLogic->deleGroup(Request::param('id'));
        }
    }


    /**
     * 添加微信号
     */

    public function addWx(){

        //创建添加表单
        if(Request::isGet()){
            if (!Request::param('edit')){
                //添加页面
                $this->AdminWeiLogic->addWxForm(cmf_get_current_admin_id(),Request::param('gid'));
            }else{
                //编辑页面
                $this->AdminWeiLogic->editWxForm(Request::param('gid'));
            }

        }
        //保存表单
        if (Request::isPost()){
          return $this->AdminWeiLogic->saveAddWx(Request::param());
        }

        //修改微信表单
        if (Request::isPut()){
            return $this->AdminWeiLogic->saveEditForm(Request::param());
        }

        if (Request::isDelete()){
            return $this->AdminWeiLogic->deleWx(Request::param('id'));
        }
    }


    //调用微信数据
    public function getWxjs(){

        return $this->AdminWeiLogic->getWxjs(Request::param('id'));
    }


    //存储二维码
    public function imgSave(){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        // 移动到框架应用根目录/uploads/ 目录下
        $info = $file->move('./uploads');
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            echo $info->getExtension();
            // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getSaveName();
            // 输出 42a79759f284b767dfcb2a0197904287.jpg
            echo $info->getFilename();
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }
}