<?php

namespace Micx\PageBuilder\Ctrl;

use Brace\Core\BraceApp;
use Brace\Router\Type\RouteParams;
use Micx\PageBuilder\Type\RepoConf;
use Phore\VCS\VcsFactory;

class RepoCtrl
{
    public function __construct(
        public BraceApp $app
    ){}

    public function __invoke(RouteParams $routeParams, RepoConf $repoConf)
    {
        $repo = new VcsFactory();
        $repo->setAuthSshPrivateKey(phore_file(CONF_SSH_KEY_FILE)->assertReadable()->get_contents());

        if ($this->app->request->getMethod() === "GET") {
            $r = $repo->repository($repoConf->getRepoDir(), $repoConf->repo);
            if ( ! $r->exists()) {
                $r->pull();
            }
            return ["ok" => "success", "ref" => $r->getRev()];
        } else {
            $body = $this->app->get("body");
            $file = $repoConf->getRepoDocPath($routeParams->get("file"));
            if ($file->getExtension() === "yml") {
                $file->asFile()->set_yaml(phore_yaml_decode($body));
                return ["ok" => "success"];
            }

        }

    }
}
