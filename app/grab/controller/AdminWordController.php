<?php
namespace app\grab\controller;
use cmf\controller\AdminBaseController;
use app\facade\Connectother as con;
use think\facade\Cache;

/**
 * Class AdminSelectController   showdata展示页面的查询类  2019年10月10日11:10:47
 * @package app\grab\controller
 */
class AdminWordController extends AdminBaseController
{



    public function word_index(){
        $url = con::urls();
        $this->assign('url',$url);
        return $this->fetch();
    }

    public function index_data(){

        $data = input('param.');
        $data['time'] = $data['time']=="" ? '' : $this->cut_time($data['time']);
        $word = $this->sele_word($data);

        //array_filter  数组去空
        $souword = array_count_values (array_filter((array_column($word['data'],'wsouword'))));
        $utm_medium = array_count_values (array_filter((array_column($word['data'],'wutm_medium'))));
        $utm_content = array_count_values (array_filter((array_column($word['data'],'wutm_content'))));
        $utm_term = array_count_values (array_filter((array_column($word['data'],'wutm_term'))));
        // $utm_medium = array_column($word['data'],'utm_medium');
        // $utm_content = array_column($word['data'],'utm_content');
        // $utm_term = array_column($word['data'],'utm_term');

        $arr['souword']=$this->ex_name($souword);
        $arr['utm_medium']=$this->ex_name($utm_medium);
        $arr['utm_content']=$this->ex_name($utm_content);
        $arr['utm_term']=$this->ex_name($utm_term);
        
        return con::msg(0,'ok','',$arr);
       

    }


    public function ex_name($data){
          $arr=[];
          $i=0;
        foreach ($data as $key => $value) {
           $arr[$i]['name']=$key;
           $arr[$i]['num']=(string)$value;
           $i+=1;
        }
        return $arr;
    }


    //查询的展示的词数据
    public function sele_word($data){
        //什么都没选择none
        if($data['app_url']=='0' && $data['time']==''){
            $arr['data'] = con::con('wxshow')
                ->whereTime('wtime', 'today')
                ->field('wid,wsouword,wutm_medium,wutm_content,wutm_term')
                ->order('wid desc')
                ->select();
            return $arr;
            //only url
        }elseif ($data['app_url']!='0' && $data['time']=='') {

            $arr['data'] = con::con('wxshow')
                ->whereTime('wtime', 'today')
                ->where('wlocation',$data['app_url'])
                ->field('wid,wsouword,wutm_medium,wutm_content,wutm_term')
                ->order('wid desc')
                ->select();

            return $arr;
            //only time
        }elseif ($data['app_url']=='0' && $data['time'] !='') {

            $arr['data'] = con::con('wxshow')
                ->where('wtime','>',$data['time']['Btime'])
                ->where('wtime','<',$data['time']['Etime'])
                ->field('wid,wsouword,wutm_medium,wutm_content,wutm_term')
                ->order('wid desc')
                ->select();

            return $arr;
            //choose all
        }else{
            $arr['data'] = con::con('wxshow')
                ->where('wlocation',$data['app_url'])
                ->where('wtime','>',$data['time']['Btime'])
                ->where('wtime','<',$data['time']['Etime'])
                ->field('wid,wsouword,wutm_medium,wutm_content,wutm_term')
                ->order('wid desc')
                ->select();
            return $arr;
        }

    }
    ####






#################################################
#落地词
#################################################

    public function word_floor(){
        $url = con::urls();
        $this->assign('url',$url);
        return $this->fetch();
    }

    public function floor_data(){

        $data = input('param.');
        $data['time'] = $data['time']=="" ? '' : $this->cut_time($data['time']);
        $word = $this->sele_floorword($data);

        //array_filter  数组去空
        $souword = array_count_values (array_filter((array_column($word['data'],'souword'))));
        $utm_medium = array_count_values (array_filter((array_column($word['data'],'utm_medium'))));
        $utm_content = array_count_values (array_filter((array_column($word['data'],'utm_content'))));
        $utm_term = array_count_values (array_filter((array_column($word['data'],'utm_term'))));
        // $utm_medium = array_column($word['data'],'utm_medium');
        // $utm_content = array_column($word['data'],'utm_content');
        // $utm_term = array_column($word['data'],'utm_term');

        $arr['souword']=$this->ex_name($souword);
        $arr['utm_medium']=$this->ex_name($utm_medium);
        $arr['utm_content']=$this->ex_name($utm_content);
        $arr['utm_term']=$this->ex_name($utm_term);

        return con::msg(0,'ok','',$arr);


    }





    //查询的展示的词数据
    public function sele_floorword($data){
        //什么都没选择none
        if($data['app_url']=='0' && $data['time']==''){
            $arr['data'] = con::con('allshow')
                ->whereTime('time', 'today')
                ->field('id,souword,utm_medium,utm_content,utm_term')
                ->order('id desc')
                ->select();
            return $arr;
            //only url
        }elseif ($data['app_url']!='0' && $data['time']=='') {

            $arr['data'] = con::con('allshow')
                ->whereTime('time', 'today')
                ->where('location',$data['app_url'])
                ->field('id,souword,utm_medium,utm_content,utm_term')
                ->order('id desc')
                ->select();

            return $arr;
            //only time
        }elseif ($data['app_url']=='0' && $data['time'] !='') {

            $arr['data'] = con::con('allshow')
                ->where('time','>',$data['time']['Btime'])
                ->where('time','<',$data['time']['Etime'])
                ->field('id,souword,utm_medium,utm_content,utm_term')
                ->order('id desc')
                ->select();

            return $arr;
            //choose all
        }else{
            $arr['data'] = con::con('allshow')
                ->where('location',$data['app_url'])
                ->where('time','>',$data['time']['Btime'])
                ->where('time','<',$data['time']['Etime'])
                ->field('id,souword,utm_medium,utm_content,utm_term')
                ->order('id desc')
                ->select();
            return $arr;
        }

    }




##########################################
#
##################################


    //转化率conversion_rate
    public function conversion_rate(){

        if(!request()->isPost()){

            return $this->fetch(); 
        }

        $input = input('param.');
        $url = con::urls();
        $arr=[];
        if($input["time"]==""){
            foreach ($url  as $key => $value) {
                $data = con::con('allshow')
                        ->whereTime('time','today')
                        ->where('location',$value)
                        ->count();
                $arr[$key]['url']=$value;
                $arr[$key]['flood_wx']=$data;
                $arr[$key]['show_wx']=0;
                $arr[$key]['conversion_rate']=0;
            }  
         }else{
            $times = $this->cut_time($input["time"]);
            foreach ($url  as $key => $value) {
                $data = con::con('allshow')
                        ->where('time','>',$times['Btime'])
                        ->where('time','<',$times['Etime'])
                        ->where('location',$value)
                        ->count();
                $arr[$key]['url']=$value;
                $arr[$key]['flood_wx']=$data;
                $arr[$key]['show_wx']=0;
                $arr[$key]['conversion_rate']=0;
            }
         }
          return con::msg('0','ok','',$arr);
    }

    public function conversion_click(){
        $input = input('param.');
        
        if($input['times']==""){
            $data = con::con('wxshow')
                ->whereTime('wtime','today')
                ->where('wlocation',$input['url'])
                ->count();
        }else{
            $times =$this->cut_time($input['times']);
            $data = con::con('wxshow')
                ->where('wtime','>',$times['Btime'])
                ->where('wtime','<',$times['Etime'])
                ->where('wlocation',$input['url'])
                ->count();
        }
       
        return con::msg('0','ok','',$data);
    }


    public function conversion_click_all(){
        $input = input('param.');
        
        if($input['times']==""){
            $data['wxshow'] = con::con('wxshow')
                    ->whereTime('wtime','today')
                    ->where('wlocation',$input['url'])
                    ->count();
            $data['allshow'] = con::con('allshow')
                    ->whereTime('time','today')
                    ->where('location',$input['url'])
                    ->count();
        }else{
            $times = $this->cut_time($input['times']);
            $data['wxshow'] = con::con('wxshow')
                ->where('wtime','>',$times['Btime'])
                ->where('wtime','<',$times['Etime'])
                ->where('wlocation',$input['url'])
                ->count();
            $data['allshow'] = con::con('allshow')
                ->where('time','>',$times['Btime'])
                ->where('time','<',$times['Etime'])
                ->where('location',$input['url'])
                ->count();
        }
        return con::msg('0','ok','',$data);
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


   

}
