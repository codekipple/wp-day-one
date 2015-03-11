<?php
/*
	The template for displaying Archive pages.

	Used to display archive-type pages if nothing more specific matches a query.
	For example, puts together date-based pages if no date.php file exists.

	Learn more: http://codex.wordpress.org/Template_Hierarchy
*/

use CodekippleWPTheme\Controller\Controller as BaseController;

class ArchiveController extends BaseController
{
    protected function setTemplates()
    {
        $this->templates = array(
            'archive.twig',
            'index.twig'
        );
    }

    public function indexAction()
    {
		$this->context['title'] = 'Archive';

		if (is_day()) {
			$this->context['title'] = 'Archive: '.get_the_date( 'D M Y' );
		} else if (is_month()) {
			$this->context['title'] = 'Archive: '.get_the_date( 'M Y' );
		} else if (is_year()) {
			$this->context['title'] = 'Archive: '.get_the_date( 'Y' );
		} else if (is_tag()) {
			$this->context['title'] = single_tag_title('', false);
		} else if (is_category()) {
			$this->context['title'] = single_cat_title('', false);
			array_unshift($templates, 'archive-'.get_query_var('cat').'.twig');
		} else if (is_post_type_archive()) {
			$this->context['title'] = post_type_archive_title('', false);
			array_unshift($templates, 'archive-'.get_post_type().'.twig');
		}

        $this->context['posts'] = \Timber::get_posts();

        parent::indexAction();
    }
}

new ArchiveController;