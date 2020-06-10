<?php
namespace app\grab\controller;
use cmf\controller\AdminBaseController;
use app\facade\Connectother as con;
use think\facade\Cache;

/**
 * Class AdminColurlsController 获取url
 * @package app\grab\controller
 */
class AdminColurlsController extends AdminBaseController
{

    /**
     * @return mixed url接口检测
     */
    public function url_api(){

        //获取缓存数据
        $data = Cache::get('wx_alldata');

        //获取url列值
        $url_all =array_column($data,'location');

        //获取不重复的值
        $url = array_unique($url_all);
        return con::msg(0,'ok',0,$url);

    }

    /**
     * @return mixed url 页面渲染
     */
    
    public function urls(){

        //获取缓存数据
        $data = Cache::get('wx_alldata');

        //获取url列值
        $url_all =array_column($data,'location');

        //获取不重复的值
        $url = array_unique($url_all);
        // var_dump($url);
        return $url;
       //  $this->assign('urls','0');

       // return $this->fetch('admin_showdata/showdata');

    }





}
