<?php

namespace CodekippleWPTheme\Controller;

use CodekippleWPTheme\Api\Images as Images;
use CodekippleWPTheme\Api\Assets as Assets;

abstract class Controller
{
    protected $context;
    protected $templates;

    function __construct() {
        $this->context = \Timber::get_context();

        // environment (dev, demo, live)
        $this->context['WP_ENV'] = WP_ENV;

        // live reload
        $this->context['live_reload'] = Assets\live_reload();

        $this->setMenus();

        $this->indexAction();
    }

    protected function setMenus()
    {
        $this->context['menu_header'] = new \TimberMenu('header');
        $this->context['menu_main'] = new \TimberMenu('main');
        $this->context['menu_footer'] = new \TimberMenu('footer');
    }

    protected function setTemplates()
    {
        $this->templates = array(
            'page.twig'
        );
    }

    protected function indexAction()
    {
        // views
        $this->setTemplates();

        \Timber::render($this->templates, $this->context);
    }
}