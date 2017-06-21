<?php
namespace backend\models;

use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model{

 public $name;

 public $description;


  public function rules()
  {
      return [

          [['name','description'],'required','message'=>'{attribute}不能为空']
      ];
  }

  public function attributeLabels()
  {
      return [

          'name'=>'权限名称',
          'description'=>'描述'
      ];
  }

  //添加权限
    public function addPermission(){


        //实例化rbac组件
        $authManager=\Yii::$app->authManager;
        //创建权限
        //判断权限是否存在
        if($authManager->getPermission($this->name)){
            $this->addError('name','权限已经存在！');
        }else{
            //不存在就创建权限
            $permission=$authManager->createPermission($this->name);
            $permission->description=$this->description;

            return  $authManager->add($permission);
        }

            return false;
    }
    //更新加载权限
    public function loadData(Permission $permission){

        $this->name=$permission->name;
       $this->description=$permission->description;

    }
    //修改权限
    public function updatePermission($name){
        //实例化权限对象
        $authManager=\Yii::$app->authManager;
        //获取当前权限
        $permission=$authManager->getPermission($name);
        //修改前判断修改的权限是否存在
        if($this->name!=$name && $authManager->getPermission($this->name)){
            $this->addError('name','权限已经存在！');
        }else{
            //给权限赋值
            $permission->name=$this->name;
            $permission->description=$this->description;
          return  $authManager->update($name,$permission);
        }
      return false;
    }

}