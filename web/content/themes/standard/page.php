<?php
/*
    The template for displaying all pages.
*/

use CodekippleWPTheme\Controller\Controller as BaseController;

class PageController extends BaseController
{
    public function indexAction()
    {
        global $post;

        parent::indexAction();
    }
}

new PageController;