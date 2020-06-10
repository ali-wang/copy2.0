<?php
namespace app\grab\controller;
use cmf\controller\AdminBaseController;
use app\facade\Connectother as con;
use think\facade\Cache;
use app\grab\model\UserModel as User;



class AdminCodeinstallController extends AdminBaseController
{

    public function index(){
    	$id = cmf_get_current_admin_id();
    	$data = db('user')->where('id',$id)->field(['id','user_login','btime','etime','domain','donum'])->find();

    	$data['btime']=date('Y-m-d',$data['btime']);
        $data['etime']=date('Y-m-d',$data['etime']);
        // $data['domain']=json_decode($data['domain']);
        $str = "
        <script id='a2bc' src='http://zxfz.yxykedu.com/a2bc.js?kw_sign_id=".$data['id']."'  charset='UTF-8'></script>

        
        ";
        $data['str'] = $str;
    	// var_dump($data);
    	$this->assign('numdata',$data);
        return $this->fetch();
    }

   public function savedomain(){
    $data = input('param.');
    
    $user = new User();
    $sqldata = $user->save(["domain"=>json_encode($data['url'])],['id'=>$data['id']]);
    // var_dump($sqldata);
    if($sqldata){

      $json_url=dirname(dirname(dirname(__DIR__))).'/data/user/'.$data['id'].'.json';
       if(!file_exists($json_url)){
            fopen($json_url,"w");
        }
        
        $rs = file_put_contents($json_url, json_encode($data['url']));
        return con::msg(1,'ok');
    }else{
        return con::msg(0,'error');
    }

   }

}
