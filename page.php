<?php
include "src/header.php";
if (isset($_POST['submit']) || isset($_POST['search'])) {
    //if submit is push without datas in the search bar, reload index.php and get message
    if (empty($_POST['search'])) {
        header("location: index.php?msg=null");
    } else {
        //Search word
        $post = trim(htmlentities(ucfirst($_POST['search'])));
        $_SESSION['search'] = $post;
        //Get datas from http request to openweathermap
        $jsonMeteo = file_get_contents('http://api.openweathermap.org/data/2.5/weather?q=' . $_SESSION['search'] . '&&apikey=6c3ae00bfde9d0251218bf15b1e16c9a&units=metric&lang=fr');
        //if submit nothing corresponding from the cities list, reload index.php and get message
        if (!$jsonMeteo) {
            header("location: index.php?msg=badCity&value=" . $_SESSION['search']);
        } else {
            $meteo = json_decode($jsonMeteo, true);
            //Change conditions english to french
            $conditions = ['Clear' => 'Dégagé',
                'Rain' => 'Pluvieux',
                'Snow' => 'Neigeux',
                'Clouds' => 'Nuageux',
                'Thunderstorm' => 'Orageux',
                'Drizzle' => 'Bruineux',
                'Atmosphere' => 'Brumeux',
            ];
            //Array to change openweather icon to weather icon from toolkit => https://erikflowers.github.io/weather-icons/
            $icons = ['01d' => 'wi-day-sunny',
                '02d' => 'wi-day-cloudy',
                '03d' => 'wi-cloud',
                '04d' => 'wi-cloudy',
                '09d' => 'wi-rain',
                '10d' => 'wi-day-showers',
                '11d' => 'wi-thunderstorm',
                '13d' => 'wi-snow',
                '50d' => 'wi-fog',
                '01n' => 'wi-night-clear',
                '02n' => 'wi-night-alt-cloudy',
                '03n' => 'wi-cloud',
                '04n' => 'wi-cloudy',
                '09n' => 'wi-rain',
                '10n' => 'wi-night-alt-showers',
                '11n' => 'wi-thunderstorm',
                '13n' => 'wi-snow',
                '50n' => 'wi-fog',
            ];
            $sun = ['sun', 'happy', 'samba', 'sunshine', 'diamon', 'smile', 'reagge', 'ragga', 'good vibes', 'fun', 'nice', 'love'];
            $rain = ['sad', 'homeless', 'alone', 'bad', 'hangry', 'dark', 'moon', 'cloud', 'rain', 'sickness'];
            $drizzle = ['sad', 'homeless', 'alone', 'bad', 'hangry', 'dark', 'moon', 'cloud', 'rain', 'sickness'];
            $snow = ['cold', 'snow', 'christmas', 'chirstmas tree', 'wind', 'winter', 'fireplace'];
            $cloud = ['Clouds', 'Cloud', 'Cloudy', '', '', '', '', '', '', '', '', '', ''];
            $atmosphere = ['thunder', 'strom', 'thunderstorm', 'cyclone', 'mist', 'sad day', 'black mind', 'emergency'];

            $keyWord = [
                'Clear' => $sun[array_rand($sun)],
                'Rain' => $rain[array_rand($rain)],
                'Drizzle' => $drizzle[array_rand($drizzle)],
                'Snow' => $snow[array_rand($snow)],
                'Clouds' => $cloud[array_rand($cloud)],
                'Atmosphere' => $atmosphere[array_rand($atmosphere)],
            ];
            //Get datas from http request to deezer with keyword in parameter
            $dzReturn = file_get_contents('http://api.deezer.com/search?q=track:"' . $keyWord[$meteo['weather'][0]['main']] . '"');
            $tracks = json_decode($dzReturn, true);
            //Random id track to play in deezer
            $track = $tracks['data'][array_rand($tracks['data'])]['id'];
        }
    }
}
?>
    <div class="container-fluid">
        <div class="row no-gutters justify-content-around">
            <div class="col-12 col-md-4 tuile my-3 text-center mr-1 d-flex">
                <div class="align-self-center mx-auto">
                    <h1> The mood from <?php echo $meteo['name'] . ' ' . $meteo['sys']['country'] ?></h1>
                    <p><?php echo date('d-m-Y') ?> </p/>
                    <form class="form-inline" method="POST" action="page.php">
                        <div class="input-group mx-auto">
                            <input class="form-control" type="text" name="search" id="search"
                                   placeholder="Nouvelle recherche">
                            <button type="submit" class="btn btn-secondary btn-lg mt-0">Go!</button>
                        </div>
                    </form>
                    <a class="btn btn-secondary btn-lg" href="index.php">Retour</a>
                    <p>
                        <span class="queryDate"> Last update : <?php echo date('h') . ' h - ' . date('i') . ' m - ' . date('s') . ' s' ?></span>
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-7 tuile my-3 text-center ml-1 d-flex">
                <div class="align-self-center mx-auto">
                    <div class="row no-gutters">
                        <div class="col-12">
                            <h1>Actual Conditions</h1>
                        </div>
                        <div class="col-3 hover">
                            <?php
                            echo '<h3>' . $conditions[$meteo['weather'][0]['main']] . '</h3>
									<i class="iconTop wi ' . $icons[$meteo['weather'][0]['icon']] . '"></i>';
                            ?>
                        </div>
                        <div class="col-3 hover">
                            <?php
                            echo '<h3>Temperature ' . $meteo['main']['temp'] . ' °C</h3>';
                            if ($meteo['main']['temp'] < 10) {
                                echo '<i class="iconTop wi wi-thermometer-exterior"></i>';
                            } else {
                                echo '<i class="iconTop wi wi-thermometer"></i>';
                            }
                            ?>
                        </div>
                        <div class="col-3 hover">
                            <?php
                            echo '<h3>Humidity ' . $meteo['main']['humidity'] . ' %</h3>
										<i class="iconTop wi wi-humidity"></i>';
                            ?>
                        </div>
                        <div class="col-3 hover">
                            <?php
                            echo '<h3>Pressure ' . $meteo['main']['pressure'] . ' Pa</h3>
										<i class="iconTop wi wi-barometer"></i>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row no-gutters justify-content-around">
            <div class="col-12 col-md-4">
                <div class="row no-gutters">
<!--                    <div class="col-12 text-center tuile mb-2 d-flex">-->
<!--                        <div class="row no-gutters align-self-center mx-auto">-->
<!--                            <div class="col-12">-->
<!--                                <h1 class="text-center">Wind conditions</h1>-->
<!--                            </div>-->
<!--                            <div class="col-6">-->
<!--                                <p class="hover">-->
<!--                                    <i class="iconTop wi wi-wind-direction wi-rotate---><?php //echo $meteo['wind']['deg'] ?><!--"></i>-->
<!--                                    --><?php //echo $meteo['wind']['deg'] ?><!-- deg-->
<!--                                </p>-->
<!--                            </div>-->
<!--                            <div class="col-6">-->
<!--                                <p class="hover">-->
<!--                                    <i class="iconTop wi wi-strong-wind"></i>-->
<!--                                    --><?php //echo $meteo['wind']['speed'] ?><!-- km/h-->
<!--                                </p>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="col-12 text-center tuile musicTuile">
                        <?php
                        //Video playlist for background
                        $playlist = ['Clear' => 'clear.mp4',
                            'Rain' => 'rain.mp4',
                            'Drizzle' => 'rain.mp4',
                            'Snow' => 'snow.mp4',
                            'Clouds' => 'clouds.mp4',
                            'Atmosphere' => 'atmosphere.mp4'];
                        // New version of deezer player
                        $readerLink = 'http://www.deezer.com/plugins/player?format=square&autoplay=true&playlist=true&width=450&height=500&color=007feb&layout=dark&size=big&type=tracks&id=' . $track . '&app_id=326482';
                        echo '<iframe class="music m-3" scrolling="no" allowTransparency="true"  src="' . $readerLink . '"></iframe>';
                        //Background video
                        echo '
									<video class="bgvid" playsinline autoplay muted loop>
										<source src="image/' . $playlist[$meteo['weather'][0]['main']] . '" type="video/mp4">
									</video>';
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-7">
                <div class="text-center">
                    <div class="tuile mapTuile">
                        <?php
                        $mapLink = 'https://maps.darksky.net/@precipitation_rate,' . $meteo['coord']['lat'] . ',' . $meteo['coord']['lon'] . ',5?embed=true&timeControl=false&fieldControl=false&defaultField=precipitation_rate';
                        echo '<iframe class="map" src="' . $mapLink . '" frameborder="0"></iframe>';
                        ?>
                        <div class="col-12">
                            <div class="col-md-1">
                                <!--div class="checkbox">
                                    <label for="opt1">
                                        <input type="checkbox" name="opt1" id="opt1" value="">Carte
                                    </label>
                                </div-->
                            </div>
                            <div class="col-md-1">
                                <!--div class="checkbox">
                                    <label for="opt2">
                                        <input type="checkbox" name="opt2" id="opt2" value="">Temps
                                    </label>
                                </div-->
                            </div>
                            <div class="col-md-8">
                            </div>
                            <div class="col-md-2">
                                <a class="darkskyLink" href="https://darksky.net/poweredby/">Powered by Dark Sky</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>
<?php include "src/footer.php"; ?>