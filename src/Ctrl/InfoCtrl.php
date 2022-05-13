<?php

namespace Micx\PageBuilder\Ctrl;

use Brace\Core\BraceApp;
use Brace\Router\Type\RouteParams;
use Micx\PageBuilder\Type\RepoConf;

class InfoCtrl
{
    public function __construct(
        public BraceApp $app
    ){}

    public function __invoke(RouteParams $routeParams, RepoConf $repoConf)
    {
        return [
            "info_domain" => $repoConf->info_domain,
            "preview_url" => CONF_PREVIEW_HOST . "/" . $repoConf->getPathId(),
            "has_changes" => phore_file($repoConf->getRepoDir() . DEFAULT_IS_CHANGED_FILE)->exists()
        ];
    }
}
