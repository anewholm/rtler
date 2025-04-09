<?php

namespace AcornAssociated\Rtler;

use App;
use Lang;
use Event;
use Config;
use Backend;
use Request;
use AcornAssociated\Rtler\Classes\UrlGenerator;
use AcornAssociated\Rtler\Models\Settings;
use System\Classes\PluginBase;

/**
 * It shifts the controller from right to left
 *
 * @package AcornAssociated\Rtler
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
            'name'        => 'acornassociated.rtler::lang.plugin.name',
            'description' => 'acornassociated.rtler::lang.plugin.description',
            'author'      => 'Acorn Associated',
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
                $controller->addCss(Config::get('cms.pluginsPath') . ('/acornassociated/rtler/assets/css/rtler.css'));
                $controller->addJs(Config::get('cms.pluginsPath') . ('/acornassociated/rtler/assets/js/rtler.min.js'));
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
            'acornassociated.rtler.change_settings' => [
                'tab' => 'acornassociated.rtler::lang.permissions.tab',
                'label' => 'acornassociated.rtler::lang.permissions.label'
            ],
        ];
    }


    protected function registerUrlGenerator()
{
    $this->app->singleton('url', function ($app) {
        $routes = $app['router']->getRoutes();
        $url = new \AcornAssociated\Rtler\Classes\UrlGenerator(
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
                'label'       => 'acornassociated.rtler::lang.setting.menu',
                'description' => 'acornassociated.rtler::lang.setting.description',
                'category'    => 'AcornAssociated',
                'icon'        => 'icon-anchor',
                'class'       => 'AcornAssociated\Rtler\Models\Settings',
                'order'       => 500,
                'keywords'    => 'acornassociated rtler',
                'permissions' => ['acornassociated.rtler.change_settings']
            ]
        ];
    }
}
