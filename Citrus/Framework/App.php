<?php

namespace Citrus\Framework;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\HttpCache;
use Symfony\Component\HttpKernel\HttpCache\Store;

use Citrus\Core\Event\EventServiceProvider;
use Citrus\Core\Routing\RoutingServiceProvider;
use Citrus\Core\KernelServiceProvider;
use Citrus\Core\App as CitrusApp;

use Citrus\Framework\Controller\ControllerResolverServiceProvider;
use Citrus\Framework\Routing\RoutingConfigLoader;
use Citrus\Framework\View\TemplateEngineServiceProvider;
use Citrus\Framework\Config\MainConfigLoader;

class App extends CitrusApp
{
    const VERSION = '2.0a-DEV';

    protected $paths     = Array();

    protected $container = Array();

    protected $providers = Array();

    protected $context;

    public function __construct(Request $request, $debug = false)
    {
        $this['request'] = $request;
        $this['debug']   = $debug;
        $this->loadConfig();
    }

    public function loadConfig()
    {
        if (!isset($this['dir'])) {
            throw new \RuntimeException("Application directory is not defined. Unable to load configuration");
        }

        $routingLoader = new RoutingConfigLoader($this['dir'] . '/config/routing.json');
        $this['routes'] = $routingLoader->getContent();

        $config = new MainConfigLoader($this['dir'] . '/config/config.json');
        $this['config'] = $config->getContent();
    }

    /*public function __get($name) {
        return $this[$name];
    }*/

    public function run()
    {
        $this->boot();
        if ($this['debug'] === false) {
            $this['http_cache'] = new HttpCache($this['kernel'], new Store($this['dir'] . '/cache'), null, array(
                "debug" => $this['debug']
            ));
            $kernel = $this['http_cache'];
        } else {
            $kernel = $this['kernel'];
        }
        $response = $kernel->handle($this['request'])->send();
        return $response;

    }

    public function registerCoreProviders()
    {

        // $this->registerProvider(new LoggerServiceProvider, "logger");
        // $this->registerProvider(new HttpCacheServiceProvider($this));

        $this->registerProvider(new EventServiceProvider());
        $this->registerProvider(new RoutingServiceProvider());
        $this->registerProvider(new ControllerResolverServiceProvider());
        $this->registerProvider(new KernelServiceProvider());
        // $this->registerProvider(new ViewServiceProvider());
        $this->registerProvider(new TemplateEngineServiceProvider());
    }
}
