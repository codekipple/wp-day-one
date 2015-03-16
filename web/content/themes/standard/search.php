<?php
/*
    Search results page
*/

$context = Timber::get_context();
$context['title'] = 'Search results for '. get_search_query();
$context['posts'] = Timber::get_posts();
$templates = array(
    'search.twig',
    'archive.twig',
    'index.twig'
);

Timber::render($templates, $context);