<?php
/*
	The main template file
	This is the most generic template file in a WordPress theme
	and one of the two required files for a theme (the other being style.css).
	It is used to display a page when nothing more specific matches a query.
	E.g., it puts together the home page when no home.php file
*/

use CodekippleWordPressTheme\Controller\Controller as BaseController;

class IndexController extends BaseController
{
    protected function setTemplates()
    {
        $this->templates = array(
            'index.twig'
        );
    }

    public function indexAction()
    {
        if (!class_exists('Timber')) {
        	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
        }

        $this->context['posts'] = \Timber::get_posts();

        parent::indexAction();
    }
}

new IndexController;