<?php
namespace app\weixin\logic;
use cmf\controller\HomeBaseController;
use app\weixin\model\WxNumber;
use think\facade\Cache;
class CdnwxLogic extends HomeBaseController
{

    //查询wx
    public function sqlwx($uid,$gid){
        $wxnumber =  new WxNumber();
        $wx = $wxnumber->where('uid',$uid)->where('gid',$gid)->field('id,number as wx,name,imgurl')->select()->toArray();
        if ($wx){
            return $this->msg('200','success',$wx);
        }else{
            return $this->msg('202','error','error');
        }
    }


    //使用缓存redis


    public function rediswx($uid,$gid){
        $key = $uid.'_'.$gid;
       $flag = Cache::store('redis')->has($key);
       if ($flag){
           $wx = Cache::store('redis')->get($key);
       }else{
           $wxnumber =  new WxNumber();
           $wx = $wxnumber->where('uid',$uid)
               ->where('gid',$gid)
               ->field('id,number as wx,name,imgurl')
               ->select()->toArray();

            Cache::store('redis')->set($key,$wx,null);
       }
       return $this->msg('200','success',$wx);
    }



    public function msg($code,$msg,$data=""){
        $arr['code'] =$code;
        $arr['msg'] =$msg;
        $arr['data'] =$data;
        return json($arr);

    }
}