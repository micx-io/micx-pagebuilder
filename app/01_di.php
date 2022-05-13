<?php
namespace App;

use Brace\Core\AppLoader;
use Brace\Core\BraceApp;
use Brace\Dbg\BraceDbg;
use Brace\Mod\Request\Zend\BraceRequestLaminasModule;
use Brace\Router\RouterModule;
use Brace\Router\Type\RouteParams;
use Lack\Subscription\Brace\SubscriptionClientModule;
use Lack\Subscription\Type\T_Subscription;
use Micx\FormMailer\Config\Config;
use Micx\PageBuilder\Type\RepoConf;
use Phore\Di\Container\Producer\DiService;
use Phore\Di\Container\Producer\DiValue;
use Phore\VCS\VcsFactory;
use Psr\Http\Message\ServerRequestInterface;


BraceDbg::SetupEnvironment(true, ["192.168.178.20", "localhost", "localhost:5000", "pagebuilder.leuffen.de"]);


AppLoader::extend(function () {
    $app = new BraceApp();
    $app->addModule(new BraceRequestLaminasModule());
    $app->addModule(new RouterModule());
    $app->addModule(
        new SubscriptionClientModule(
            CONF_SUBSCRIPTION_ENDPOINT,
            CONF_SUBSCRIPTION_CLIENT_ID,
            CONF_SUBSCRIPTION_CLIENT_SECRET
        )
    );

    $app->define("vcsFactory", new DiService(function () {
        $repo = new VcsFactory();
        $repo->setAuthSshPrivateKey(phore_file(CONF_SSH_KEY_FILE)->assertReadable()->get_contents());
        $repo->setCommitUser("pagebuilder", "pagebuilder@leuffen.de");
        return $repo;
    }));

    $app->define("redis", new DiService(function () {
        $redis = new \Redis();
        $redis->connect(CONF_REDIS_HOST, CONF_REDIS_PORT);
        return $redis;
    }));


    $app->define("repoConf", new DiService(function (T_Subscription $subscription, RouteParams $routeParams) {
        if (STANDALONE === true) {
            $repoConf = new RepoConf();
            $repoConf->__scopeId = "weba";
            $repoConf->__subscriptionId = "demo1";
            $repoConf->repo_dir = STANDALONE_PATH;
            $repoConf->doc_dir = STANDALONE_DOC_PATH;
            $repoConf->info_domain = "<standalone>";
            return $repoConf;
        }
        $scopeId = $routeParams->get("scope_id");
        $data = $subscription->getClientPrivateConfig(null);

        if ( ! isset ($data["scopes"][$scopeId]))
            throw new \InvalidArgumentException("Invalid scope: '$scopeId' in subscription '$subscription->subscription_id'");
        $scope = phore_hydrate($data["scopes"][$scopeId], RepoConf::class);
        $scope->__subscriptionId = $subscription->subscription_id;
        $scope->__scopeId = $scopeId;
        $scope->repo_dir = CONF_REPO_PATH . "/$scope->__subscriptionId-$scope->__scopeId";
        return $scope;
    }));
    $app->define("app", new DiValue($app));


    return $app;
});
