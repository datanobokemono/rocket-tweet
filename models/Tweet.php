<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "data_tweets".
 *
 * @property integer $id
 * @property string $tweet_id
 * @property string $lat
 * @property string $lng
 * @property string $user_name
 * @property string $user_screen_name
 * @property string $user_img
 * @property string $description
 * @property string $text
 */
class Tweet extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_tweets';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tweet_id', 'lat', 'lng', 'user_name', 'user_screen_name', 'text'], 'required'],
            [['description', 'text'], 'string'],
            [['tweet_id', 'user_name', 'user_screen_name', 'user_img'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tweet_id' => 'Tweet ID',
            'lat' => 'Lat',
            'lng' => 'Lng',
            'user_name' => 'User Name',
            'user_screen_name' => 'User Screen Name',
            'user_img' => 'User Img',
            'description' => 'Description',
            'text' => 'Text',
        ];
    }

    public static function getAPI() {
        return new \TwitterAPIExchange(Yii::$app->params['twitter_api_settings']);
    }

    /**
     * 
     */
    public function updateFromTwitter($lat = '43.240342', $lng = '76.915449', $radius = '30km') {
        $tApi = self::getAPI();

        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $requestMethod = 'GET';

        $lastId = Tweet::find()->orderBy('id DESC')->limit(1)->all();
        if ($lastId) {
            $lastId = $lastId[0];
            $getfield = "?geocode=$lat,$lng,$radius&since_id=$lastId->tweet_id";
        } else {
            $getfield = "?geocode=$lat,$lng,$radius";

        }

        $tweets = $tApi->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        $tweets = json_decode($tweets);

        $newCount = 0;
        foreach ($tweets->statuses as $tweet) {
            # Берем только те твиты, координаты которых мы можем сохранить
            if($tweet->coordinates && !Tweet::find()->where(['tweet_id' => $tweet->id_str])->count()) {
                $newCount++;
                $newTweet = new Tweet();
                $newTweet->attributes = [
                    'tweet_id' => $tweet->id_str,
                    'user_name' => $tweet->user->name,
                    'user_screen_name' => $tweet->user->screen_name,
                    'description' => $tweet->user->description,
                    'text' => $tweet->text,
                    'lat' => $tweet->coordinates->coordinates[0],
                    'lng' => $tweet->coordinates->coordinates[1],
                    'user_img' => $tweet->user->profile_image_url,
                ];

                if(!$newTweet->save()) {
                    return false;
                }
            }
        }

        return $newCount;
    }
}
