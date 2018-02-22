<?php
namespace Admin\Controller;
use Admin\Controller;
/**
 * 厂区管理
 */
class FactoryController extends BaseController
{
    /**
     * 单页列表
     * @return [type] [description]
     */
    public function index($key="")
    {
        $model = D('FactoryView');
        $where = array();
        if($key){
            $where['factory.name'] = array('like',"%$key%");
        } 
        
        $count  = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Extend\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $pages = $model->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('id DESC')->select();
        $this->assign('model', $pages);
        $this->assign('page',$show);
        $this->display();     
    }

    /**
     * 添加厂区
     */
    public function add()
    {
        //默认显示添加表单
        if (!IS_POST) {
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
            $model = D("Factory");
            if (!$model->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($model->getError());
                exit();
            } else {
                if ($model->add()) {
                    $this->success("添加成功", U('factory/index'));
                } else {
                    $this->error("添加失败");
                }
            }
        }
    }
    /**
     * 更新厂区信息
     * @param  [type] $id [单页ID]
     * @return [type]     [description]
     */
    public function update($id)
    {
        //默认显示添加表单
        if (!IS_POST) {
            $info = D('Factory')->where(array('id'=>$id))->find();
            $this->assign('info',$info);
            $this->display();
        }
        if (IS_POST) {
            $model = D("Factory");
            if (!$model->create()) {
                $this->error($model->getError());
            }else{
                if ($model->save()) {
                    $this->success("更新成功", U('factory/index'));
                } else {
                    $this->error("更新失败");
                }        
            }
        }
    }
    /**
     * 删除厂区
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function delete($id)
    {
        $model = D('Factory');
        $result = $model->where(array("id"=>$id))->delete();
        if($result){
            $this->success("删除成功", U('factory/index'));
        }else{
            $this->error("删除失败");
        }
    }

    /**
     * 设置厂区状态
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function setStatus($id)
    {
        $model = D('Factory');
        $info = $model->where(array("id"=>$id))->find();
        if(!$info) {
            $this->ajaxReturn(array('code'=>1001, 'msg'=>'厂区已删除！'));
        }
        if($info['status']){
            $data = array('status'=>0);
        }else{
            $data = array('status'=>1);
        }
        $result = $model->where(array("id"=>$id))->save($data);
        if(!$result) {
            $this->ajaxReturn(array('code'=>1001, 'msg'=>'厂区状态设置失败！'));
        }
        if($info['status']) {
            $this->ajaxReturn(array('code'=>0, 'msg'=>'厂区禁用成功！'));
        } else {
            $this->ajaxReturn(array('code'=>0, 'msg'=>'厂区启用成功！'));
        }

    }
}
