<div class="container-fluid formulaire">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h1 class="titleSearch text-center">WeatherMood Search!</h1>
            <h2 class="h3">Stream your weather...</h2>
        </div>
        <div class="col-12 col-md-4 text-center ">
            <form class="form" method="POST" action="page.php">
                <div class="input-group">
                    <input class="form-control" type="text" name="search" id="search" placeholder="City, Country">
                    <input type="submit" class="btn btn-primary" name="submit" id="submit" value="GO!">
                </div>
            </form>
            <?php
            if (isset($_GET['msg']) && !empty($_GET['msg'])) {
                if ($_GET['msg'] == 'badCity') {
                    echo '<h3 class="h1 text-danger">No results found for the city : ' . $_GET['value'] . '</h3>';
                } else if ($_GET['msg'] == 'null') {
                    echo '<h3 class="h1 text-danger">Input field is empty</h3>';
                }
            }
            ?>
        </div>
    </div>
</div>
<video class="bgvid" playsinline autoplay muted loop poster="image/ciel.png">
    <source src="image/sky.mp4" type="video/mp4">
</video>