<?php
namespace backend\models;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model{

    public $name;//角色名
    public $description;//角色描述
    public $permissions=[];//权限

    public function rules()
    {
        return [

            [['name','description'],'required','message'=>'{attribute}不能为空'],
            ['permissions','safe'],//表示该字段不需要验证
        ];
    }

    public function attributeLabels()
    {
        return [

            'name'=>'角色名',
            'description'=>'描述',
            'permissions'=>'权限'
        ];
    }

//获取所有权限
    public static function getPermissionOptions(){
        $permissions=\Yii::$app->authManager->getPermissions();

     return   ArrayHelper::map($permissions,'name','description');
    }

    //角色添加
    public function addRole(){
        //实例化rbac组件
        $authManager=\Yii::$app->authManager;
        //判断角色是否存在
        if($authManager->getRole($this->name)){
            $this->addError('name','角色已经存在！');
        }else{
            //创建角色
            $role=$authManager->createRole($this->name);
            $role->description=$this->description;

           //如果角色添加成功后
            if($authManager->add($role)){
                //关联角色的权限
                foreach ($this->permissions as $permissionName){

                    $permission=$authManager->getPermission($permissionName);
                    //判断权限是否存在
                    if($permission)$authManager->addChild($role,$permission);
                }
                return true;
            }

        }


        return false;
    }

    public function loadData(Role $role){
        $this->name=$role->name;

        $this->description=$role->description;
        //返回对应角色的权限
        $permissions=\Yii::$app->authManager->getPermissionsByRole($this->name);
        foreach ($permissions as $permission){
            $this->permissions[]=$permission->name;
        }
    }

    //修改角色
    public function updateRole($name){

        //实例化权限对象
        $authManager=\Yii::$app->authManager;
        //获取当前角色
        $role=$authManager->getRole($name);
        //赋值
        $role->name=$this->name;
        $role->description=$this->description;
        //判断当前修改的角色是否存在
        if($this->name!=$name && $authManager->getRole($this->name)){
            $this->addError('name','角色已经存在！');
        }else{
            //$name为修改前的名称
            //如果修改成功！
            if($authManager->update($name,$role)){
                //去掉所有与该角色关联的权限
                $authManager->removeChildren($role);
                //关联角色的权限
                foreach ($this->permissions as $permissionName){
                    $permission=\Yii::$app->authManager->getPermission($permissionName);
                    if($permission)$authManager->addChild($role,$permission);
                }
                return true;
            }
        }
        return false;
    }
}