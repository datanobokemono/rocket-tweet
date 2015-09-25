<?php

namespace app\controllers;

use Yii;

use app\models\Tweet;

class TweetController extends \yii\web\Controller
{
    // Вывод всех твитов из БД
	public function actionInitTweets()
    {
		$tweets = Tweet::find()->asArray()->all();
		echo json_encode($tweets);
    }

    // Получение новых твитов за последнее время либо популизация таблицы записями в первый раз
    public function actionUpdateTweets()
    {
        // Сохраняем кол-во твитов
    	$limit = Tweet::updateFromTwitter();
    	if ($limit) {
    		$tweets = Tweet::find()->orderBy('id DESC')->limit($limit)->asArray()->all();
    		echo json_encode($tweets);
    	}
    }

}
