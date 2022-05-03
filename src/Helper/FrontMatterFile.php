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

        $parts = explode("\n---\n", $content);

        $data = phore_yaml_decode($parts[0]);

        $data["content"] = $parts[1];

        return $data;
    }

    public function write(array $data, $content)
    {
        $data = phore_yaml_encode($data) . "\n$content";
        $this->file->set_contents($data);
    }


    public static function ReadPage ($rootDir, $pid, $lang)
    {
        $rootDir = phore_dir($rootDir);
        
        if ($rootDir->withRelativePath($pid . ".$lang.md")->isFile()) {
            $f = new self($rootDir->withRelativePath($pid . ".$lang.md")->asFile());
            $data = $f->read();
            $data["pid"] = $pid;
            $data["lang"] = $lang;
            $data["ftype"] = "html";
            return $data;
        } else if ($rootDir->withRelativePath($pid . ".$lang.html")->isFile()) {
            $f = new self($rootDir->withRelativePath($pid . ".$lang.html")->asFile());
            $data = $f->read();
            $data["pid"] = $pid;
            $data["lang"] = $lang;
            $data["ftype"] = "html";
            return $data;
        }
        throw new FileNotFoundException("Page not found '$pid' (Lang: '$lang') location: $rootDir");

    }


}
