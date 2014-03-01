<?php
/*
    Search results page

    Methods for TimberHelper can be found in the /functions sub-directory
*/

use CodekippleWordPressTheme\Controller\Controller as BaseController;

class SearchController extends BaseController
{
    protected function setTemplates()
    {
        $this->templates = array(
            'search.twig',
            'archive.twig',
            'index.twig'
        );
    }

    public function indexAction()
    {
        $this->context['title'] = 'Search results for '. \get_search_query();
        $this->context['posts'] = \Timber::get_posts();

        parent::indexAction();
    }
}

new SearchController;