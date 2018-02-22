<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;
use Com\Wechat;
use Com\WechatAuth;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {
    protected $appid;
    protected $appsecret;
    //protected $openid;

	/* 空操作，用于输出404页面 */
	public function _empty(){
		$this->redirect('Index/index');
	}

    protected function _initialize(){
        $user = session('user');
        if(!$user) {
            $this->appid = C("WX_APPID");
            $this->appsecret = C("WX_APPSECRET");
            $token = session("token");
            if (!$token) {
                $auth = new WechatAuth($this->appid, $this->appsecret);
                $token = $auth->getAccessToken();
                session(array('expire' => $token['expires_in']));
                session("token", $token['access_token']);
            }
            $auth = new WechatAuth($this->appid, $this->appsecret, $token);
            $code = I('code');
            if (!$code) {
                redirect($auth->getRequestCodeURL('http://hongqichoujiang.xteknology.com/'));
            }
            $acc = $auth->getAccessToken('code', $code);
            $user = $auth->getUserInfo($acc['openid']);
            $userInfo = M('user')->where(array('openid'=>$user['openid']))->find();
            if($userInfo) {
                M('user')->where(array('id'=>$userInfo['id']))->save($user);
            } else {
                M('user')->add($user);
            }
            session("user", $user);
        }
        //$this->openid = $acc['openid'];
    }

    public function getOpenId() {
        $user = session('user');
        return $user['openid'];
    }
    
    public function getNickName() {
        $user = session('user');
        return $user['nickname'];
    }


}
