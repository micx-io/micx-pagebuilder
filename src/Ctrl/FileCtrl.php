<?php

namespace Micx\PageBuilder\Ctrl;

use Brace\Core\BraceApp;
use Lack\Subscription\Type\T_Subscription;
use Micx\PageBuilder\Type\RepoConf;

class FileCtrl
{
    public function __construct(
        public BraceApp $app
    ){}

    public function __invoke(T_Subscription $subscription, RepoConf $repoConf)
    {
        return $repoConf;
    }
}
