<?php

namespace Bolt\Extension\Bolt\SpaceAPI;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * SpaceAPI Controller
 *
 * @author realitygaps <bolt@gapsinreality.com> 
 */
class Controller
{
    /**
     * @var Silex\Application
     */
    private $app;

    /**
     * @var Extension config array
     */
    private $config;

    public function __construct(\Bolt\Application $app)
    {
        $this->app = $app;
        $this->config = $this->app['extensions.' . Extension::NAME]->config;
    }

    public function spaceapifeed()
    {
        // jQuery not required
        $this->app['extensions']->disableJquery();

        // Clear snippet list. There's no clear any more, so just set to null
        $this->app['extensions']->clearSnippetQueue();
        $this->app['debugbar'] = false;

        // Defaults for later
        $template = 'spaceapi.twig';

	      $spaceapi_info = array(
    		 'api' => $this->config['api'],
     		 'space' => $this->config['space'],
    		 'logo' => $this->config['logo'],
    		 'url' => $this->config['url'],
    		 'locationaddress' => $this->config['locationaddress'],
    		 'lon' => $this->config['lon'],
    		 'lat' => $this->config['lat'],
    		 'email' => $this->config['email'],
    		 'irc' => $this->config['irc'],
    		 'ml' => $this->config['ml'],
		     'twitter' =>$this->config['twitter'],
    		 'openicon' => $this->config['openicon'],
    		 'closedicon' => $this->config['closedicon'],
    		 'state' => $this->statejson(),
    		 'projects' => $this->config['projects']);

        $this->app['twig.loader.filesystem']->addPath(dirname(__DIR__) . '/assets/');

        $body = $this->app['render']->render($template, array(
            'spaceapi' => $spaceapi_info
        ));
        return new Response($body, 200,
            array(
                'Content-Type'  => 'application/json; charset=utf-8',
                'Cache-Control' => 's-maxage=3600, public',
            )
        );
    }

    public function getState()
    {
      $state = file_get_contents("thestate");
      return $state;
    }

    public function setState($state="Open")
    {
      file_put_contents("thestate",$state);
      return $state;
    }

    public function toggleState()
    {
      $state = $this->getState();
      if ($state == 'Open') { $state = 'Closed'; } else { $state = 'Open'; }
      $newstate = $this->setState($state);
      return 'Space is now ' . $newstate;
    }

    private function statejson()
    { 
      if($this->getState() == "Closed") {
        return "false";
      }
      else
      {
        return "true";
      }
    }
}
