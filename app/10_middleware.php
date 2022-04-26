<?php

namespace App;


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

        new CallbackMiddleware(function ($request, $handler) use ($app) {
            $app->define("repoConf", new DiService(function (T_Subscription $subscription, RouteParams $routeParams) {
                $scopeId = $routeParams->get("scope_id");
                $data = $subscription->getClientPrivateConfig(null);
                out($data);
                if ( ! isset ($data["scopes"][$scopeId]))
                    throw new \InvalidArgumentException("Invalid scope: '$scopeId' in subscription '$subscription->subscription_id'");
                $scope = phore_hydrate($data["scopes"][$scopeId], RepoConf::class);
                $scope->__subscriptionId = $subscription->subscription_id;
                $scope->__scopeId = $scopeId;
                return $scope;
            }));
            return $handler->handle($request);
        }),

        new RouterDispatchMiddleware([
            new JsonReturnFormatter($app)
        ]),
        new NotFoundMiddleware()
    ]);
});
