<?php

define("DEV_MODE", (bool)"1");

const CONF_API_MOUNT = "/v1/pagebuilder";

if (DEV_MODE === true) {
    define("CONF_REPO_PATH", "/opt/mock/repos");

} else {
    define("CONF_REPO_PATH", "/data/repos");
}


