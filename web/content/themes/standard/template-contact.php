<?php
/**
 * Template Name: Contact
 */

namespace CodekippleWPTheme\Templates\Contact;

use CodekippleWPTheme\Forms;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
/*
    Doing this to suppress Deprecated warnings.
    The warnings are in the Symfony form component.
    I tried updating the form component but it just resulted in more
    Warnings and Deprecated notices.

    I think the Symfony form component is not being kept properly in step
    with it's dependencies to avoid warnings. I noticed the Deprecated
    warnings what come our currently are fixed in the master branch but
    have not made it into a release.

    TODO: figure out an elegant way to suppress and report notices from
    the symfony form component.
    possible using:-
    set_error_handler(function() {});
    restore_error_handler();
*/
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

class ContactTemplate {
    protected $form;
    protected $post;

    function __construct() {
        $this->post = new \TimberPost();
        $this->buildForm();
        $this->handleRequest();
    }

    protected function buildForm() {
        $formFactory = Forms\Form_Factory::Instance()->formFactory;

        $form_builder = $formFactory->createBuilder()
            ->add('name', 'text', array(
                'constraints' => new NotBlank(array(
                    'message' => 'This value should not be blank.'
                ))
            ))
            ->add('message', 'textarea', array(
                'constraints' => new NotBlank(array(
                    'message' => 'This value should not be blank.'
                ))
            ))
            ->add('email', 'text', array(
                'constraints' => new NotBlank(),
                'constraints' => new Email(array(
                    'message' => 'The email {{ value }} is not a valid email.',
                ))
            ));

        $this->form = $form_builder->getForm();
    }

    protected function handleRequest() {
        $request = Request::createFromGlobals();
        $this->form->handleRequest($request);

        if ($this->form->isValid()) {
            $data = $this->form->getData();

            /*
                store in db under the form entries post type
            */

            /*
                send email
            */

            // redirect user
            $redirect = new RedirectResponse($this->post->permalink . '?form=sent');
            $redirect->prepare($request);
            $redirect->send();
        } else {
            $this->renderView();
        }
    }

    protected function renderView() {
        $context = \Timber::get_context();
        $context['post'] = $this->post;
        $context['form'] = $this->form->createView();
        $templates = array(
            'template-contact.twig'
        );
        \Timber::render($templates, $context);
    }
}

new ContactTemplate;