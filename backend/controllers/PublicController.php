<?php
namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

Class PublicController extends Controller{


    public function behaviors()
    {
        return [

            'access' => [
                'class' => AccessControl::className(),
//                'only' => ['add', 'edit', 'delete', 'index','pwd',''],
                'rules' => [
                    [//未认证用户允许执行view操作
                        'allow' => true,//是否允许执行
                        'actions' => ['login'],//指定操作
                        'roles' => ['?'],//角色？表示未认证用户  @表示已认证用户
                    ],
                    [//证用户允许执行的操作
                        'allow' => true,//是否允许执行
//                        'actions' => ['index', 'add', 'delete', 'edit','pwd','logout'],//指定操作
                        'roles' => ['@'],//角色？表示未认证用户  @表示已认证用户
                    ],
                ]
            ],
        ];

    }
}