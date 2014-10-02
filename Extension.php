<?php

namespace Bolt\Extension\Bolt\SpaceAPI;

/**
 * Spaceapi extension for Bolt
 *
 * @author realitygaps <bolt@gapsinreality.com> 
 */
class Extension extends \Bolt\BaseExtension
{
    const NAME = 'SpaceAPI';

    public function getName()
    {
        return Extension::NAME;
    }

    public function initialize()
    {
        // Set up routes
        $this->setController();
    }

    /**
     * Create controller and define routes
     */
    private function setController()
    {
        // Create controller object
        $this->controller = new Controller($this->app);

        // Sitewide feed
        $this->app->match('/spaceapi/spacestate.json', array($this->controller, 'spaceapifeed'));

        $this->app->match('/spaceapi/spacestate', array($this->controller, 'spaceState'));
        
        $this->app->match('/spaceapi/togglestate', array($this->controller, 'toggleState'));
        
    }
}
