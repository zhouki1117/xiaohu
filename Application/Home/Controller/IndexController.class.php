<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends HomeController{

    public function index(){
        $this->display('Index/trans');
    }

    public function welcome(){
        $this->display();
    }

    //介紹页面
    public function desc() {
        $this->display();
    }
    
    public function isLottery() {
        $result = $this->checkLottery();
        if($result) {
            $this->ajaxReturn(array('code'=>0, 'msg'=>'可以抽奖'));
        } else {
            $this->ajaxReturn(array('code'=>1001, 'msg'=>'你已经抽过奖了'));
        }
    }


    //验证是否抽过奖
    public function checkLottery(){
        $openid = $this->getOpenId();
        $is_admin = M('user')->where(array('openid'=>$openid, 'if_admin'=>1))->find();
        if($is_admin) {
            return TRUE;
        }
        $is_exist = M('lottery')->where(array('openid'=>$openid))->find();
        if($is_exist) {
            return FALSE;
        }
        return TRUE;
    }

    public function answer() {
        $result = $this->checkLottery();
        if(!$result) {
            $this->redirect('index/complete');
        }
        $this->display();
    }

    public function choujiang() {
        $this->display();
    }

    public function lottery() {
        $openid = $this->getOpenId();
        $result = $this->checkLottery();
        if(!$result) {
            $this->ajaxReturn(array('code'=>1001, 'msg'=>'你已经抽过奖了'));
        }
        /*
         * 奖项数组
         * 是一个二维数组，记录了所有本次抽奖的奖项信息，
         * 其中id表示中奖等级，prize表示奖品，v表示中奖概率。
         * 注意其中的v必须为整数，你可以将对应的 奖项的v设置成0，即意味着该奖项抽中的几率是0，
         * 数组中v的总和（基数），基数越大越能体现概率的准确性。
         * 本例中v的总和为100，那么平板电脑对应的 中奖概率就是1%，
         * 如果v的总和是10000，那中奖概率就是万分之一了。
         *
         */
        $prize_arr = M('prize')->select();

        /*
         * 每次前端页面的请求，PHP循环奖项设置数组，
         * 通过概率计算函数get_rand获取抽中的奖项id。
         * 最后输出json个数数据给前端页面。
         */
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['number'];
        }
        $rid = get_rand($arr); //根据概率获取奖项id
        M()->startTrans();
        M('prize')->where(array('id'=>$rid))->setDec('number');

        $data = array(
            'id' => $prize_arr[$rid-1]['id'],
            //'name' => $prize_arr[$rid-1]['title'],
            'voucher' => '',
        );

        if($prize_arr[$rid-1]['voucher']) {
            $voucher = M('voucher')->where(array('prize_id'=>$rid, 'status'=>1))->order('id asc')->find();
            M('voucher')->where(array('id'=>$voucher['id']))->save(array('status'=>2));
            $data['voucher'] = $voucher['voucher'];
        }

        D('Lottery')->add(array(
            'openid' => $openid,
            'name' => $this->getNickName(),
            'prize_id'=> $rid,
            'create_time'=> time(),
            'voucher' => $data['voucher']
        ));
        M()->commit();
        $this->ajaxReturn(array('code'=>0, 'data'=>$data));
    }

    public function complete() {
        $openid = $this->getOpenId();
        $data = M('lottery')->where(array('openid'=>$openid))->order('id desc')->find();
        $this->assign('data', $data);
        switch ($data['prize_id']) {
            case 1:
                $tmp = 'Index/com_chou';
                break;
            case 2:
                $tmp = 'Index/twok';
                break;
            case 3:
                $tmp = 'Index/onek';
                break;
            case 4:
                $tmp = 'Index/nomoney';
                break;
        }
        $this->display($tmp);
    }

    public function address(){
        if (!IS_POST) {
            $this->display();
        } else {
            $data = I();
            $this->assign('data', $data);
            $this->display('Index/addconfirm');
        }
    }

    public function com_confirm(){
        $data = I();
        $openid = $this->getOpenId();
        $result = M('lottery')->where(array('openid'=>$openid))->save($data);
        $this->assign('data', $data);
        $this->display();
    }
}
