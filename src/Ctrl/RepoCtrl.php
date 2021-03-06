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

    public function __invoke(RouteParams $routeParams, RepoConf $repoConf, ServerRequest $request, VcsFactory $vcsFactory)
    {
        ignore_user_abort(true);
        $action = $request->getQueryParams()["a"] ?? null;
        if ($this->app->request->getMethod() === "GET") {
            $r = $vcsFactory->repository($repoConf->getRepoDir(), $repoConf->repo);
            if ( ! $r->exists()) {
                $r->pull();
            }
            if ($action === "pull") {
                $r->commit("autocommit on pull");
                $r->pull();
            }
            if ($action === "push") {
                $r->commit("autocommit on pull");
                $r->pull();
                $r->push();
                $changedFile = phore_file($repoConf->getRepoDir() . DEFAULT_IS_CHANGED_FILE);
                if ($changedFile->exists())
                    $changedFile->unlink();
            }
            return ["ok" => "success", "ref" => $r->getRev()];
        } else {


        }

    }
}
