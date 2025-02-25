<?php

namespace Micx\PageBuilder\Helper;

use Phore\FileSystem\Exception\FileAccessException;
use Phore\FileSystem\Exception\FileNotFoundException;
use Phore\FileSystem\PhoreFile;

class FrontMatterFile
{

    public function __construct(
        public PhoreFile $file
    )
    {
    }


    public function read() : array
    {
        $content = $this->file->get_contents();

        $parts = explode("\n---\n", $content, 2);

        try {

            $data = phore_yaml_decode($parts[0]);
        } catch (\Exception $e) {
            throw new FileAccessException("Error parsing frontmatter: " . $e->getMessage() . " in file: " . $this->file->getUri());
        }

        $data["content"] = $parts[1];

        return $data;
    }

    public function write(array $data)
    {
        $content = $data["content"];
        unset($data["content"]);
        $data = "---\n" .  phore_yaml_encode($data) . "\n---\n" . "$content";
        $this->file->set_contents($data);
    }

    public static function ReadPage ($rootDir, $pid, $lang, &$errors=[], &$permalinks=[])
    {
        $rootDir = phore_dir($rootDir);

        if ($rootDir->withRelativePath($pid . ".$lang.md")->isFile()) {
            $f = new self($rootDir->withRelativePath($pid . ".$lang.md")->asFile());
            $data = $f->read();
            $data["ftype"] = "md";
        } else if ($rootDir->withRelativePath($pid . ".$lang.html")->isFile()) {
            $f = new self($rootDir->withRelativePath($pid . ".$lang.html")->asFile());
            $data = $f->read();
            $data["ftype"] = "html";
        } else {
            throw new FileNotFoundException("Page not found '$pid' (Lang: '$lang') location: $rootDir");
        }
        if ($data["pid"] !== $pid) {
            $errors[] = "Page '$pid' [$lang] frontmatter mismatch: pid";
            $data["pid"] = $pid;
        }
        if ($data["lang"] !== $lang) {
            $errors[] = "Page '$pid' [$lang] frontmatter mismatch: lang";
            $data["lang"] = $lang;
        }
        if (isset ($data["permalink"]) && $data["permalink"] !== null && $data["permalink"] !== "") {
            if (isset($permalinks[$data["permalink"]])) {
                $errors[] = "Permalink conflict '{$data['permalink']}': $pid [$lang] == {$permalinks[$data["permalink"]]}";
            }
            $permalinks[$data["permalink"]] = "$pid [$lang]";
        } else {
            $data["permalink"] = null; // default
        }
        return $data;
    }


    public static function WritePage ($rootDir, array $data)
    {
        $rootDir = phore_dir($rootDir);
        $file = $rootDir->withRelativePath($data["pid"] . ".{$data["lang"]}.md");
        if (isset ($data["ftype"]) && $data["ftype"] === "html") {
            $file = $rootDir->withRelativePath($data["pid"] . ".{$data["lang"]}.html");
        }

        $file->asFile()->touch();
        $f = new self($file->asFile());
        unset($data["ftype"]);
        ksort($data);
        $f->write($data);

    }
}
