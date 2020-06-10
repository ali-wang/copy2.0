<?php
namespace app\weixin\logic;
use cmf\controller\AdminBaseController;
use FormBuilder\Factory\Elm;
use app\weixin\model\WxGroup;
use app\weixin\model\WxNumber;
use FormBuilder\Response;
use think\Db;
use think\facade\Request;
use think\facade\Cache;

/**
 * 处理AdminWei控制器业务逻辑
 * Class AdminWeiLogic
 * @package app\weixin\logic
 */
class AdminWeiLogic extends AdminBaseController
{

    //创建分组
    public function creatGroup(){

       $action = 'saveGroup';
       $method = 'POST';
       $wxgroup = Elm::input('wxgroup', '微信分组名称')->required();
       $wxname = Elm::textarea('wxname', 'wx对应名称');
       $wxnum = Elm::textarea('wxnum', '展示微信号')->required();
//       $switch = Elm::switches('is_open', '是否开启')->activeText('开启')->inactiveText('关闭');
       $upload = Elm::uploadImage('images', '二维码',"imgSave","");
       //创建表单
       $form = Elm::createForm($action)->setMethod($method);
       //添加组件
       $form->setRule([$wxgroup, $wxname,$wxnum,$upload]);
       //生成表单页面
       echo $formHtml = $form->view();
    }

    /**
     * 首次保存组信息
     */
    public function saveGroup($arr){
        //获取当前用户id
        $userid = cmf_get_current_admin_id();
        $wxgroup = new WxGroup();
        //判断当前名称是否存在，名称不能重复
       $nowheav = $wxgroup->where("userid",$userid)->where("groupname",$arr['wxgroup'])->find();
        if($nowheav){
            echo Response::fail('该组已经存在')->getContent();
            return ;
        }
        $wxgroup->userid = $userid;
        $wxgroup->groupname = $arr['wxgroup'];
        $wxgroup->save();
        $grupid_now = $wxgroup->id;
        Cache::store('redis')->rm($userid.'_'.$grupid_now);
        $wxnumber = new WxNumber();
        //每次添加一个
        $res =  $wxnumber->save([
                    'uid'=>$userid,
                    'gid'=>$grupid_now,
                    'number'=>$arr['wxnum'],
                    'name'=>$arr['wxname'],
                    'imgurl'=>$arr['images'],
                     ]);
        echo $res? Response::success('数据提交成功')->getContent():Response::fail('数据提交失败')->getContent();

    }


    /**
     * 查询组名称、微信信息
     */
    public function selectWx(){
        $userid = cmf_get_current_admin_id();

        $dataWx = [];
        //获取全部分组。通过分组获取微信号
        $wxgroup = new WxGroup();
        $group = $wxgroup->where("userid",$userid)->order('id', 'desc')->select();
        $wxnum = new WxNumber();
        foreach ($group as $k=>$v){
          $wx = $wxnum->where('gid',$v['id'])->where('uid',$userid)->select()->toArray();
          $dataWx[$k]['groupName'] = $v['groupname'];
          $dataWx[$k]['groupId']= $v['id'];
          $dataWx[$k]['wxdata'] =$wx;
        }
        return $dataWx;
    }

    /**
     * 删除微信组及其微信号
     * @param $id
     * @return \think\response\Json
     */

    public function deleGroup($id){
        //删除两部分，number表  和group表

        $uid = cmf_get_current_admin_id();

        Db::startTrans();
        try{
            $wxnumber = new WxNumber();
            $wxnumber->where('uid',$uid)->where('gid',$id)->delete();
            $wxgroup = new WxGroup();
            $wxgroup->where('id',$id)->where('userid',$uid)->delete();
            // 提交事务
            Db::commit();
        }catch (\Exception $exception){
            // 回滚事务
            Db::rollback();
            return $this->msg('300',$exception->getMessage(),'');
        }

        Cache::store('redis')->rm($uid.'_'.$id);

        return $this->msg('200','分组删除成功','');
    }

    /**
     * @throws \FormBuilder\Exception\FormBuilderException
     * 创建添加微信表单
     */
    public function addWxForm($uid,$gid){
        $action = 'addWx';
        $method = 'POST';
        $wxuid = Elm::textarea('uid', 'uid')->value($uid)->hiddenStatus();
        $wxgid = Elm::textarea('gid', 'gid')->value($gid)->hiddenStatus();
        $wxname = Elm::textarea('wxname', 'wx对应名称')->maxlength(10);
        $wxnum = Elm::textarea('wxnum', '展示微信号')->maxlength(13)->required();
//       $switch = Elm::switches('is_open', '是否开启')->activeText('开启')->inactiveText('关闭');
        $upload = Elm::uploadImage('images', '二维码',"imgSave","");
        //创建表单
        $form = Elm::createForm($action)->setMethod($method);
        //添加组件
        $form->setRule([$wxname,$wxnum,$upload,$wxuid,$wxgid]);
        //生成表单页面
        echo $formHtml = $form->view();
    }


    /**
     * 保存微信数据
     * @param $arr
     */
    public function saveAddWx($arr){
       $wxnumber =  new WxNumber();

        $wxnumber->uid = $arr['uid'];
        $wxnumber->gid= $arr['gid'];
        $wxnumber->number= $arr['wxnum'];
        $wxnumber->name= $arr['wxname'];
        $wxnumber->imgurl= $arr['images'];
        $flag = $wxnumber->save();
        Cache::store('redis')->rm($arr['uid'].'_'.$arr['gid']);
        if ($flag){
            echo Response::success('微信添加成功')->getContent();
            return ;
        }else{
            echo Response::fail('微信添加失败')->getContent();
            return ;
        }

    }

    /**
     * 编辑更新微信表单
     * @param $gid
     */
    public function editWxForm($gid){
        $wxnumber = new WxNumber();
        $flag = $wxnumber->where('id',$gid)->where('uid',cmf_get_current_admin_id())->find();
        if (!$flag){
            echo Response::fail("数据错误，请刷新后重试")->getContent();
            return;
        }
        $action = 'addWx';
        $method = 'PUT';

        $wxid = Elm::textarea('gid', 'gid')->value($flag['id'])->hiddenStatus();
        $wxname = Elm::textarea('wxname', 'wx对应名称')->value($flag['name'])->maxlength(10);
        $wxnum = Elm::textarea('wxnum', '展示微信号')->value($flag['number'])->maxlength(13)->required();
        $upload = Elm::uploadImage('images', '二维码',"imgSave","")->value($flag['imgurl']);
        //创建表单
        $form = Elm::createForm($action)->setMethod($method);
        //添加组件
        $form->setRule([$wxname,$wxnum,$upload,$wxid]);
        //生成表单页面
        echo $formHtml = $form->view();
    }

    //保存编辑微信号数据
    public function saveEditForm($arr){
        $wxnumber =  new WxNumber();
        $flag = $wxnumber->where('id',$arr['gid'])->find();
        $uid = cmf_get_current_admin_id();
        Cache::store('redis')->rm($uid.'_'.$flag['gid']);
        $flag = $wxnumber->where('id',$arr['gid'])
            ->update([
                        'number'=>$arr['wxnum'],
                        'name'=>$arr['wxname'],
                        'imgurl'=>$arr['images']
                     ]);

        $uid = cmf_get_current_admin_id();
        Cache::store('redis')->rm($uid.'_'.$arr['gid']);
        if ($flag){
            echo Response::success('微信修改成功')->getContent();
            return ;
        }else{
            echo Response::fail('微信修改失败，请刷新重试')->getContent();
            return ;
        }
    }

    //删除微信号
    public function deleWx($id){
        $wxnumber =  new WxNumber();

        $flag = $wxnumber->where('id',$id)->find();
        $uid = cmf_get_current_admin_id();
        Cache::store('redis')->rm($uid.'_'.$flag['gid']);

        $flag = $wxnumber->where('id',$id)->delete();

        if ($flag){
            return $this->msg('200','删除成功');

        }else{
            return $this->msg('400','删除失败');

        }
    }

    /**
     * @param $uid 用户id
     * @param $gid 组id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function cdnwx($uid,$gid){
        $wxnumber =  new WxNumber();
        $wx = $wxnumber->where('uid',$uid)->where('gid',$gid)->select()->toArray();
        if ($wx){
            return $this->msg('200','success',$wx);
        }else{
            return $this->msg('202','error','error');
        }
    }


    public function getWxjs($gid){
        $action = '';
        $method = '';
        $domain = Request::domain();
        $uid = cmf_get_current_admin_id();
        $wx= "<script id=\"a2bc\" src=\"".$domain."/wx/wx.js?uid=".$uid."&gid=".$gid."\" type=\"text/javascript\" charset=\"utf-8\"></script>";
        $wx_copy= "<script id=\"a2bc\" src=\"".$domain."/wx/a2bc.js?kw_sign_id=".$uid."&kw_gro_id=".$gid."\" type=\"text/javascript\" charset=\"utf-8\"></script>";

        $wxname = Elm::textarea('wxname', '微信调用')->value($wx);
        $wxnum = Elm::textarea('wxnum', '复制加微信调用')->value($wx_copy);
        $form = Elm::createForm();
        //添加组件
        $form->setRule([$wxname,$wxnum]);
        //生成表单页面
        echo $formHtml = $form->view();

    }




    public function msg($code,$msg,$data=""){
        $arr['code'] =$code;
        $arr['msg'] =$msg;
        $arr['data'] =$data;
        return json($arr);

    }


}