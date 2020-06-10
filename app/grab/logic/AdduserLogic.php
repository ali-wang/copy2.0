<?php
namespace app\grab\logic;
use cmf\controller\AdminBaseController;
use  FormBuilder\Factory\Elm;
use FormBuilder\Response;
use think\Db;

class AdduserLogic extends AdminBaseController
{

    /**
     * 添加子账户页面
     * @throws \FormBuilder\Exception\FormBuilderException
     */
    public function addForm(){
        $action = 'addForm';
        $method = 'POST';

        $input = Elm::input('user_login', '用户名')->required();
        $textarea = Elm::input('user_pass', '密码')->required();

        //创建表单
        $form = Elm::createForm($action)->setMethod($method);

     //添加组件
        $form->setRule([$input, $textarea]);

        //生成表单页面
        echo $formHtml = $form->view();
    }

    public function sonUsernum(){
        $id = cmf_get_current_admin_id();
//        $user   = DB::name('user')->where('id',$id)->find();
       $sonuserNum =  DB::name('user')->where('pid',$id)->count();
       return $sonuserNum > 2 ? true:false;
    }



    /**
     * 添加子账户
     * @param $username 用户名
     * @param $password 密码
     */
    public function addUser($username,$password){
        //不能为空，验证用户名是否存在
        if(empty($username)||empty($password)){
            echo Response::fail('用户名，密码输入有误！')->getContent();
            return ;
        }
        $flag = DB::name('user')->where('user_login',$username)->find();
        if ($flag){
            echo Response::fail('改账户已经存在，请重新添加！')->getContent();
            return ;
        }else{
            $datas['user_login'] = $username;
            $datas['user_pass'] = cmf_password($password);
            $datas['pid'] = cmf_get_current_admin_id();
             $res =  DB::name('user')->where('id', $datas['pid'])->find();
            $datas['btime']=  $res['btime'];
            $datas['etime']=  $res['etime'];
            $datas['domain']= "[\"\"]";
            $result   = DB::name('user')->insertGetId($datas);
            if ($result !== false) {
                //$role_user_model=M("RoleUser");
                //对应管理员编号$role_id = 2
                $role_id = 2;
                Db::name('RoleUser')->insert(["role_id" => $role_id, "user_id" => $result]);
                echo Response::success('创建成功！')->getContent();
                return ;
            } else {
                echo Response::fail('创建失败！')->getContent();
                return ;
            }
        }

    }


    public function userListLogic(){

        $res = Db::name('user')->field('id,user_login,btime,etime,domain,pid')->order('id','desc')->select()->toArray();
        $id = cmf_get_current_admin_id();

        foreach ($res as $k=>$v){
            $res[$k]['btime'] = date("Y-m-d H:i:s", $v['btime']);
            $res[$k]['etime'] = date("Y-m-d H:i:s", $v['etime']);
        }
        $sonId = $this->get_attr($res,$id);
        return $sonId;

    }


    public function deleUserson($id){
        $res = Db::name('user')->field('id,pid')->select()->toArray();
        $sonId = $this->get_attr($res,$id);
         Db::name('user')->delete(['id'=>$id]);
        foreach ($sonId as $v){
           Db::name('user')->delete(['id'=>$v['id']]);
        }
        return true;
    }


    public function get_attr($array,$id){
        $tree = array();                                //每次都声明一个新数组用来放子元素
        foreach($array as $v){
            if($v['pid'] == $id){                      //匹配子记录
                $v['children'] = $this->get_attr($array,$v['id']); //递归获取子记录
                if($v['children'] == null){
                    unset($v['children']);             //如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）
                }
                $tree[] = $v;                           //将记录存入新数组
            }
        }
        return $tree;                                  //返回新数组
    }





}