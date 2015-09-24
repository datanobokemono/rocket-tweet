<?php

namespace app\controllers;

use Yii;

class TweetController extends \yii\web\Controller
{
	private $tApi;

	public function init() {
		$this->tApi = new \TwitterAPIExchange(Yii::$app->params['twitter_api_settings']);
	}

    public function actionIndex()
    {
    	$url = 'https://api.twitter.com/1.1/search/tweets.json';
    	$requestMethod = 'GET';

    	$getfield = '?geocode=43.240342,76.915449,100km';

    	$tweets = $this->tApi->setGetfield($getfield)
			->buildOauth($url, $requestMethod)
			->performRequest();

		$tweets = json_decode($tweets);

		foreach ($tweets->statuses as $tweet) {
			echo $tweet->user->name . '<br>';
			echo $tweet->user->screen_name . '<br>';
			echo $tweet->user->description . '<br>';
			echo $tweet->id_str . '<br>';
			echo $tweet->text . '<br>';
			echo $tweet->coordinates->coordinates[0] . ' ' . $tweet->coordinates->coordinates[1];

			echo '<hr />';
		}


    }

}
