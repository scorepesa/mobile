<?php

$host = gethostname();
if ($host == "pr-web-1") {
    ini_set('session.save_handler', 'file');
    ini_set('session.save_path', '');
}

error_reporting(E_ALL);
define('APP_PATH', realpath('..'));

try {
    /**
     * Read the configuration
     */
    $config = include __DIR__ . "/../app/config/config.php";

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    // Include 3rd party libraries

    include APP_PATH . "/vendor/autoload.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application();
    $application->setDI($di);
    echo $application->handle()->getContent();
} catch (Exception $e) {
//echo $e->getMessage() . '<br>';
      //echo '<pre>' . $e->getTraceAsString() . '</pre>';
    echo "<head><title>Page Not Found - wekeleabet.com</title></head><body style='background-color: #030928;background:url(img/error-cover.jpg)'><link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'><div style='
    max-width: 600px;
    display: block;
    margin: 100px auto;
    text-align: center;
    color: #e0e0e0;
    font-size: 20px;
    font-family: Montserrat, sans-serif;
'>
<a href='https://wekeleabet.com'><img style='max-width: 180px;' src='images/logo.png' /></a>
<p><b style='font-size: 50px;
    line-height: 60px;
    display: block;'>Error</b> <br/>Something is not adding up. Could not trace this request.<br/> CLick below to go back home
</p>
    <a href='http://wekeleabet.com' class='error-404' style='background: #6e2f44; color: #ffffff; border-radius: 10px; padding:5px 15px;'>Home</a>
</div>

</body>";
}
