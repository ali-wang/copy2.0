<?php
namespace app\grab\controller;
use cmf\controller\AdminBaseController;
use app\facade\Connectother as con;
use think\facade\Cache;
use app\grab\model\UserModel as User;


/**
 * Class AdminSelectController   showdata展示页面的查询类  2019年10月10日11:10:47
 * @package app\grab\controller
 */
class AdminUsercheckController extends AdminBaseController
{

    public function lst(){
        return $this->fetch();
    }

     public function lst_admin(){
        return $this->fetch();
    }

    public function showdata_index(){

        $input = input('param.');
        $user = new User();
        $now_id = cmf_get_current_admin_id();

        $data1 = $user->where('pid',0)->field(['id','user_login','btime','etime','domain','donum'])->page($input['page'],$input['limit'])->select()->toArray();

        $data = $user->where('pid',$now_id)->field(['id','user_login','btime','etime','domain','donum'])->page($input['page'],$input['limit'])->select()->toArray();

        $data = array_merge($data1,$data);
        foreach ($data as $key => $value) {
           $data[$key]['btime']=date('Y-m-d H:i:s',$value['btime']);
           $data[$key]['etime']=date('Y-m-d H:i:s',$value['etime']);
        }
        return con::msg(0,'ok',count($data),$data);
       
    }


     public function showdata_admin(){
        $input = input('param.');
       
        $user = new User();
        $data = $user->field(['id','user_login','btime','etime','domain','donum','pid'])->page($input['page'],$input['limit'])->select()->toArray();
        foreach ($data as $key => $value) {
           $data[$key]['btime']=date('Y-m-d H:i:s',$value['btime']);
           $data[$key]['etime']=date('Y-m-d H:i:s',$value['etime']);
        }
        return con::msg(0,'ok',count($data),$data);
       
    }

    public function changedata(){
        $data = input('param.');
        $user = new User();
       
        $pid = $user->where('id',$data['id'])->find();
        if($pid['pid'] == 0){
            $now_id = cmf_get_current_admin_id();
            $sql = $user->save(['pid'=>$now_id],['id'=>$data['id']]);
        }
        
        $sqldata = $user->save(['etime'=>strtotime($data['etime']),'btime'=>strtotime($data['btime']),'donum'=>$data['num']],['id'=>$data['id']]);
        return $sqldata;
    }

    public function lookuser(){

        return $this->fetch();

    }

    public function lookuser_admin(){

        return $this->fetch();

    }

    public function sele_user(){
        $input = input('param.');
        $use = new User();
        $now_id = cmf_get_current_admin_id();

       

        $data = $use->where('user_login',$input['user'])->field(['id','user_login','btime','etime','domain','donum'])->where('pid',$now_id)->find();
        // var_dump($data);
        if(empty($data)){
            $data = $use->where('user_login',$input['user'])->field(['id','user_login','btime','etime','domain','donum'])->where('pid',0)->find();
            if(empty($data)){
                 return con::msg(0,'error','','');
            }
        }

        $data['btime']=date('Y-m-d H:i:s',$data['btime']);
        $data['etime']=date('Y-m-d H:i:s',$data['etime']);

        if($data){
            return con::msg(0,'ok','',$data);
        }else{
            return con::msg(0,'error','','');
        }
        
    }

    public function sele_user_admin(){
        $input = input('param.');
        $use = new User();
        $now_id = cmf_get_current_admin_id();
        
        $data = $use->where('user_login',$input['user'])->field(['id','user_login','btime','etime','domain','donum'])->find();

        $data['btime']=date('Y-m-d H:i:s',$data['btime']);
        $data['etime']=date('Y-m-d H:i:s',$data['etime']);

        if($data){
            return con::msg(0,'ok','',$data);
        }else{
            return con::msg(0,'error','','');
        }
        
    }

}
