<?php

require_once('./App/App.php');

use App\App;

$appEnvironemntJSON = file_get_contents("environment.json");
$appConfig = json_decode($appEnvironemntJSON, true);

$app = new App($appConfig);

$randomNumber = rand();
$title = $app->getConfig('title');
$description = $app->getConfig('description');
$keywords = $app->getConfig('keywords');
$author = $app->getConfig('author');


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $description; ?>">
    <meta name="keywords" content="<?php echo $keywords; ?>">
    <meta name="author" content="<?php echo $author; ?>">
    <title><?php echo $title; ?><?php echo $app->pageName ? " - " . $app->pageName : null; ?></title>


    <link rel="stylesheet" href="css/styles.css?v=<?php echo $randomNumber; ?>">
    <link href="favicon.ico" rel="icon" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:display=swap" rel="stylesheet">
    <script src="/js/app.js?v=<?php echo $randomNumber; ?>"></script>
</head>
<bod id="body">
    <div class="background-image">
        <div class="image-container">
            <img src="assets/img/app_background.png">
        </div>
    </div>
    <div class="overlay transparent-background"></div>
    <div id="wrapper">
        <div id="header">
            <h1>
                <?php include('./App/Components/Header.php'); ?>
            </h1>
        </div>
        <div id="content">
            <div id="left">
            </div>
            <div id="main">
                <div class="overlay">
                    <?php
                    if ($app->pageName == 'chat') {
                        include('./App/Modules/Chat/Chat.php');
                    } else if ($app->pageName == 'mollie') {
                        include('./App/Modules/Mollie/Mollie.php');
                    } else {
                        include('./App/Components/StartPage.php');
                    }
                    ?>
                </div>
            </div>
            <div id="right">
            </div>
        </div>
        <div id="footer">
            <?php include('./App/Components/Footer.php'); ?>
        </div>
    </div>

    </body>

</html>