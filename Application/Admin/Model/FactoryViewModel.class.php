<?php 
namespace Admin\Model;
use Think\Model\ViewModel;
class FactoryViewModel extends ViewModel {
   public $viewFields = array(
     'factory'=>array('id','name','status','user_id','create_at'),
     'member'=>array('username', '_on'=>'factory.user_id=member.id'),
   );
 }

?>