<?php

namespace Micx\PageBuilder\Ctrl;

use Brace\Core\BraceApp;
use Brace\Router\Type\RouteParams;
use Laminas\Diactoros\ServerRequest;
use Micx\PageBuilder\Helper\FrontMatterFile;
use Micx\PageBuilder\Type\RepoConf;
use Phore\Core\Exception\NotFoundException;

class PageCtrl
{

    public function __construct(
        protected RepoConf $repoConf,
        protected BraceApp $app
    ){}


    public static function copyPage(ServerRequest $request, RouteParams $routeParams, RepoConf $repoConf) {
        $srcPageId = $request->getQueryParams()["src_page_id"];
        $targetPageId = $request->getQueryParams()["target_page_id"];
        $srcLang = $request->getQueryParams()["src_lang"];
        $targetLang = $request->getQueryParams()["target_lang"];

        $data = FrontMatterFile::ReadPage($repoConf->getRepoDocPath(), $srcPageId, $srcLang);
        $data["pid"] = $targetPageId;
        $data["lang"] = $targetLang;
        unset($data["permalink"]);
        FrontMatterFile::WritePage($repoConf->getRepoDocPath(), $data);
        return ["ok"=>"success"];
    }



    public function __invoke(ServerRequest $request, RouteParams $routeParams)
    {
        $pageId = $routeParams->get("page_id");
        $lang = $request->getQueryParams()["lang"] ?? throw new \InvalidArgumentException("Parameter lang missing");

        if ($request->getMethod() === "GET") {
            try {
                return FrontMatterFile::ReadPage($this->repoConf->getRepoDocPath(), $pageId, $lang);
            } catch (NotFoundException $e) {
                return ["found" => false];
            }
        } elseif ($request->getMethod() === "POST") {
            $body = $this->app->get("body");
            FrontMatterFile::WritePage($this->repoConf->getRepoDocPath(), phore_json_decode($body));
            return ["ok" => true];
        }
    }
}
