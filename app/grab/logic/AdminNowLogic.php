<?php
namespace app\grab\logic;
use cmf\controller\AdminBaseController;
use app\facade\Connectother as con;
class AdminNowLogic extends AdminBaseController
{

    //获取当天的最新数据
    public function nowList($url){
        $id = cmf_get_current_admin_id();
        $now = con::con("wxshow")
            ->where('wsign_id',$id)
            ->whereTime('wtime', 'between', [time()-60*60*12, time()])
            ->order('wid', 'desc')
            ->select();

        foreach ($now as $k=>$v){
            if($v['wform']=="undefined"){
                $now[$k]['wform'] = "直接打开"   ;
            }
            $now[$k]['wtime'] = date("Y-m-d H:i:s",$v['wtime']);
        }

        return con::msg(0,'ok',count($now),$now);
    }

}