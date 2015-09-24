<?php
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'RocketTweets';
?>

<div id="tweet-map" width="100%" height="100%">
    
</div>

<div id="tweet-map-menu">
    <h1><strong>Rocket</strong>Tweets</h1>
    <hr>

    <div class="btn-group">
        <a class="btn btn-success update-tweets" onclick="updateMarkers()">
            Обновить твиты
        </a>
    </div>
</div>


<script type="text/javascript">

var map;
var markers = [];
var tweetsText = [];

function initMap() {
    map = new google.maps.Map(document.getElementById('tweet-map'), {
        center: {lat: 43.240342, lng: 76.915449},
        zoom: 13
    });

    initMarkers();
}

function updateMarkers() {
    console.log(map);

    $.get('<?= Url::to(['tweet/update-tweets']) ?>', function(data) {
        tweets = JSON.parse(data);
        for (ind in tweets) {
            tweet = tweets[ind];
            addMarker(tweet);
        }

        alert(tweets.length + " новых твитов(-а)");

    });
}

function initMarkers() {
    $.get('<?= Url::to(['tweet/init-tweets']) ?>', function(data) {
        tweets = JSON.parse(data);
        for (ind in tweets) {
            tweet = tweets[ind];
            addMarker(tweet);
        }
    });
}

function markerFromTweet(tweet) {
    tweetLatlng = new google.maps.LatLng(tweet.lng,tweet.lat);
    
    var tMarker = new google.maps.Marker({
        position: tweetLatlng,
        title: tweet.text,
    });

    tMarker.setMap(map);
    return tMarker;
}

function addMarker(tweet) {
    markers.push(markerFromTweet(tweet));
    ind = markers.length - 1;
    console.log(tweet, ind);

    tweetsText.push(new google.maps.InfoWindow({
        content: '<img style="float:left" width="50" src="'+tweet.user_img+'">' 
                    + '<a href="https://twitter.com/'+tweet.user_screen_name+'"><h3>' + tweet.user_name +  '</h3></a>' 
                    + tweet.text
    }));


    markers[ind].addListener('click', tieMarkerAndTweet(ind));
}

function tieMarkerAndTweet(ind) {
    tweetsText[ind].open(map, markers[ind]);
}

</script>

<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=<?= Yii::$app->params['google_map_key'] ?>&callback=initMap">
</script>
