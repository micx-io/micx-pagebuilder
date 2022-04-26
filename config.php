<?php

define("DEV_MODE", (bool)"0");

const CONF_API_MOUNT = "/v1/pagebuilder";

if (DEV_MODE === true) {
    define("CONFIG_PATH", "/opt/cfg");
} else {
    define("CONFIG_PATH", "/config");
}


