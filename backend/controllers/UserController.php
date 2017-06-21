<?php

namespace backend\controllers;


use backend\models\RoleForm;
use backend\models\User;

use backend\models\EditPwdForm;
use backend\models\UseroleForm;
use function PHPSTORM_META\elementType;
use yii\filters\AccessControl;
use yii\web\Request;
use yii\web\UploadedFile;



class UserController extends PublicController
{
    public function actionIndex()
    {

        $users = User::find()->all();

        return $this->render('index', ['users' => $users]);
    }

    //完成用户注册功能
    public function actionAdd()
    {

        $model = new User(['scenario' => User::SCENARIO_ADD]);
        //创建请求对象
        $request = new Request();
        //判断
        if ($request->isPost) {
            $model->load($request->post());

            //加载数据
            //在验证之前先实例化上传文件对象
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');

//            if ($model->validate()) {
            //给密码加密
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
            $fileName = '/images/' . uniqid() . '.' . $model->imgFile->extension;
            $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
            $model->create_time = time();
            //保存数据
            $model->photo = $fileName;

            $model->save(false);

            $model->addUser($model->id);


            \Yii::$app->session->setFlash('success', '注册成功');

            return $this->redirect(['user/index']);
        }
//        }

        return $this->render('add', ['model' => $model]);
    }

    //修改用户信息

    public function actionEdit($id)
    {

        $model = User::findOne($id);
//        $model->scenario=User::SCENARIO_EDIT;
//        //创建请求对象
        $request = new Request();
        $model->loadData($id);
        //判断
        if ($request->isPost) {
            $model->load($request->post());

            //加载数据
            //在验证之前先实例化上传文件对象
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');

            if ($model->validate()) {
                //给密码加密
//                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password_hash);
            if($model->imgFile){
                $fileName = '/images/' . uniqid() . '.' . $model->imgFile->extension;
                $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                $model->updated_time = time();
                //保存数据
                $model->photo = $fileName;

            }

                $model->save(false);
                $model->updateUser($model->id);
                \Yii::$app->session->setFlash('success', '修改成功');

                return $this->redirect(['user/index']);
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }

        return $this->render('add', ['model' => $model]);
    }

//修改密码
    public function actionPwd($id)
    {
        //获取当前修改用户的id
        $user = User::findOne($id);

        $model = new EditPwdForm();
        $request = new Request();

        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            //对数据的验证
            if ($model->validate()) {
                //获取旧密码加密
                $old_password = \Yii::$app->security->validatePassword($model->old_password, $user->password_hash);
                if ($old_password) {
                    //如果用户输入的旧密码正确，就判断新密码是否和确认一致
                    if ($model->new_password != $model->re_password) {
                        \Yii::$app->getSession()->setFlash('danger', '两次密码不一致');

                    } else {
                        //两次密码正确后加密并保存数据到数据库
                        $user->password_hash = \Yii::$app->security->generatePasswordHash($model->new_password);
                        $user->save(false);
                        \Yii::$app->getSession()->setFlash('success', '修改密码成功');

                        return $this->redirect(['user/index']);
                    }

                } else {
                    \Yii::$app->getSession()->setFlash('danger', '旧密码不正确');
                }

            }
        }
        return $this->render('edit', ['model' => $model]);
    }

//删除用户
public function actionDel($id){
        User::findOne($id)->delete();

        //删除该用户的角色
    $authManger=\Yii::$app->authManager;
   $authManger->revokeAll($id);

        return $this->redirect(['user/index']);

}
}