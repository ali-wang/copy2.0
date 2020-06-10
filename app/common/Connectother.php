<?php
namespace app\common;
use think\db;
use think\facade\Cache;
use think\Controller;
use app\grab\model\UserModel as User;
class Connectother extends controller
{
    public function hello($name="abc"){
        return 'hello'.$name;
    }

    /*
    * 链接其它数据库
    * */
    public function connect(){
        $db = Db::connect([
            'type'            => 'mysql',
            'hostname'        => 'localhost',
            'database'        => 'mygrab',
            'username'        => 'root',
            'password'        => 'root',
            'hostport'        => '3306',
            'charset'         => 'utf8',

        ]);
        return $db;
    }

    /**
    创建链接方法
     */

    public function con($dataname){
        $id=  cmf_get_current_admin_id();

        if($dataname =='wxshow'){
            $data =$this->connect()->table($dataname)->where('wsign_id',$id);
        }elseif ($dataname =='allshow') {
            $data =$this->connect()->table($dataname)->where('sign_id',$id);
        }

        $user = new User();

        $use_data = $user->where('id',$id)->cache('300')->find();
        $time = time();
        if($use_data['etime'] < $time){

           return $this->error('时间已经到期！请续期~~',url('admin_codeinstall/index'),'',120);
        }

       
        return $data;
    }


     public function waicon($dataname){
        $data =$this->connect()->table($dataname);
        return $data;
    }

    public function msg($code="0",$msg='',$count='',$data=''){
        $arr=[
            'code'=>$code,
            'msg'=>$msg,
            'count'=>$count,
            'data'=>$data,
        ];
        return json($arr);

    }

    /**
     * @param $name 缓存的名称
     * @param string $data_time 数据保存时间
     * @param $data  需要缓存的数据
     * @param $col_id  id名称
     * @return mixed 返回缓存数据中最大id
     */
    public function check_cache($name,$data_time,$data,$col_id){

        //判断该名称是否存在
            //获取缓存

            $names = Cache::get($name);
            // 1、不存在，创建缓存。
            if(!$names){
                Cache::set($name,$data,$data_time);
            }else{
                $names = $data;
            }
            $max_id = $this->colmax($names,$col_id);
            return $max_id;
        //2、存在，获取之前缓存，检查最大id ==》获取最大id查询最新数据

        //3、将数据整合再次缓存

    }

    /**查询数据中某列的最大值
     * @param $data  【array】
     * @param $col_id 需要获取某列的最大值
     * @return mixed  数字，最大值
     */
    public function col_max($data, $col_id){
        $col = array_column($data,$col_id);
        $max_data = max($col);
        return $max_data;
    }


    /**
     * @param $cach_data  需要删除的数组
     * @param $id  需要删除的id
     * @return mixed 返回删除后的数组
     */
    public function arr_delone($cach_data, $id){
        foreach ($cach_data as $k=>$v){
            if($v['id']==$id){
                unset($cach_data[$k]);
            }
        }
        return $cach_data;
    }

    public function integration_data(){

    }


    /**
     * @return mixed url 页面渲染
     */
    
    public function urls(){

        //获取url列值
        // $data= $this->con('allshow')->whereTime('time','week')->column('location');
        $times = time();
        $data= $this->con('allshow')->whereTime('time', 'between', [($times-604800),$times])->column('location');
        //获取不重复的值
        $url = array_unique($data);
		$use =new User;
        $id=  cmf_get_current_admin_id();
        $use_data = $use->where('id',$id)->cache('180')->find();
        foreach ($url as $key => $value) {
            $host = parse_url($value);
           if(!in_array($host['host'],json_decode($use_data['domain']))){
                unset($url[$key]);
           }
        }
        return $url;
    }


    /**
     * @return mixed url 实时统计页面渲染
     */

    public function cnzzurls(){

        $times = time();
        $data= $this->con('wxshow')->whereTime('wtime', 'between', [($times-43200),$times])->column('wlocation');
        $url = array_unique($data);
        return $url;

    }


    
    ########
        public function getip($ip)
        {
            
				//注册你的ip解析库,我使用的是高德
                 //$ipurl="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
                 $ipurl="http://restapi.amap.com/v3/ip?key=b89be65800aa401397f15ce726419593&ip=".$ip;
                 
                 //try{
                   // $ip=json_decode(file_get_contents($ipurl));
                    $ip=json_decode(file_get_contents($ipurl),true);
                
                    //判断ip是否正确,如果不正确就输出--
                    if($ip['status'] =="0" || is_array($ip['adcode'])){


                             $ip['province'] = '--';
                             $ip['city']   = '--';  
                    }

                    
                     // var_dump($ip);
                     
                     return $ip;
                  
        }

        function sourceType($source)
            {//检测平台
                if(strpos($source, 'baidu.com')){
                        return '百度';
                    }else if(strpos($source, 'so.com')){
                        return '360';
                    }else if(strpos($source, 'sogou.com')){
                        return '搜狗';
                    }else if(strpos($source, 'sm.cn')){
                        return '神马';
                    }else if(strpos($source, 'sina.cn')){
                        return '新浪';
                    }else if(strpos($source, 'ifeng.com')){
                        return '凤凰';
                    }else if(strpos($source, 'qq.com')){
                        return '腾讯';
                    }else if($source ==''){
                        return '--';
                    }
                    else {                      
                            return '--';                        
                    }
            }




        //将页面链接转换成数组
        public function all_url($url)
        {
            
            $arr = parse_url($url);
            //dump($arr);
            return $arr; 

        }

        public function arrayUtil($arr,$key)
        {
                //检查数组中该键值对是否存在有值
                if(isset($arr[$key])){
                    return $arr[$key];
                }else{
                    return "";
                }
        }

        public function convertUrlQuery($query)

            {   //将url中的query转换成二位数组
                
                if(empty($query)){
                    return array();
                }
                $queryParts = explode('&', $query); 
                $params = array();
                foreach ($queryParts as $param) 
                { 
                    $item = explode('=', $param);
                    if(!isset($item[1])){
                        continue;
                    }else{
                        $params[$item[0]] = $item[1];
                    }
                } 
                
                return $params; 
            }

        public function urldecode($value)
        {
            //将词，单元，计划解码
            //
            if( isset($value['utm_medium']) && isset($value['utm_content']) && isset($value['utm_term']) ){
                $date = [

                         //"cid"         => $value['cid'],
                         "utm_medium"  => urldecode($value['utm_medium']),
                         "utm_content" => urldecode($value['utm_content']),
                         "utm_term"    => urldecode($value['utm_term']),            
                        ];
            }else{
                    $date = [

                         //"cid"         => $value['cid'],
                         "utm_medium"  => '',
                         "utm_content" => '',
                         "utm_term"    => '',           
                    ];
            }
            return $date;
        }   



        public   function deviceType($ua)
        {   //判断设备是否是
            $agent = strtolower($ua);

            $is_pc = (strpos($agent, 'windows nt')) ? true : false;

            $is_iphone = (strpos($agent, 'iphone')) ? true : false;

            $is_android = (strpos($agent, 'android')) ? true : false;

            $is_oppo = (strpos($agent, 'oppobrowser')) ? true : false;

            $is_ipad = (strpos($agent, 'ipad')) ? true : false;

            $is_mac = (strpos($agent, 'mac os')) ? true : false;

            $is_linux = (strpos($agent, 'linux')) ? true : false;

            if($is_pc){
                  return  'PC';

            }else if($is_iphone){
                  return  'iphone';

            }else if($is_android||$is_oppo){
                  return  'android';

            }else if($is_ipad){
                  return  'ipad';

            }else if($is_mac){
                  return  'PC';

            }else if($is_linux){

                  return 'PC';
            }else{
                  return 'other';
            }
        }




    //获取搜索词 
        public function souword($burl){
            //分解成数组
            $arr= parse_url($burl); 
            //将query分解成数组
            //$a = $this->arrayUtil($arr,'query');
            $arr_query2 = $this->convertUrlQuery($this->arrayUtil($arr,'query'));
            
            //exit;
            $wd="";
            if( $this->arrayUtil($arr_query2,'word')!=""){
                $wd=$arr_query2["word"];
            }else{
                $wd= $this->arrayUtil($arr_query2,'wd');
            }


            if(strstr($burl,"yz.m.sm.cn")||strstr($burl,"m.yz.sm.cn")||strstr($burl,"so.m.sm.cn")){
    
                    $wd=$arr_query2["q"];
                }

                if(strstr($burl,"m.sogou.com")||strstr($burl,"sogou.com")){
                    $wd=$arr_query2["keyword"];
                    if($wd==""){
                        $wd=$arr_query2["query"];
                    }
                }

                if(strstr($burl,"m.so.com")||strstr($burl,"so.com")){
                    
                    $wd=$arr_query2["q"];
                }


                // if($wd==""&&!empty($keyword)){
                //      $wd=$keyword;
                //  }

                    $wd=urldecode($wd);

                    if(strstr($wd, '%')){
                        $wd=urldecode($wd);
                    }

                    $wd=preg_replace("/[^\x{4e00}-\x{9fa5}^0-9^A-Z^a-z]+/u", '', $wd);
                    $wd=trim($wd);
                    if($wd == NULL){
                        return '';
                       
                    }else{
                        return $wd;
                    }
                    
                 
        }
    #####
    
}