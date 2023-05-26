<?php

namespace Micx\PageBuilder\Ctrl;

use Brace\Router\Type\RouteParams;
use Laminas\Diactoros\ServerRequest;
use Micx\PageBuilder\Helper\FrontMatterFile;
use Micx\PageBuilder\Type\RepoConf;

class PageListCtrl
{

    public function __construct(
        protected RepoConf $repoConf
    ){}

    public function __invoke(ServerRequest $request, RouteParams $routeParams)
    {
        $filterPageId = $request->getQueryParams()["filterPid"] ?? null;
        $docPath = $this->repoConf->getRepoDocPath();
        $permalinks = [];
        $list = [
            "sections" => [],
            "errors" => []
        ];
        foreach (phore_dir($docPath)->genWalk() as $path) {
            if ( ! $path->isDirectory())
                continue;

            if (startsWith($path->getRelPath(), "~") || startsWith($path->getRelPath(), "."))
                continue;

            $defFile = $path->withSubPath("_section.yml");
            if ( ! $defFile->exists())
                continue;

            if ($filterPageId !== null && explode("/", $filterPageId)[0] !== (string)$path->getRelPath())
                continue;

            $section = [
                "section_name" => $path->getRelPath(),
                "section" => $defFile->asFile()->get_yaml(),
                "pages" => []
            ];
            foreach ($path->asDirectory()->genWalk() as $file) {
                if ( ! $file->isFile())
                    continue;
                if ($file->getExtension() !== "html" && $file->getExtension() !== "md")
                    continue;

                $fileNameArr = explode(".", $file->getFilename());
                if (count($fileNameArr) !== 2)
                    continue;
                [$pageId, $lang] = $fileNameArr;

                if ($filterPageId !== null && $filterPageId !== $section["section_name"] . "/" . $pageId)
                    continue;

                try {
                    $frontmatter = FrontMatterFile::ReadPage($docPath, $section["section_name"] . "/$pageId", $lang, $list["errors"], $permalinks);
                    if ($frontmatter["pid"] !== $section["section_name"] . "/$pageId")
                        throw new \InvalidArgumentException("pid mismatch between filename and yaml content: '{$frontmatter['pid']}'");
                    if ($frontmatter["lang"] !== $lang)
                        throw new \InvalidArgumentException("lang mismatch between filename and yaml content: '{$frontmatter['lang']}'");
                } catch (\Exception $e) {
                    $list["errors"][] = ["file" => $file, "msg" => $e->getMessage()];
                    continue;
                }
                if ( ! isset($section["pages"][$pageId]))
                    $section["pages"][$pageId] = [];
                $section["pages"][$pageId][$lang] = $frontmatter;
            }
            $list["sections"][] = $section;
        }
        return $list;
    }

}
