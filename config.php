<?php

define("DEV_MODE", (bool)"1");
define("CONF_SSH_KEY_FILE", "/opt/ssh_keys");
define("STANDALONE", (bool)"1");
define("STANDALONE_PATH", "/opt/mock/repos/demo1-weba");
define("STANDALONE_DOC_PATH", "/docs");

const CONF_API_MOUNT = "/v1/pagebuilder";

if (DEV_MODE === true) {
    define("CONF_REPO_PATH", "/opt/mock/repos");

} else {
    define("CONF_REPO_PATH", "/data");
}


