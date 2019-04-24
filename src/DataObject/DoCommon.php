<?php

namespace DorsetDigital\SilverStripeEventBrite\DataObject;

use SilverStripe\Control\Director;
use SilverStripe\View\Parsers\URLSegmentFilter;
use SilverStripe\View\Requirements;

trait DoCommon
{

    /**
     * @return bool
     */
    public function validURLSegment()
    {
        $do = self::get()->filter(['URLSegment' => $this->URLSegment]);
        if ($this->ID) {
            $do = $do->exclude('ID', $this->ID);
        }
        return !$do->exists();
    }

    /**
     * @param $title
     * @return string
     */
    public function generateURLSegment($title)
    {
        $filter = URLSegmentFilter::create();
        $filteredTitle = $filter->filter($title);

        // Fallback to generic page name if path is empty (= no valid, convertable characters)
        if (!$filteredTitle || $filteredTitle == '-' || $filteredTitle == '-1') {
            $filteredTitle = $this->ClassName . "-" . $this->ID;
        }

        return $filteredTitle;
    }


    public function setURLField()
    {
        // If there is no URLSegment set, generate one from Title
        $defaultSegment = $this->generateURLSegment('new-' . $this->i18n_singular_name());
        if ((!$this->URLSegment || $this->URLSegment == $defaultSegment) && $this->Title) {
            $this->URLSegment = $this->generateURLSegment($this->Title);
        } elseif ($this->isChanged('URLSegment', 2)) {
            $filter = URLSegmentFilter::create();
            $this->URLSegment = $filter->filter($this->URLSegment);
            // If after sanitising there is no URLSegment, give it a reasonable default
            if (!$this->URLSegment) {
                $this->URLSegment = $this->ClassName . "-" . $this->ID;
            }
        }

        // Ensure that this object has a non-conflicting URLSegment value.
        $count = 2;
        while (!$this->validURLSegment()) {
            $this->URLSegment = preg_replace('/-[0-9]+$/', null, $this->URLSegment) . '-' . $count;
            $count++;
        }
    }

    public function Link()
    {
        return $this->URLSegment;
    }

    public function AbsoluteLink()
    {
        return Director::absoluteURL($this->Link());
    }

    public static function getByURLSegment($segment)
    {
        return self::get()->filter(['URLSegment' => $segment])->first();
    }


    public function addCMSExtras()
    {
        Requirements::add_i18n_javascript('silverstripe/cms: client/lang', false, true);
    }


    function canView($member = null)
    {
        return true;
    }

}