<?php
/*
    The template for displaying 404 pages (Not Found)
*/

use CodekippleWordPressTheme\Controller\Controller as BaseController;

class FourOhFourController extends BaseController
{
    function __construct() {
        $this->context = \Timber::get_context();

        $this->setTemplates();
        $this->indexAction();
    }

    protected function setTemplates()
    {
        $this->templates = array('404.twig');
    }

}

new FourOhFourController;