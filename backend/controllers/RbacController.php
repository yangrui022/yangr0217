<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\NotFoundHttpException;

class RbacController extends PublicController
{
    public function actionAddPermission()
    {
        $model = new PermissionForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->addPermission()) {

                \Yii::$app->session->setFlash('success', '添加成功！');
                return $this->redirect(['rbac/permission-index']);
            }

        }

        return $this->render('add-permission', ['model' => $model]);
    }

    public function actionPermissionIndex()
    {
        $permissions = \Yii::$app->authManager->getPermissions();

        return $this->render('permission-index', ['permissions' => $permissions]);
    }

    //修改权限
    public function actionEditPermission($name)
    {

        $permission = \Yii::$app->authManager->getPermission($name);
//        var_dump($permission);exit;
        //判断权限是否存在
        if ($name == null) {
            throw  new NotFoundHttpException('权限不存在');
        }

        $model = new PermissionForm();
        $model->loadData($permission);

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            if ($model->updatePermission($name)) {
                \Yii::$app->session->setFlash('success', '修改权限成功！');
                return $this->redirect(['permission-index']);
            }
        }
        return $this->render('add-permission', ['model' => $model]);

    }

    //删除权限
    public function actionDelPermission($name)
    {

        //获取当前权限
        $permission = \Yii::$app->authManager->getPermission($name);
//        var_dump($permission);exit;
        //判断权限是否存在
        if ($name == null) {
            throw  new NotFoundHttpException('权限不存在');
        }
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success', '修改删除成功！');
        return $this->redirect(['permission-index']);

    }


    //角色列表
    public function actionRoleIndex()
    {

        $roles = \Yii::$app->authManager->getRoles();

        return $this->render('role-index', ['roles' => $roles]);
    }


    //添加角色
    public function actionAddRole()
    {

        $model = new RoleForm();

        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if ($model->addRole()) {

                \Yii::$app->session->setFlash('success', '添加角色成功！');
                return $this->redirect(['rbac/role-index']);
            }

        }

        return $this->render('add-role', ['model' => $model]);

    }

    //修改角色
    public function actionEditRole($name)
    {

        $role = \Yii::$app->authManager->getRole($name);
//        var_dump($permission);exit;
        //判断权限是否存在
        if ($name == null) {
            throw  new NotFoundHttpException('角色不存在');
        }

        $model = new RoleForm();
       $model->loadData($role);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            if ($model->updateRole($name)) {
                \Yii::$app->session->setFlash('success', '修改角色成功！');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('add-role', ['model' => $model]);

    }
}