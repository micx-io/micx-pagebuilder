<?php

namespace Micx\PageBuilder\Ctrl;

use Brace\Core\BraceApp;
use Brace\Router\Type\RouteParams;
use Lack\Subscription\Type\T_Subscription;
use Micx\PageBuilder\Type\RepoConf;

class FileCtrl
{
    public function __construct(
        public BraceApp $app
    ){}

    public function __invoke(RouteParams $routeParams, RepoConf $repoConf)
    {
        if ($this->app->request->getMethod() === "GET") {
            $file = $repoConf->getRepoDocPath($routeParams->get("file"));
            if ($file->getExtension() === "yml")
                return $file->asFile()->assertReadable()->get_yaml();
        } else {
            $body = $this->app->get("body");
            $file = $repoConf->getRepoDocPath($routeParams->get("file"));
            if ($file->getExtension() === "yml") {
                $file->asFile()->set_yaml(phore_json_decode($body));
                return ["ok" => "success"];
            }

        }

    }
}
