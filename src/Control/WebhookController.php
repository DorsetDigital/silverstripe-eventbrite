<?php

namespace DorsetDigital\SilverStripeEventBrite\Control;

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;

class WebhookController extends ContentController
{
    public function index(HTTPRequest $request)
    {
        $dir = Director::baseFolder().DIRECTORY_SEPARATOR.'eb_submissions';
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $stamp = time();

        file_put_contents($dir.DIRECTORY_SEPARATOR.$stamp.".txt", print_r($request->requestVars(), true));
        file_put_contents($dir.DIRECTORY_SEPARATOR.$stamp.".body.txt", $request->getBody());
        return "OK";
    }
}