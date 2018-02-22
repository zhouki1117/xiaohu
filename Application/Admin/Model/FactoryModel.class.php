<?php
namespace Admin\Model;
use Think\Model;
class FactoryModel extends Model{
    protected $_validate = array(
        array('name','require','请填写名称！'), //默认情况下用正则进行验证
        array('name','','名称已经存在！',0,'unique',self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('status',array(0,1),'请勿恶意修改字段',3,'in'), // 当值不为空的时候判断是否在一个范围内
    );
    protected $_auto = array (
        array('create_at','time',1,'function'), // 新增时写入当前时间戳
        array('update_at','time',2,'function'), // 更新时写入当前时间戳
        array('user_id','getUid',1,'callback'), // 新增时创建人写入当前登录人
    );
    protected function getUid(){
        return session('adminId');
    }
}