<?php
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Rocket Tweets';
$this->registerJsFile('@web/js/twitter-map.js');
?>

<!-- Карта, содержит URL для инициализации твитов -->
<div id="tweet-map" data-init-url="<?= Url::to(['tweet/init-tweets']) ?>">
</div>

<!-- Панель управления -->
<div class="tweet-menu">
    <div class="tweet-menu-header">
        <strong>ROCKET</strong> <i class="glyphicon glyphicon-bullhorn"></i> TWEETS
    </div>

    <ul class="menu-list">
        <li class="list-item">
            <!-- Контроль авто-обновления -->
            <input type="checkbox" class="auto-reload" onclick="toggleAutoReload()"> Автообновление 20 секунд

            <!-- Кнопка обновления передает URL бэкэнда -->
            <a class="update-tweets" onclick="parseTweets('<?= Url::to(['tweet/update-tweets']) ?>')">
                <span class="text"> <i class="glyphicon glyphicon-refresh"></i> Обновить твиты</span>

                <!-- Отсчет до авто-обновления -->
                <span class="reload-progress"></span>
            </a>
        </li>

        <!-- Сообщение о найденых твитах -->
        <li class="list-item hidden tweet-count">
            <i class="glyphicon glyphicon-info-sign"></i>
            Найдено <span id="tweet-count"></span> твитов(-а)
        </li>

        <!-- И не найденых -->
        <li class="list-item hidden no-tweets-found">
            <i class="glyphicon glyphicon-remove-sign"></i>
            Нет ни одного нового твита
        </li>
    </ul>
</div>

<!-- Подгрузка  -->
<script async defer
      src="https://maps.googleapis.com/maps/api/js?key=<?= Yii::$app->params['google_map_key'] ?>&callback=initMap">
</script>
