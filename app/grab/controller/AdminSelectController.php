<?php
namespace app\grab\controller;
use cmf\controller\AdminBaseController;
use app\facade\Connectother as con;
use think\facade\Cache;

/**
 * Class AdminSelectController   showdata展示页面的查询类  2019年10月10日11:10:47
 * @package app\grab\controller
 */
class AdminSelectController extends AdminBaseController
{



    /**
     * @return mixed
     */
    public function select_index(){

       if(request()->isPost()){
           $data = input('param.');
//            var_dump($data);
           //处理时间为空格的时候
           $data['time'] = $data['time']=="" ? '' : $this->cut_time($data['time']);

           //获取url
            $url = con::urls();
            $this->assign('url',$url);

            //获取用户id
           $id=  cmf_get_current_admin_id();
            //创建搜索记录缓存
//           var_dump($id);
           Cache::set($id,$data,300);

           $ids = Cache::get($id);
           $test = json_encode($ids);
           $this->assign('times', $ids);

           //测试
           $this->assign('times1',$test);
//       var_dump($ids);
           return $this->fetch();
       }else{
           $id=  cmf_get_current_admin_id();
           $ids = Cache::get($id);
            $data = input('param.');
            $data=array_merge($data,$ids);
//            var_dump($ids);
            $arr = $this->sele_type($data);


            foreach ($arr['data'] as $k=>$v){
                $arr['data'][$k]['time']=date("Y-m-d H:i:s",$v['time']);
				 ////排除特殊符号
     //           if(preg_match("/\#|\-|\:|\\$|\n/",  $arr['data'][$k]['copy_content'], $match)){
     //               unset($arr['data'][$k]);
     //           }
            }
           return con::msg(0,'ok',$arr['count'],$arr['data']);
       }
//        $id=  cmf_get_current_admin_id();
//        $ids = Cache::get($id);
//       var_dump($ids);


   }

 /**
     *主页展示请求数据
     */
    public function select_api($arr,$data){

//        $data = input('param.');
      
        //缓存总条数
        $count = count($arr);

        //查询指定条数，实现翻页功能
        $wxdata = array_slice($arr,$data['page']*$data['limit'],($data['limit']-1),true);

        //将时间戳转换为时间
        foreach ($wxdata as $key => $value) {

           $wxdata[$key]['time']=date('Y-m-d H:i:s',$value['time']);

        }

        return con::msg(0,'ok',($count-$data['limit']),$wxdata);


    }



   //将时间转换为时间戳
    public function cut_time($str){
//       $str = '2019-10-01 00:00:00 - 2019-10-04 00:00:00';
       //将字符串分割成两个数组
       $arr = explode(' - ',$str);
       //将时间转换为时间戳
        $arr_time = [
            'Btime'=>strtotime($arr['0']),
            'Etime'=>strtotime($arr['1'])
        ];
        return $arr_time;

    }


//前期页面浏览时间判定，先已停止使用
  public function stoptime($alldata,$wxdata){

        // $allwx = $alldata;
        // $wx = $wxdata;

        // foreach ($allwx as $key => $value) {
        //   foreach ($wx as $k => $v) {
        //      if($value['user_ip']==$v['wuser_ip']){
        //         $stop=$value['time']-$v['wtime'];
        //         if($stop>900){
        //             unset($wx[$k]);
        //             continue;
        //         }else{
        //             $alldata[$key]['stop'] = $stop;
        //             unset($allwx[$key]);
        //             unset($wx[$k]);
        //         }
                
        //      }
        //   }

        //   if(!isset($alldata[$key]['stop'])||$alldata[$key]['stop'] <=0 ){
        //         $alldata[$key]['stop']="--";
        //   }

        // }

        return $alldata;
  }


    //5、判断选择类型
    public function sele_type($data){

        //什么都没选择none
       if($data['app_url']=='0' && $data['time']==''){

           $arr['data'] = con::con('allshow')
               ->whereTime('time', 'today')
               ->page($data['page'],$data['limit'])
               ->order('id desc')
               ->select();

            $arr['data']= $this->stoptime($arr['data'],$wxdata);
            $arr['count']=con::con('allshow')->whereTime('time', 'today')->count();


        return $arr;
        //only url
       }elseif ($data['app_url']!='0' && $data['time']=='') {

           $arr['data'] = con::con('allshow')
               ->where('location',$data['app_url'])
               ->whereTime('time', 'today')
               ->order('id desc')
               ->page($data['page'],$data['limit'])
               ->select();

			$arr['count'] = con::con('allshow')->where('location',$data['app_url'])->whereTime('time', 'today')->count();

         return $arr;
         //only time
       }elseif ($data['app_url']=='0' && $data['time'] !='') {

           $arr['data'] = con::con('allshow')
               ->where('time','>',$data['time']['Btime'])
               ->where('time','<',$data['time']['Etime'])
               ->order('id desc')
               ->page($data['page'],$data['limit'])
               ->select();
               
            $arr['count'] =con::con('allshow')
               ->where('time','>',$data['time']['Btime'])
               ->where('time','<',$data['time']['Etime'])
               ->count();


          return $arr;

       }else{

			$arr['count'] = con::con('allshow')
               ->where('location',$data['app_url'])
               ->where('time','>',$data['time']['Btime'])
               ->where('time','<',$data['time']['Etime'])
               ->count();
           $arr['data'] = con::con('allshow')
               ->where('location',$data['app_url'])
               ->where('time','>',$data['time']['Btime'])
               ->where('time','<',$data['time']['Etime'])
               ->order('id desc')
               ->page($data['page'],$data['limit'])
               ->select();

        return $arr;
       }

    }


    /**
     * 微信号展示和复制次数
     */

    public function wx_times(){
       
      if(!request()->isPost()){
        //获取url
         $url = con::urls();
        $this->assign('url',$url);
        return $this->fetch();
      }

      $data = input('param.');
      $data['time'] = $data['time']=="" ? '' : $this->cut_time($data['time']);
      //获取数据
      $arr = $this->sele_wx($data);
     
      //将数据整合
      $arr_wx = array_column($arr['data'],'copy_content');
      $arr_wx_times = array_count_values($arr_wx);

      $arr_msg =[];
      $i = 0;
      
      foreach ($arr_wx_times as $key => $value) {
          
          $arr_msg[$i]['wx']=$key;
          $arr_msg[$i]['num']=$value;
          $i+=1;
      }

      
      return con::msg(0,'ok','',$arr_msg);
       
    }


      //需要查询的微信号数据
    public function sele_wx($data){
        //什么都没选择none
        if($data['app_url']=='0' && $data['time']==''){
            $arr['data'] = con::con('allshow')
                ->whereTime('time', 'today')
                ->field('id,location,copy_content,time')
                ->order('id desc')
                ->select();
            return $arr;
            //only url
        }elseif ($data['app_url']!='0' && $data['time']=='') {

            $arr['data'] = con::con('allshow')
                ->whereTime('time', 'today')
                ->where('location',$data['app_url'])
                ->field('id,location,copy_content,time')
                ->order('id desc')
                ->select();

            return $arr;
            //only time
        }elseif ($data['app_url']=='0' && $data['time'] !='') {

            $arr['data'] = con::con('allshow')
                ->where('time','>',$data['time']['Btime'])
                ->where('time','<',$data['time']['Etime'])
                ->field('id,location,copy_content,time')
                ->order('id desc')
                ->select();

            return $arr;
            //choose all
        }else{
            $arr['data'] = con::con('allshow')
                ->where('location',$data['app_url'])
                ->where('time','>',$data['time']['Btime'])
                ->where('time','<',$data['time']['Etime'])
                ->field('id,location,copy_content,time')
                ->order('id desc')
                ->select();
            return $arr;
        }

    }


     //查询操作次数
    public function operation_times($data){
        //什么都没选择none
        if($data['app_url']=='0' && $data['time']==''){
            $arr['wxshow'] = con::con('wxshow')
                ->whereTime('time', 'today')
                ->column('location')->count();

            $arr['allshow'] = con::con('allshow')
                ->whereTime('time', 'today')
                ->column('user_ip');
             
             $arr['allshow'] =count(array_unique($arr['allshow']));

            return $arr;
            //only url
        }elseif ($data['app_url']!='0' && $data['time']=='') {

            $arr['allshow'] = con::con('allshow')
                ->whereTime('time', 'today')
                ->where('location',$data['app_url'])
                ->column('user_ip');

            $arr['allshow'] =count(array_unique($arr['allshow']));

             $arr['wxshow'] = con::con('wxshow')
                ->whereTime('time', 'today')
                ->where('location',$data['app_url'])
                ->column('location')->count();

            return $arr;
            //only time
        }elseif ($data['app_url']=='0' && $data['time'] !='') {

            $arr['allshow'] = con::con('allshow')
                ->where('time','>',$data['time']['Btime'])
                ->where('time','<',$data['time']['Etime'])
                ->column('user_ip');

            $arr['allshow'] =count(array_unique($arr['allshow']));

             $arr['wxshow'] = con::con('wxshow')
                ->where('time','>',$data['time']['Btime'])
                ->where('time','<',$data['time']['Etime'])
                ->column('location')->count();

            return $arr;
            //choose all
        }else{
            $arr['allshow'] = con::con('allshow')
                ->where('location',$data['app_url'])
                ->where('time','>',$data['time']['Btime'])
                ->where('time','<',$data['time']['Etime'])
                ->column('user_ip');
            $arr['allshow'] =count(array_unique($arr['allshow']));

             $arr['wxshow'] = con::con('wxshow')
                ->where('location',$data['app_url'])
                ->where('time','>',$data['time']['Btime'])
                ->where('time','<',$data['time']['Etime'])
                ->count();
            
            return $arr;
        }

    }
    ####



    /**
     * 分时段展示微信号
     */

    /**
     * 1
     * [cut_times description]时间整点均分
     * @return [type] [description]
     */
    public function cut_times(){

        date_default_timezone_set("Asia/Shanghai");

        $now  = time();
        $h = date('Y-m-d H',$now);

        $t_str = strtotime($h.':00:00')+3600;
        $time_arr=[];
        for($i=0;$i<24;$i++){
          $time_arr[$i] = $t_str;
          $t_str-=3600;
        }
        // var_dump($time_arr);
         return $time_arr;

    }
    /**[wx_timesform description]
     * 根据url和时间查询对应的时间段微信次数
     * @return [type] [description]
     */
    public function wx_timesform(){

       if(!request()->isPost()){
        //获取url
         $url = con::urls();
        $this->assign('url',$url);
        return $this->fetch();
      }

      $data = input('param.');
      $data['time'] = $data['time']=="" ? '' : $this->cut_time($data['time']);
      //获取数据
      $arr = $this->sele_wx($data);

      $time = $this->cut_times();

      $wx_unique = array_unique(array_column($arr['data'],'copy_content'));
      // var_dump($wx_unique);
      
      $wx = [];
      //将数据划分24小时
        for($i=23;$i>0;$i--){
            foreach ($arr['data'] as $k=>$v) {
                if( $time[$i]<((int)$v['time']) && $time[$i-1]>((int)$v['time'])){
                    $wx[date('H:i',$time[$i]).'-'.date('H:i',$time[$i-1])][]=$v;
                    unset($arr['data'][$k]);
                }
            }

        }

      //将划分好的微信号，统计次数
        $wx_unm=[];
        foreach ($wx as $key => $value) {
         $wx_unm[$key]= array_count_values(array_column($value,'copy_content'));
        }

      //将统计好的微信号，按照时间和顺序
        $wx_end=[];
        foreach ($wx_unm as $key => $value) {
          foreach ($wx_unique as $k => $v) {
              foreach ($value as $ke => $val) {
                
                if($ke == $v){
                  $wx_end[$key][$v]=$val;
                  unset($wx_unm[$ke]);
                }else{
                  if(!isset($wx_end[$key][$v])){
                    $wx_end[$key][$v]=0;
                  }
                }
               
              }

          }
          
        }

    // var_dump($wx_end);
      

    return con::msg(0,'ok',$wx_unique,$wx_end);

    }


   

}
