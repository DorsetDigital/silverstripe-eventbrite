<?php

namespace DorsetDigital\SilverStripeEventBrite\Extension;

use DorsetDigital\SilverStripeEventBrite\DataObject\Event;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Security;

class PageExtension extends Extension
{
    public function getComingEvents($count = 4)
    {
        return Event::get()->filter([
            'Status' => 'live',
            'Start:GreaterThan' => DBDatetime::now()
        ])->limit($count);
    }

}