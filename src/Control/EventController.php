<?php

namespace DorsetDigital\SilverStripeEventBrite\Control;

use DorsetDigital\SilverStripeEventBrite\DataObject\Event;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\View\Requirements;

class EventController extends \PageController
{
    use Configurable;
    use Injectable;

    /**
     * @config
     * @var string
     */
    private static $url_segment;

    private static $url_handlers = [
        '//$ID' => 'index'
    ];

    public function index(HTTPRequest $request)
    {
        Requirements::themedCSS('client/dist/css/events');
        $eventslug = $request->param('ID');
        $event = Event::getByURLSegment($eventslug);
        if (!$event) {
            return $this->httpError(404);
        }
        $this->MetaTitle = $event->Title . " - " . date('d/m/Y', strtotime($event->Start));
        $this->Layout = $event->renderWith('EventPage');
        return $this->render();
    }

}