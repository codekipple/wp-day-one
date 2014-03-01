<?php
/*
    The Template for displaying all single posts
*/

use CodekippleWordPressTheme\Controller\Controller as BaseController;

class SingleController extends BaseController
{
    protected function setTemplates()
    {
        $this->templates = array(
            'single-' . $this->context['post']->ID . '.twig',
            'single-' . $this->context['post']->post_type . '.twig',
            'single.twig'
        );
    }

    public function indexAction()
    {
        $this->context['comment_form'] = \TimberHelper::get_comment_form();

        parent::indexAction();
    }
}

new SingleController;