<?php
namespace app\grab\controller;
use cmf\controller\AdminBaseController;
use app\facade\Connectother as con;
use think\facade\Cache;

/**
 * Class AdminShowdataController   页面展示数据    2019年10月10日11:10:37
 * @package app\grab\controller
 */
class AdminShowdataController extends AdminBaseController
{

    public function showdata()
    {
        $url = con::urls();
        $this->assign('url',$url);
        return $this->fetch();
    }

    /**
     *主页展示请求数据
     */
    public function showdata_index(){

        $data = input('param.');
        $time = time()-86400;
		$time2 = time()-10800;
        //缓存总条数
        $count = con::con('allshow')->where('time','>=',$time)->limit('5000')->cache('key',60)->count();


        $alldata = con::con('allshow')
                ->where('time','>=',$time)
                ->order('id desc')
                ->page($data['page'],$data['limit'])
                ->select();



        //将时间戳转换为时间
        foreach ($alldata as $key => $value) {
           $alldata[$key]['time']=date('Y-m-d H:i:s',$value['time']);
		    //排除特殊符号
            if(preg_match("/\#|\-|\:|\\$|\n/",  $alldata[$key]['copy_content'],$match)){
                unset($alldata[$key]);
            }

        }


        return con::msg(0,'ok',($count),$alldata);


    }


}
