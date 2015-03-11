<?php
namespace CodekippleWPTheme\Forms;

use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;

class Form_Twig
{
    /*
        Private because this class is a singleton
    */
    private function __construct($twig)
    {
        $defaultFormTheme = 'forms.twig';
        $formEngine = new TwigRendererEngine(array($defaultFormTheme));
        $formEngine->setEnvironment($twig);

        // add the FormExtension to Twig
        $twig->addExtension(
            new FormExtension(new TwigRenderer($formEngine, Form_Crsf::Instance()->csrfProvider))
        );
    }

    /*
        Call this method to get singleton
        @return settings
    */
    public static function Instance($twig)
    {
        static $inst = null;

        if ($inst === null) {
            $inst = new Form_Twig($twig);
        }

        return $inst;
    }
}

/*
    the filter is from the Timber plugin
    https://wordpress.org/plugins/timber-library/
*/
add_filter('twig_apply_filters', function($twig) {
    Form_Twig::Instance($twig);
    return $twig;
}, 10, 3);