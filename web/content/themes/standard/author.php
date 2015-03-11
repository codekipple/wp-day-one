<?php
/*
    The template for displaying Author Archive pages
*/

use CodekippleWPTheme\Controller\Controller as BaseController;

class AuthorController extends BaseController
{
    protected function setTemplates()
    {
        $this->templates = array(
            'author.twig',
            'archive.twig'
        );
    }

    public function indexAction()
    {
        global $wp_query;

        $author = new \TimberUser($wp_query->query_vars['author']);
        $this->context['author'] = $author;
        $this->context['title'] = 'Author Archives: ' . $author->name();
        $this->context['posts'] = \Timber::get_posts();

        parent::indexAction();
    }
}

new AuthorController;