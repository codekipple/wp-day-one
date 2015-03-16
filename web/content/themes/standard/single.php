<?php
/*
    The Template for displaying all single posts
*/

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;
$context['comment_form'] = TimberHelper::get_comment_form();
$templates = array(
    'single-' . $post->ID . '.twig',
    'single-' . $post->post_type . '.twig',
    'single.twig'
);
Timber::render($templates, $context);