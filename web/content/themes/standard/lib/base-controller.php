<?php

namespace CodekippleWordPressTheme\Controller;

use CodekippleWordPressTheme\Api\Image as Images;
use CodekippleWordPressTheme\Api\Hero as Hero;

abstract class Controller
{
    protected $context;
    protected $templates;

    function __construct() {
        $this->context = \Timber::get_context();
        $this->context['post'] = new \TimberPost();

        $this->setTemplates();
        $this->indexAction();
    }

    /*
        Uses the Imager class
        =====================

        $options = array(
            'image' => 'path/to/image.jpg',
            'width' => 500,
            'height' => 500,
            'mode' => 'inset'
        )
    */
    protected function image($options)
    {
        $imager = new Images\Imager();
        $image = $imager->get_image($options);

        return $image;
    }

    protected function setTemplates()
    {
        $this->templates = array(
            'page-' . $this->context['post']->post_name . '.twig',
            'page.twig'
        );
    }

    public function indexAction()
    {
        \Timber::render($this->templates, $this->context);
    }
}