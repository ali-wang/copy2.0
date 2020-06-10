<?php
namespace app\grab\controller;
use cmf\controller\AdminBaseController;
use think\Loader;
use app\facade\Connectother as con;
use think\facade\Cache;

require dirname(dirname(dirname(__DIR__))).'/public/static/PHPExcel/PHPExcel.php';
/**
 * Class AdminSelectDownloaderController   查询下载
 * @package app\grab\controller  根据输入条件去查询下载数据
 */
class AdminSelectDownloaderController extends AdminBaseController
{

    public function selectdownloader_index(){

        $url = con::urls();
        $this->assign('url',$url);
        return $this->fetch();

    }


    public function select(){
        $data = input('param.');
        // var_dump($data);
        $data['time'] = $data['time']=="" ? '' : $this->cut_time($data['time']);
        $arr = $this->sele_num($data); 
        $arr['time']=date(('Y-m-d_H:i:s'),time());
        // var_dump($arr);
        return con::msg(0,'ok', $arr['count'],$arr);
    }

    public function download(){


    }

    //查找出需要导出的数据
    public function DatasToExcel(){
        $data = input('param.');
        $data['time'] = $data['time']=="" ? '' : $this->cut_time($data['time']);
        $arr = $this->sele_data($data);
        return $arr;
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






     //5、判断选择类型
    public function sele_num($data){

        //什么都没选择none
       if($data['app_url']=='0' && $data['time']==''){
           $count = ['count' => con::con('allshow')->whereTime('time','today')->count() ];

           $arr['data'] = [
            'url'=>'全部',
            'chose_time'=>'今天'
           ];
            $arr = array_merge($arr,$count);
        return $arr;
        //only url
       }elseif ($data['app_url']!='0' && $data['time']=='') {
           $count =['count' => con::con('allshow')->whereTime('time','today')->where('location',$data['app_url'])->count()];
           $arr['data'] = [
            'url'=>$data['app_url'],
            'chose_time'=>'今天'
           ];
           $arr = array_merge($arr,$count);
         return $arr;
         //only time
       }elseif ($data['app_url']=='0' && $data['time'] !='') {
           $count =['count' => con::con('allshow')
               ->where('time','>',$data['time']['Btime'])
               ->where('time','<',$data['time']['Etime'])
               ->count()];
          $arr['data'] = [
            'url'=>$data['app_url'],
            'chose_time'=>date('Y-m-d H-i',$data['time']['Btime']).' 至 '.date('Y-m-d H-i',$data['time']['Etime'])
           ];
           $arr = array_merge($arr,$count);
          return $arr;
          //choose all
       }else{
           $count =['count' => con::con('allshow')
               ->where('location',$data['app_url'])
               ->where('time','>',$data['time']['Btime'])
               ->where('time','<',$data['time']['Etime'])
               ->count()];
          $arr['data'] = [
            'url'=>$data['app_url'],
            'chose_time'=>date('Y-m-d H-i',$data['time']['Btime']).' 至 '.date('Y-m-d H-i',$data['time']['Etime'])
           ];
           $arr = array_merge($arr,$count);
        return $arr;
       }

    }


    //需要下载的数据
    public function sele_data($data){

        //什么都没选择none
        if($data['app_url']=='0' && $data['time']==''){
            $arr['data'] = con::con('allshow')
                ->whereTime('time', 'today')
                ->order('id desc')
                ->select();
            return $arr;
            //only url
        }elseif ($data['app_url']!='0' && $data['time']=='') {

            $arr['data'] = con::con('allshow')
                ->whereTime('time', 'today')
                ->where('location',$data['app_url'])
                ->order('id desc')
                ->select();

            return $arr;
            //only time
        }elseif ($data['app_url']=='0' && $data['time'] !='') {

            $arr['data'] = con::con('allshow')
                ->where('time','>',$data['time']['Btime'])
                ->where('time','<',$data['time']['Etime'])
                ->order('id desc')
                ->select();

            return $arr;
            //choose all
        }else{
            $arr['data'] = con::con('allshow')
                ->where('location',$data['app_url'])
                ->where('time','>',$data['time']['Btime'])
                ->where('time','<',$data['time']['Etime'])
                ->order('id desc')
                ->select();
            return $arr;
        }

    }
    ####

    public function ExportExcel(){
        $datas = $this->DatasToExcel();//将需要导出的数据转换成数组格式
//        var_dump($datas);
        //此处需要自己根据自己的数据进行修改

        for ($i=0; $i < count($datas['data']) ; $i++) {//拼接数据，形成新的数组，用来装新导出的数据
            $data[$i]['id'] = $datas['data'][$i]["id"];
            $data[$i]['location'] = $datas['data'][$i]["location"];
            $data[$i]['souword'] = $datas['data'][$i]["souword"];
            $data[$i]['copy_content'] = $datas['data'][$i]["copy_content"];
            $data[$i]['equipment'] = $datas['data'][$i]["equipment"];
            $data[$i]['sourceType']=$datas['data'][$i]["sourceType"];

            if($datas['data'][$i]["user_type"]=='1'){
                $data[$i]['user_type'] ="长按复制";
            }elseif ($datas['data'][$i]["user_type"]=='2'){
                $data[$i]['user_type'] ="点击复制";
            }else{
                $data[$i]['user_type'] ='--';
            }
            //$data[$i]['user_type'] = $datas['data'][$i]["user_type"];

            $data[$i]['user_ip'] = $datas['data'][$i]["user_ip"];
            $data[$i]['utm_medium'] = $datas['data'][$i]["utm_medium"];
            $data[$i]['utm_content'] = $datas['data'][$i]["utm_content"];
            $data[$i]['utm_term'] = $datas['data'][$i]["utm_term"];
            $data[$i]['region'] = $datas['data'][$i]["region"];
            $data[$i]['city'] = $datas['data'][$i]["city"];
            $data[$i]['time'] = date("Y-m-d H:i:s",$datas['data'][$i]["time"]);
            $data[$i]['times'] =  date("H",$datas['data'][$i]["time"]);

        }
        // var_dump($data);

        try {
            //引用类包   不需要修改
//            Loader::import('PHPExcel.PHPExcel');
//            Loader::import('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');
           // require_once $_SERVER['DOCUMENT_ROOT'].'/static/PHPExcel/PHPExcel.php';
           // require_once $_SERVER['DOCUMENT_ROOT'].'/static/PHPExcel/PHPExcel/IOFactory.php';

            // require_once '/vendor/PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory';
//            vendor('PHPExcel.PHPExcel');
//            vendor('PHPExcel.PHPExcel.IOFactory.PHPExcel_IOFactory');
            //实例化类包 不用改
            $objPhpExcel=new \PHPExcel();


            //所有单元格进行垂直和水平居中设置  不用改
            $objPhpExcel ->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPhpExcel->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);


            /*
             设置表头标题，这里根据自己实际数据的需求写
            */
            $rowVal = array(
                0=>'编号',
                1=>'链接',
                2=>'搜索词',
                3=>'复制内容',
                4=>'平台',
                5=>'设备',
                6=>'操作类型',
                7=>'访问ip',
                8=>'计划',
                9=>'单元',
                10=>'关键字',
                11=>'省',
                12=>'市',
                13=>'时间',
                14=>'小时'
            );



            //设置表头的样式，你别管，这里不用你改，照抄。
            foreach ($rowVal as $k=>$r){
                $objPhpExcel
                    ->getActiveSheet()
                    ->getStyleByColumnAndRow($k,1)
                    ->getFont()->setBold(true);//字体加粗
                $objPhpExcel
                    ->getActiveSheet()
                    ->getStyleByColumnAndRow($k,1)
                    ->getAlignment()
                    ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//文字居中
                $objPhpExcel
                    ->getActiveSheet()
                    ->setCellValueByColumnAndRow($k,1,$r);

            }

            //设置当前的sheet索引 用于后续内容操作  这里也不用你改，抄吧
            $objPhpExcel->setActiveSheetIndex(0);
            $objActSheet=$objPhpExcel->getActiveSheet();

            //设置表格的宽度  根据情况修改
            $objActSheet->getColumnDimension('A')->setWidth(10);//编号
            $objActSheet->getColumnDimension('B')->setWidth(30);//仓库名
            $objActSheet->getColumnDimension('C')->setWidth(30);//创建时间
            $objActSheet->getColumnDimension('D')->setWidth(30);//最近的修改时间
            $objActSheet->getColumnDimension('E')->setWidth(10);//状态
            $objActSheet->getColumnDimension('F')->setWidth(20);//有无货架
            $objActSheet->getColumnDimension('G')->setWidth(20);//创建ip
            $objActSheet->getColumnDimension('H')->setWidth(50);//备注
            $objActSheet->getColumnDimension('I')->setWidth(50);//备注
            $objActSheet->getColumnDimension('J')->setWidth(50);//备注
            $objActSheet->getColumnDimension('K')->setWidth(50);//备注
            $objActSheet->getColumnDimension('L')->setWidth(50);//备注
            $objActSheet->getColumnDimension('M')->setWidth(50);//备注
            $objActSheet->getColumnDimension('N')->setWidth(50);//备注
            $objActSheet->getColumnDimension('O')->setWidth(50);//备注

            /*
           设置Excel表的名称  别抄 自己写
            */
            $title="数据总表";//
            $objActSheet->setTitle($title);

            //设置单元格内容
            //var_dump($data);die;
            foreach($data  as $k => $v)
            {
                $num=$k+2;
                $objPhpExcel ->setActiveSheetIndex(0)
                    //Excel的第A列，id是你查出数组的键值，下面以此类推

                    /*这里你别抄，我们不一样，$v['id']这些是你数据库中拿出来的数据字段  修改成自己的
                    //*/
                    ->setCellValue('A'.$num, $v['id'])/*编号*/
                    ->setCellValue('B'.$num, $v['location'])/*仓库名*/
                    ->setCellValue('C'.$num, $v['souword'])/*创建时间*/
                    ->setCellValue('D'.$num, $v['copy_content'])/*最近的修改时间*/
                    ->setCellValue('E'.$num, $v['sourceType'])/*状态*/
                    ->setCellValue('F'.$num, $v['equipment'])/*有无货架*/
                    ->setCellValue('G'.$num, $v['user_type'])/*创建ip*/
                    ->setCellValue('H'.$num, $v['user_ip'])/*创建ip*/
                    ->setCellValue('I'.$num, $v['utm_medium'])/*创建ip*/
                    ->setCellValue('J'.$num, $v['utm_content'])/*创建ip*/
                    ->setCellValue('K'.$num, $v['utm_term'])/*创建ip*/
                    ->setCellValue('L'.$num, $v['region'])/*创建ip*/
                    ->setCellValue('M'.$num, $v['city'])/*创建ip*/
                    ->setCellValue('N'.$num, $v['time'])/*创建ip*/
                    ->setCellValue('O'.$num, $v['times']);/*创建ip*/
                }

                $name=date('Y-m-d-H-i');//设置文件名

                //*抄吧*/
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Transfer-Encoding:utf-8");
                header("Pragma: no-cache");
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$title.'_'.urlencode($name).'.xls"');
                header('Cache-Control: max-age=0');
                $objWriter = \PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel5');
                $objWriter->save('php://output');
                // return $objWriter;
        } catch (Exception $e) {
            $this->error('操作异常');
        }

    }


    ####

}
