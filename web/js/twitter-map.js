/*
	author: datanobokemono
*/

// Google Map объекты
var map;
var tweetsOnMap = [];

// Настройки авто-загрузки
var autoReload = false;
var autoReloadTimerID = false;
var autoReloadTime = 0;

// Настройки загрузки
var loading = false;

// ID таймера сокрытия сообщения
var hideNoTweetsTimerID = 0;

// Инициализация карты (происходит после загрузки скрипта во view-файле)
function initMap() {
    map = new google.maps.Map(document.getElementById('tweet-map'), {
        center: {lat: 43.240342, lng: 76.915449},
        zoom: 15
    });

    parseTweets($('#tweet-map').data('init-url'));
}

/* 
	Обработка твитов из БД, получаемых по одному из методов:
	* Инициализация
	* Обновление
*/
function parseTweets(url) {
	if (!loading) {
		setLoading();

		// Ajax запрос по URL
		$.get(url, function(data) { 
			if (data) {
				// В случае если есть данные, то парсим их
				tweets = JSON.parse(data);
			    for (ind in tweets) {
			        tweet = tweets[ind];
			        addTweetToMap(tweet);
			    }
			    // И выводим кол-во
		    	$("#tweet-count").html(tweets.length);
		    	$('.tweet-count').removeClass('hidden');
				$('.no-tweets-found').addClass('hidden');
			} else {
				// Если же данных нет, то на 5 сек. показываем сообщение
				$('.tweet-count').addClass('hidden');
				$('.no-tweets-found').removeClass('hidden');

				// Сохраняем таймер для сокрытия чтобы не накладывались друг на друга
				if (hideNoTweetsTimerID) {
					clearTimeout(hideNoTweetsTimerID);
					hideNoTweetsTimerID = 0;
				};
				hideNoTweetsTimerID = setTimeout(function() {
					$('.no-tweets-found').addClass('hidden');
				}, 5000);
			};

			// Освобождаем кнопку
			unsetLoading();
		});
	};
}


/* 
	addTweetToMap
	Функция для добавления текста твитов на карту.
 */
function addTweetToMap(tweet) {
	// Местоположение твита
    tweetLatlng = new google.maps.LatLng(tweet.lng,tweet.lat);

    // Загоняем в массив всех твитов
    tweetsOnMap.push(new google.maps.InfoWindow({
        content: '<img style="float:left; margin-right: 10px;" width="50" src="'+tweet.user_img+'">' + 
                	'<h3 style="margin: 0 0 5px 0;">' + tweet.user_name +  '</h3>' +
        			'<a href="https://twitter.com/'+tweet.user_screen_name+'" target="_blank">' +
        				'@' + tweet.user_screen_name +
                    '</a> <hr style="min-width: 300px">' + 
                    '<p style="clear: both;">' + tweet.text + '</p>',
        maxWidth: 400,
        position: tweetLatlng
    }));

    // Показываем твит на карте
    ind = tweetsOnMap.length - 1;
    tweetsOnMap[ind].open(map);
}


/*
	Обновление состояния кнопки "ОБНОВИТЬ ТВИТЫ".
	Работа функции зависит от глобальной переменной autoReload
 */
function updateAutoReload() {
	if (autoReload) {
		autoReloadTimerID = setInterval(function() {
			progress = ++autoReloadTime / 20 * 100;
			$('.reload-progress').animate({'width' : progress + '%'}, 500);
			if (autoReloadTime == 20) {
				$('.update-tweets').click();
				resetAutoReloadProgress();
			};
		}, 1000);
	} else {
		resetAutoReloadProgress();
	};
}
// Обнуляет таймер и полосу прогресса авто-обновления
function resetAutoReloadProgress() {
	if (autoReload) {
		autoReloadTime = 0;
		$('.reload-progress').animate({'width' : '0'}, 200);
		clearInterval(autoReloadTimerID);
	};
}

function toggleAutoReload() {
	if($('.auto-reload').is(':checked')) {
		autoReload = true;
	} else {
		autoReload = false;
	}
	updateAutoReload();
}

// Функции для обработки состояния загрузки твитов
function setLoading() {
	$('.update-tweets').addClass('loading');
	loading = true;

	// Убираем полосу авто-обновления, т.к. во время загрузки счет не должен идти
	resetAutoReloadProgress();
}
function unsetLoading() {
	$('.update-tweets').removeClass('loading');
	loading = false;

	// Проверяем, нужно ли заводить новый счет на авто-обновление
	updateAutoReload();
}
