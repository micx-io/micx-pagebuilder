<?php
namespace App;



use Brace\Auth\Basic\RequireValidAuthTokenMiddleware;
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
use Micx\PageBuilder\Ctrl\PageCtrl;
use Micx\PageBuilder\Ctrl\PageListCtrl;
use Micx\PageBuilder\Ctrl\RepoCtrl;
use Phore\Mail\PhoreMailer;
use Phore\VCS\VcsFactory;
use Psr\Http\Message\ServerRequestInterface;

AppLoader::extend(function (BraceApp $app) {

    $mount = CONF_API_MOUNT;

    $app->router->on("GET@$mount/pagebuilder.js", JsCtrl::class);

    $app->router->on("POST|GET@$mount/:subscription_id/:scope_id/files/::file", FileCtrl::class, [RequireValidAuthTokenMiddleware::class]);
    $app->router->on("POST|GET@$mount/:subscription_id/:scope_id/pages/::page_id", PageCtrl::class, [RequireValidAuthTokenMiddleware::class]);
    $app->router->on("POST|GET@$mount/:subscription_id/:scope_id/list/pages", PageListCtrl::class,  [RequireValidAuthTokenMiddleware::class]);
    $app->router->on("POST|GET@$mount/:subscription_id/:scope_id/repo", RepoCtrl::class, [RequireValidAuthTokenMiddleware::class]);


    $app->router->on("GET@$mount", function() {
        return ["system" => "micx pagebuilder", "status" => "ok"];
    });

    $app->router->on("GET@/pub-key", function (VcsFactory $vcsFactory) {
       return ["ssh_public_key"=>$vcsFactory->createSshPublicKey()];
    });

    $app->router->on("GET@/e/:subscription_id/:scope_id*", function () use ($app) {
         return $app->responseFactory->createResponseWithBody(file_get_contents(__DIR__ . "/../www/page.html"), 200, ["Content-Type" => "text/html"]);
    },  [RequireValidAuthTokenMiddleware::class]);

});
