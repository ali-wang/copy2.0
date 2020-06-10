<?php
namespace app\grab\controller;
use think\Controller;
use cmf\controller\HomeBaseController;
use app\facade\Connectother as con;
use app\grab\model\UserModel as User;

class IndexController extends HomeBaseController
{
    public function index()
    {
        return $this->fetch(':index');
    }

    public function ws()
    {
        return $this->fetch(':ws');
    }

    #####
     public function addurl(){
    	//浏览次数统计
   		$request = request();
		$dates = $request->param();//获取所有参数，最全
		$equipment = $_SERVER["HTTP_USER_AGENT"];//获取设备
		$userip =$request->ip();//获取ip
		// var_dump($dates);

		//接收数据
		$date1 = [ 
		  "location"     => $dates['kw_url'],//落地页链接
		  "souword"      => $dates['kw_ref'],//来源连接
		  "time"         => $dates['v'],//时间
		  "userip"       => $userip
		];
		

		// $allshow=new con();
		//将页面链接转换成数组
		$url_ary = con::all_url($date1['location']);

		//******************************判断搜索词开始***********************************************
		$souword =con::souword($date1['souword']);//判断搜索词
		// if( $souword == ""){
				
		// 		echo "no souword";	
		// 		die;
		// 	}

		//******************************判断搜索词结束***********************************************
		// var_dump($url_ary);
		//
		//******************************检测域名是否绑定开始***********************************************
		//后期连接数据库，运营缓冲
		 $json_url=dirname(dirname(dirname(__DIR__))).'/data/user/'.$dates['kw_sign_id'].'.json';//文件名称和路径
			                    // 写入文件
        if(!file_exists($json_url)){
            fopen($json_url,"w");
        }

		 $rs = file_get_contents($json_url);
		  $host = json_decode($rs);
		 // $host=explode(",",str_replace('"','',$rs)); 
				
		$shost =in_array($url_ary['host'], $host);

		// var_dump($host);

		if(!$shost){
			exit;
		}
		//*******************************检测域名是否绑定结束**********************************************
		
		//处理后的落地页
		$urlpaths = $url_ary['scheme']."://".$url_ary['host'].$url_ary['path'];
		
		// 即将单元，词，计划转换成数组
		// 	存在query				
			if(isset($url_ary['query'])){
				$ary = con::convertUrlQuery($url_ary['query']);
				//将单元词和计划解码
				$urldecode_ary = con::urldecode($ary);
				}else{
					$urldecode_ary =  [
									  'utm_medium'=> '',					 
									  'utm_content'=> '',	 
									  'utm_term'=>''
									  ];
					}


			// var_dump($urldecode_ary);

			//***************************判断搜索词和单元计划是否存在一个*********************
				if(($souword=="")&&($urldecode_ary['utm_medium']=="")&&($urldecode_ary['utm_content']=="")&&($urldecode_ary['utm_term']==""))
				{
						// echo "no souword or utm_medium  utm_content utm_term";	
						//die;
				}
			//***************************判断搜索词和单元计划是否存在一个*********************
			
			$getip =con::getip($userip);
			$date4 = [
					'wsign_id'=>$dates['kw_sign_id'],
					'wsouword'=>$souword,
					'wtime'=>time(),
					'wlocation'=>$urlpaths,
					
					//来源
                    'wform'=>$date1['souword'],
                    'wregion' =>$getip["province"],
                    'wcity' =>$getip["city"],
					'wequipment' =>con::deviceType($equipment),
                    'wsourcetype' =>con::sourceType($date1['souword']),//平台
					
					'wutm_medium'=> $urldecode_ary['utm_medium'],					 
					'wutm_content'=> $urldecode_ary['utm_content'],	 
				    'wutm_term'=>$urldecode_ary['utm_term'],
				    'wuser_ip'=>$date1['userip']
				];

		// var_dump($date4);

		//数据保存
		$wxshow=con::waicon('wxshow')->data($date4)->insert();
		// $wxshow->data($date4);
		// $wxshow->save();
		var_dump($wxshow);

    }





     public function addcopy(){
     	//复制或点击统计
     	$request = request();
		$dates = $request->param();
   		 $params = $_SERVER["HTTP_USER_AGENT"];

   		 //var_dump($params);
		 $userip =$request->ip();

		 if (preg_match("/([\x81-\xfe][\x40-\xfe])/", $dates['c'], $match)) {
			    return '含有汉字';
			}
			
		 //排除特殊符号
		 if(preg_match("/\#|\-|\:|\\$/", $dates['c'], $match)){
             return '特殊符号';
         }
		 
		 //排除误点数据
		 if(strlen($dates['c']) < 4){
            return '无用数据';
        }
		
		//排除undefined
		 if(($dates['c']) == 'undefined'){
            return 'undefined';
        }

   		 $date2 = [
				"user_type"     => $dates['type'],
				 "location"     => $dates['kw_url'],
				 "copy_content" => $dates['c'],
				 "souword"      => $dates['kw_ref'],
				 "time"         => $dates['v'],
				 "equipment"    => $params,
				 "userip"       => $userip
			];
		
		$user_url = $date2['location'];


		//检测域名绑定
			$allshow=new con();

			$souword =con::souword($date2['souword']);	//判断是否存在搜索词
			// if( $souword == ""){
			// 	echo "没有搜索词";	
			// 	die;
			// }

			$url_ary = con::all_url($user_url);
			 $json_url=dirname(dirname(dirname(__DIR__))).'/data/user/'.$dates['kw_sign_id'].'.json';//
				                    // 写入文件
		        if(!file_exists($json_url)){
		            fopen($json_url,"w");
		        }
				$rs = file_get_contents($json_url);
				$host=json_decode($rs); 
				 // $host=explode(",",str_replace('"','',$rs)); 
				 $shost =in_array($url_ary['host'], $host);
					if(!$shost){
						exit;
					}

		//落地页连接
			$urlpath = $url_ary['scheme']."://".$url_ary['host'].$url_ary['path'];

	
			//
			//
			//即将单元，词，计划转换成数组
			//存在query				
			if(isset($url_ary['query'])){
				$ary = con::convertUrlQuery($url_ary['query']);
				//将单元词和计划解码
				$urldecode_ary = con::urldecode($ary);
				}else{
					$urldecode_ary =  [
									  'utm_medium'=> '',					 
									  'utm_content'=> '',	 
									  'utm_term'=>''
									  ];
			}

			//***************************判断搜索词和单元计划是否存在一个*********************
				if(($souword=="")&&($urldecode_ary['utm_medium']=="")&&($urldecode_ary['utm_content']=="")&&($urldecode_ary['utm_term']==""))
				{
						echo "no souword or utm_medium  utm_content utm_term";	
						//die;
				}
			//***************************判断搜索词和单元计划是否存在一个*********************

			//判断设备
			$equipment = con::deviceType($date2['equipment']);
			// var_dump($equipment);

			//判断ip
			$getip =con::getip($date2['userip']);
			//$test = '123.139.93.145';
			//$getip =$allshow->getip($test);
			
			 // var_dump($getip);

			// if($getip =="0"){
			// 	$getip= [
			// 		$getip['province'] => '--',
			// 		$getip['city']   => '--'
			// 	];
			// }
				 

			$sourceType =con::sourceType($date2['souword']);//平台
			
			
			//判断停留时间
            //查询25分钟内的ip  60*25 = 1500
            $begin_time = $date2['time']-1500;
        
            $wxstop = con::connect()->table('wxshow')->where('wsign_id',$dates['kw_sign_id'])
                ->whereTime('wtime','between',[$begin_time,$date2['time']])
                ->where('wuser_ip',$date2['userip'])
                ->find();
            if($wxstop){
                $stoptime = $date2['time'] - $wxstop['wtime'];
            }else{
                $stoptime="--";
            }
            
					
				// dump($sourceType);
				$alldate = [
						"location" => $urlpath,
						'souword' => $souword,
						'copy_content'	=> $date2['copy_content'],
						'sourceType'  =>$sourceType,
						'equipment' =>$equipment,
						'user_type' => $date2['user_type'],
						'user_ip' =>$date2['userip'],
						'utm_medium'=> $urldecode_ary['utm_medium'],			 
						'utm_content'=> $urldecode_ary['utm_content'], 
						'utm_term'=>$urldecode_ary['utm_term'],
						'region' =>$getip["province"],
						'city' =>$getip["city"],
						'sign_id'=>$dates['kw_sign_id'],
						'time' =>time(),
						'stop'=>$stoptime
					];

			// var_dump($alldate);

			$flag = con::waicon('allshow')->data($alldate)->insert();
			var_dump($flag);
			// con::save();

    }
    ######

}
