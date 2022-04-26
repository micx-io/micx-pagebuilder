<?php
namespace App;

use Brace\Core\AppLoader;
use Brace\Core\BraceApp;
use Brace\Dbg\BraceDbg;
use Brace\Mod\Request\Zend\BraceRequestLaminasModule;
use Brace\Router\RouterModule;
use Lack\Subscription\Brace\SubscriptionClientModule;
use Micx\FormMailer\Config\Config;
use Phore\Di\Container\Producer\DiService;
use Phore\Di\Container\Producer\DiValue;
use Psr\Http\Message\ServerRequestInterface;


BraceDbg::SetupEnvironment(true, ["192.168.178.20", "localhost", "localhost:8080"]);


AppLoader::extend(function () {
    $app = new BraceApp();
    $app->addModule(new BraceRequestLaminasModule());
    $app->addModule(new RouterModule());
    $app->addModule(new SubscriptionClientModule("/opt/mock/sub", "micx-pagebuilder"));

    $app->define("app", new DiValue($app));


    return $app;
});
