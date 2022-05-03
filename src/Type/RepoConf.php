<?php

namespace Micx\PageBuilder\Type;

use Phore\FileSystem\PhoreDirectory;
use Phore\FileSystem\PhoreFile;
use Phore\FileSystem\PhoreUri;

class RepoConf
{
    /**
     * @var string|null
     */
    public string|null $__subscriptionId;

    /**
     * @var string|null
     */
    public string|null $__scopeId;


    public string $repo;

    public string $doc_dir;


    /**
     * The directory the git-repo is cloned into
     *
     * @return PhoreDirectory
     */
    public function getRepoDir() : PhoreDirectory
    {
        return phore_dir(CONF_REPO_PATH . "/$this->__subscriptionId-$this->__scopeId");
    }

    public function getRepoDocPath($path = null) : PhoreUri
    {
        return $this->getRepoDir()->withRelativePath((string)$this->doc_dir)->withRelativePath((string)$path);
    }


}
