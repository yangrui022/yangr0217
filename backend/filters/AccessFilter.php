<?php
namespace backend\filters;
use Behat\Gherkin\Exception\NodeException;
use yii\base\ActionFilter;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class AccessFilter extends ActionFilter
{

    public function beforeAction($action)
    {

//
        //判断当前用户是否有权限操作
      if(!\Yii::$app->user->can($action->uniqueId)){

          //判断是否登录,如果没登录跳转到登录页面
          if(\Yii::$app->user->isGuest){
            \Yii::$app->session->setFlash('danger','你还没有登录!');
              return $action->controller->redirect(\Yii::$app->user->loginUrl);
          }
        //没权限抛出异常
          throw new HttpException('403','你没有权限访问！');
          return false;
      }
      return parent::beforeAction($action);
    }

}