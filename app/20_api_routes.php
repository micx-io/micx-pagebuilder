<?php
namespace App;



use Brace\Core\AppLoader;
use Brace\Core\BraceApp;
use http\Message\Body;
use Lack\OidServer\Base\Ctrl\AuthorizeCtrl;
use Lack\OidServer\Base\Ctrl\SignInCtrl;
use Lack\OidServer\Base\Ctrl\LogoutCtrl;
use Lack\OidServer\Base\Ctrl\TokenCtrl;
use Lack\OidServer\Base\Tpl\HtmlTemplate;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\TextResponse;
use Laminas\Diactoros\ResponseFactory;
use Micx\FormMailer\Config\Config;
use Micx\PageBuilder\Ctrl\FileCtrl;
use Micx\PageBuilder\Ctrl\JsCtrl;
use Phore\Mail\PhoreMailer;
use Psr\Http\Message\ServerRequestInterface;

AppLoader::extend(function (BraceApp $app) {

    $mount = CONF_API_MOUNT;

    $app->router->on("GET@$mount/pagebuilder.js", JsCtrl::class);

    $app->router->on("POST|GET@$mount/:subscription_id/:scope_id/files/::file", FileCtrl::class);


    $app->router->on("GET@$mount", function() {
        return ["system" => "micx pagebuilder", "status" => "ok"];
    });

});
