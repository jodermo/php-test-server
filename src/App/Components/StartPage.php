<div class="start-page content-wrapper">
    <div class="page-content">
        <h3>Server Info</h3>
        <div class="php-output">
            <?php echo $app->arrayToHtmlTable([
                "HOSTNAME" => $_SERVER["HOSTNAME"],
                "SERVER_NAME" => $_SERVER["SERVER_NAME"],
                "SERVER_ADDR" => $_SERVER["SERVER_ADDR"],
                "SERVER_SOFTWARE" => $_SERVER["SERVER_SOFTWARE"],
                "REQUEST_SCHEME" => $_SERVER["REQUEST_SCHEME"],
                "PHP_VERSION" => $_SERVER["PHP_VERSION"],
                "HTTP_HOST" => $_SERVER["HTTP_HOST"],
                "HTTP_ACCEPT" => $_SERVER["HTTP_ACCEPT"],
                "HTTP_ACCEPT_LANGUAGE" => $_SERVER["HTTP_ACCEPT_LANGUAGE"],
                "HTTP_USER_AGENT" => $_SERVER["HTTP_USER_AGENT"]
            ]); ?>
        </div>
    </div>
</div>