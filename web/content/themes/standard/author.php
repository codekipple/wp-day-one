<?php
/*
    The template for displaying Author Archive pages
*/

global $wp_query;

$context = Timber::get_context();
$context['posts'] = Timber::get_posts();
if (isset($wp_query->query_vars['author'])) {
    $author = new TimberUser($wp_query->query_vars['author']);
    $context['author'] = $author;
    $context['title'] = 'Author Archives: ' . $author->name();
}
$templates = array(
    'author.twig',
    'archive.twig'
);
Timber::render($templates, $context);