<?php

namespace DorsetDigital\SilverStripeEventBrite\DataObject;

use DorsetDigital\SilverStripeEventBrite\Control\EventController;
use SilverStripe\ORM\DataObject;

class Event extends DataObject
{

    use DoCommon;

    private static $table_name = 'DD_Event';
    private static $singular_name = 'Event';
    private static $plural_name = 'Events';

    private static $db = [
        'EBID' => 'Varchar',
        'Status' => 'Varchar',
        'Title' => 'Varchar',
        'Description' => 'HTMLText',
        'EBURL' => 'Varchar(1000)',
        'Start' => 'Datetime',
        'End' => 'Datetime',
        'Image' => 'Varchar(1000)',
        'URLSegment' => 'Varchar',
        'Summary' => 'Varchar'
    ];

    private static $indexes = [
        'EBID' => true,
        'Status' => true
    ];

    private static $default_sort = 'Start';

    private static $summary_fields = [
        'Title' => 'Title',
        'Summary' => 'Summary',
        'Start.Nice' => 'Event Start'
    ];    

    /**
     * Find an event based on the EventBrite ID.  If none exists, create it.
     * @param $id
     * @return Event|DataObject|null
     * @throws \SilverStripe\ORM\ValidationException
     */
    public static function findOrMakeByID($id)
    {
        $event = self::get_one(self::class, ['EBID' => $id]);
        if (!$event) {
            $event = self::create();
            $event->EBID = $id;
            $event->write();
        }
        return $event;
    }

    public function Link()
    {
        $pageslug = EventController::config()->get('url_segment');
        return $pageslug . '/' . $this->URLSegment;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();
        $this->setURLField();
    }

    public function canView($member = null)
    {
        if ($this->Status == 'live') {
            return true;
        }
        return false;
    }


}
