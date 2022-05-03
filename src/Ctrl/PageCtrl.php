<?php

namespace Micx\PageBuilder\Ctrl;

use Brace\Router\Type\RouteParams;
use Laminas\Diactoros\ServerRequest;
use Micx\PageBuilder\Helper\FrontMatterFile;
use Micx\PageBuilder\Type\RepoConf;

class PageCtrl
{

    public function __construct(
        protected RepoConf $repoConf
    ){}

    public function __invoke(ServerRequest $request, RouteParams $routeParams)
    {
        $pageId = $routeParams->get("page_id");
        $lang = $request->getQueryParams()["lang"] ?? throw new \InvalidArgumentException("Parameter lang missing");

        if ($request->getMethod() === "GET") {
            return FrontMatterFile::ReadPage($this->repoConf->getRepoDocPath(), $pageId, $lang);
        } elseif ($request->getMethod() === "POST") {

        }
    }

}
