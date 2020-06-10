<?php
namespace app\weixin\controller;
use cmf\controller\HomeBaseController;
use app\weixin\logic\CdnwxLogic;
use think\facade\Request;
use think\facade\Cache;
use think\cache\driver\Redis;

// 指定允许被访问的域名
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:*');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');

class CdnwxController extends HomeBaseController
{

    protected $CdnwxLogic;

    public function __construct()
    {

        parent::__construct();
        $this->CdnwxLogic = new CdnwxLogic();

    }

    /**
     *常规调用sql查询微信
     * http://127.0.1.0/weixin/cdnwx/cdnwx?uid=1&gid=20
     * @param $uid 用户id
     * @param $gid 组id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function cdnwx(){
        return $this->CdnwxLogic->sqlwx(Request::param('uid'),Request::param('gid'));
    }

    public function rediswx(){
        return $this->CdnwxLogic->rediswx(Request::param('uid'),Request::param('gid'));
    }

    //127.0.1.0/weixin/cdnwx/retest
    public function retest(){

//        phpinfo();

//        Cache::store('redis')->inc('cs');
//        var_dump(Cache::store('redis')->get('cs'));
//        return 123;

//        $redis = new Redis();
//        $redis->inc('cs');
//        var_dump($redis->get('cs'));


      return  $this->CdnwxLogic->rediswx('1','20');
    }

    public function msg($code,$msg,$data=""){
        $arr['code'] =$code;
        $arr['msg'] =$msg;
        $arr['data'] =$data;
        return json($arr);

    }
}