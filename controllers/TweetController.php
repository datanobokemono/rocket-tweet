<?php

namespace app\controllers;

use Yii;

use app\models\Tweet;

class TweetController extends \yii\web\Controller
{

	public function actionInitTweets()
    {
		$tweets = Tweet::find()->asArray()->all();
		echo json_encode($tweets);
    }

    public function actionUpdateTweets()
    {
    	$limit = Tweet::updateFromTwitter();
    	if ($limit) {
    		$tweets = Tweet::find()->orderBy('id DESC')->limit($limit)->asArray()->all();
    		echo json_encode($tweets);
    	}
    }

}
