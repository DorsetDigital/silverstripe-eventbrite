<?php

namespace DorsetDigital\SilverStripeEventBrite\Dev;

use DorsetDigital\SilverStripeEventBrite\DataObject\Event;
use DorsetDigital\SilverStripeEventBrite\Model\EventBriteConnector;
use SilverStripe\Dev\BuildTask;

class EventSync extends BuildTask
{
    public function run($request)
    {
        $eb = EventBriteConnector::create();
        $res = $eb->getEventsList()->toArray();

        echo "<pre>";
        foreach ($res['events'] as $event) {
            $id = $event['id'];
            $eventObject = Event::findOrMakeByID($id);
            $eventObject->Title = $event['name']['text'];
            $eventObject->EBURL = $event['url'];
            $eventObject->Start = $event['start']['local'];
            $eventObject->End = $event['end']['local'];
            $eventObject->Status = $event['status'];
            $eventObject->Image = $event['logo']['original']['url'];
            $eventObject->Summary = $event['summary'];

            $description = $eb->getEventDescription($id);
            $eventObject->Description = $description;

            $eventObject->write();

            echo "<br/>Event ID ".$id." (".$event['name']['text'].") updated";
        }
        echo "</pre>";
    }

}