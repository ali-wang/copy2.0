<?php
namespace app\grab\controller;
use cmf\controller\AdminBaseController;
use app\facade\Connectother as con;
use think\facade\Cache;

/**
 * Class AdminDeledataController  删除类   2019年10月10日11:10:55
 * @package app\grab\controller
 */

class AdminDeledataController extends AdminBaseController
{

    //删除数据
    public function showdata_del(){

        if(!(request()->isPost())){
            return con::msg(0,0);
        }
        $data = input('param.');
        //删除指定数据
		$wxdata1 = con::con('allshow')->where('id',$data['id'])->find();
		$dele =  con::con('wxshow')->where('wuser_ip',$wxdata1['user_ip'])->delete();
        $wxdata = con::con('allshow')->where('id',$data['id'])->delete();

        if($wxdata){

//            //更新缓存
//            $cach_data =Cache::pull('wx_alldata');
//            $wx_data = con::arr_delone($cach_data,$data['id']);
//            Cache::tag('wx_all')->set('wx_alldata',$wx_data);

            return con::msg(0,1);

        }else{
            return con::msg(0,0);
        }
    }





}
