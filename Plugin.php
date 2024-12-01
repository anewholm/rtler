<?php

namespace Acorn\Rtler;

use App;
use Lang;
use Event;
use Config;
use Backend;
use Request;
use Acorn\Rtler\Classes\UrlGenerator;
use Acorn\Rtler\Models\Settings;
use System\Classes\PluginBase;

/**
 * It shifts the controller from right to left
 *
 * @package Acorn\Rtler
 * @author Jaber Rasul
 */
class Plugin extends PluginBase
{
    /**
     * @var bool Plugin requires elevated permissions.
     */
    public $elevated = true;

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'acorn.rtler::lang.plugin.name',
            'description' => 'acorn.rtler::lang.plugin.description',
            'author'      => 'Acorn',
            'icon'        => 'icon-anchor'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        // Check if we are currently in backend module.
        if (!App::runningInBackend()) {
            return;
        }
        $this->registerUrlGenerator();
        // Listen for `backend.page.beforeDisplay` event.
        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            if (!Request::ajax() && UrlGenerator::checkForRtl('layout_mode')) {
                $controller->addCss(Config::get('cms.pluginsPath') . ('/acorn/rtler/assets/css/rtler.css'));
                $controller->addJs(Config::get('cms.pluginsPath') . ('/acorn/rtler/assets/js/rtler.min.js'));
                $controller->bodyClass = "rtl $controller->bodyClass";
            }
        });
    }


    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'acorn.rtler.change_settings' => [
                'tab' => 'acorn.rtler::lang.permissions.tab',
                'label' => 'acorn.rtler::lang.permissions.label'
            ],
        ];
    }


    protected function registerUrlGenerator()
{
    $this->app->singleton('url', function ($app) {
        $routes = $app['router']->getRoutes();
        $url = new \Acorn\Rtler\Classes\UrlGenerator(
            $routes,
            $app->rebinding(
                'request',
                $this->requestRebinder()
            )
        );
        $url->setSessionResolver(function () {
            return $this->app['session'];
        });
        $app->rebinding('routes', function ($app, $routes) {
            $app['url']->setRoutes($routes);
        });
        return $url;
    });
}


    protected function requestRebinder()
    {
        return function ($app, $request) {
            $app['url']->setRequest($request);
        };
    }

    public function registerSettings()
    {
        return [
            'rtler' => [
                'label'       => 'acorn.rtler::lang.setting.menu',
                'description' => 'acorn.rtler::lang.setting.description',
                'category'    => 'Acorn',
                'icon'        => 'icon-anchor',
                'class'       => 'Acorn\Rtler\Models\Settings',
                'order'       => 500,
                'keywords'    => 'acorn rtler',
                'permissions' => ['acorn.rtler.change_settings']
            ]
        ];
    }
}
