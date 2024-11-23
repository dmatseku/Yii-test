<?php

namespace console\controllers;

use backend\models\Loan;
use yii\console\Controller;

class AnnualController extends Controller
{
    /**
     * @return void
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionLucky(): void
    {
        $count = Loan::find()->count();
        $id = mt_rand(1, $count);
        echo "Id " . $id . " selected" . PHP_EOL;
        $records = Loan::find()->where(['>=', 'id', $id])->limit(1)->all();

        if (count($records) > 0) {
            $records[0]->delete();
        }
    }
}