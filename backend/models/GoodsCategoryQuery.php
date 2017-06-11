<?php

namespace backend\models;

use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

Class GoodsCategoryQuery extends ActiveQuery{

    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}