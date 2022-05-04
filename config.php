<?php

define("DEV_MODE", (bool)"1");
define("CONF_SSH_KEY_FILE", "/opt/ssh_keys");

define("STANDALONE", (bool)"0");
define("STANDALONE_PATH", "/opt/mock/repos/demo1-weba");
define("STANDALONE_DOC_PATH", "/docs");

define("CONF_SUBSCRIPTION_ENDPOINT", "/opt/mock/sub");
define("CONF_SUBSCRIPTION_CLIENT_ID", "micx-pagebuilder");
define("CONF_SUBSCRIPTION_CLIENT_SECRET", "");


const CONF_API_MOUNT = "/v1/pagebuilder";

if (DEV_MODE === true) {
    define("CONF_REPO_PATH", "/opt/mock/repos");

} else {
    define("CONF_REPO_PATH", "/data");
}


