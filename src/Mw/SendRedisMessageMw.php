<?php

namespace Micx\PageBuilder\Mw;

use Brace\Core\Base\BraceAbstractMiddleware;
use Micx\PageBuilder\Type\RepoConf;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SendRedisMessageMw extends BraceAbstractMiddleware
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if ($request->getMethod() === "POST") {

        }

        if ($request->getMethod() === "POST" && CONF_REDIS_HOST !== "") {
            $repoConf = $this->app->get("repoConf", RepoConf::class);
            phore_file($repoConf->getRepoDir() . DEFAULT_IS_CHANGED_FILE)->touch();

            if (CONF_REDIS_HOST !== "") {
                $redis = $this->app->get("redis", \Redis::class);
                $redis->publish(CONF_REDIS_CHANNEL, $repoConf->__subscriptionId . "-" . $repoConf->__scopeId);
            }
        }
        return $response;
    }
}
