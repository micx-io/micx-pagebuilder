<?php

namespace Micx\PageBuilder\Ctrl;

use Brace\Core\BraceApp;
use Psr\Http\Message\ServerRequestInterface;

class JsCtrl
{

    public function __invoke (BraceApp $app, string $subscriptionId, Config $config, ServerRequestInterface $request) {
        $data = file_get_contents(__DIR__ . "/../src/formmail.js");

        $error = "";
        $origin = $request->getHeader("referer")[0] ?? null;
        if ($origin !== null && ! origin_match($origin, $config->allow_origins)) {
            $error = "Invalid origin: '$origin' - not allowed for subscription_id '$subscriptionId'";
        }

        $data = str_replace(
            ["%%ENDPOINT_URL%%", "%%ERROR%%"],
            [
                "//" . $app->request->getUri()->getHost() . "/v1/formmailer/send?subscription_id=$subscriptionId",
                $error
            ],
            $data
        );

        return $app->responseFactory->createResponseWithBody($data, 200, ["Content-Type" => "application/javascript"]);
    }

}
