<?php
namespace Admin\Model;
use Think\Model;
class PrizeModel extends Model{
    protected $_validate = array(
        array('openid','require','中奖人！'), //默认情况下用正则进行验证
    );
    protected $_auto = array(
        array('update_time','time',2,'function'), //更新时
        array('create_time','time',1,'function'), //新增时
    );

}