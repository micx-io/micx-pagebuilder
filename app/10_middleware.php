<?php

namespace App;


use Brace\Auth\Basic\AuthBasicMiddleware;
use Brace\Auth\Basic\BasicAuthToken;
use Brace\Auth\Basic\Validator\LambdaAuthValidator;
use Brace\Body\BodyMiddleware;
use Brace\Core\AppLoader;
use Brace\Core\Base\CallbackMiddleware;
use Brace\Core\Base\ExceptionHandlerMiddleware;
use Brace\Core\Base\JsonReturnFormatter;
use Brace\Core\Base\NotFoundMiddleware;
use Brace\Core\BraceApp;
use Brace\CORS\CorsMiddleware;
use Brace\Router\RouterDispatchMiddleware;
use Brace\Router\RouterEvalMiddleware;
use Brace\Router\Type\RouteParams;
use Brace\Session\SessionMiddleware;
use Brace\Session\Storages\CookieSessionStorage;
use Lack\Subscription\Brace\SubscriptionMiddleware;
use Lack\Subscription\Type\T_Subscription;
use Micx\FormMailer\Config\Config;
use Micx\PageBuilder\Mw\SendRedisMessageMw;
use Micx\PageBuilder\Type\RepoConf;
use Phore\Di\Container\Producer\DiService;


AppLoader::extend(function (BraceApp $app) {

    $app->setPipe([
        new BodyMiddleware(),
        new ExceptionHandlerMiddleware(),
        new RouterEvalMiddleware(),
        new SubscriptionMiddleware(),
        new CorsMiddleware([], function (T_Subscription $subscription, string $origin) {
            return $subscription->isAllowedOrigin($origin);
        }),

        new AuthBasicMiddleware(new LambdaAuthValidator(function(BasicAuthToken $basicAuthToken, RepoConf $repoConf) {
            if (STANDALONE === true) {
                return true;
            }
            foreach ($repoConf->auth as $curAuth) {
                [$user, $hash] = explode(":", $curAuth);
                if ($basicAuthToken->user === $user && password_verify($basicAuthToken->passwd, $hash))
                    return true;
            }
            return false;
        })),
        new CallbackMiddleware(function ($request, $handler) use ($app) {

            return $handler->handle($request);
        }),
        new SendRedisMessageMw(),
        new RouterDispatchMiddleware([
            new JsonReturnFormatter($app)
        ]),
        new NotFoundMiddleware()
    ]);
});
