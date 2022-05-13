<?php

define("DEV_MODE", (bool)"1");
define("CONF_SSH_KEY_FILE", "/opt/ssh_keys");

define("STANDALONE", (bool)"0");
define("STANDALONE_PATH", "/opt/mock/repos/demo1-weba");
define("STANDALONE_DOC_PATH", "/docs");

define("CONF_SUBSCRIPTION_ENDPOINT", "/opt/mock/sub");
define("CONF_SUBSCRIPTION_CLIENT_ID", "micx-pagebuilder");
define("CONF_SUBSCRIPTION_CLIENT_SECRET", "");

define("CONF_REDIS_HOST", "redis");
define("CONF_REDIS_PORT", "6379");
define("CONF_REDIS_CHANNEL", "micx.pagebuilder.publish");

define("CONF_PREVIEW_HOST", "http://prev.micx.io");

const CONF_API_MOUNT = "/v1/pagebuilder";

const DEFAULT_IS_CHANGED_FILE = "/.git/pb_chanded";

if (DEV_MODE === true) {
    define("CONF_REPO_PATH", "/opt/mock/repos");

} else {
    define("CONF_REPO_PATH", "/data");
}


