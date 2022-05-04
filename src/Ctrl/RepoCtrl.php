<?php

namespace Micx\PageBuilder\Ctrl;

use Brace\Core\BraceApp;
use Brace\Router\Type\RouteParams;
use Laminas\Diactoros\ServerRequest;
use Micx\PageBuilder\Type\RepoConf;
use MongoDB\Driver\Server;
use Phore\VCS\VcsFactory;

class RepoCtrl
{
    public function __construct(
        public BraceApp $app
    ){}

    public function __invoke(RouteParams $routeParams, RepoConf $repoConf, ServerRequest $request)
    {
        $repo = new VcsFactory();
        $repo->setAuthSshPrivateKey(phore_file(CONF_SSH_KEY_FILE)->assertReadable()->get_contents());
        $repo->setCommitUser("pagebuilder", "pagebuilder@leuffen.de");
        $action = $request->getQueryParams()["a"] ?? null;
        if ($this->app->request->getMethod() === "GET") {
            $r = $repo->repository($repoConf->getRepoDir(), $repoConf->repo);
            if ( ! $r->exists()) {
                $r->pull();
            }
            if ($action === "pull") {
                $r->commit("autocommit on pull");
                $r->pull();
            }
            if ($action === "push") {
                $r->commit("autocommit on pull");
                $r->push();
            }
            return ["ok" => "success", "ref" => $r->getRev()];
        } else {


        }

    }
}
