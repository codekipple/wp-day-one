<?php
namespace CodekippleWPTheme\Forms;

/*
    translations
*/
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

class Form_Translations
{
    /*
        Private because this class is a singleton
    */
    private function __construct($twig)
    {
        // create translator
        $translator = new Translator('en');

        $vendorDir = realpath(__DIR__ . '/../../../../../../vendor');
        $vendorFormDir = $vendorDir . '/symfony/form/Symfony/Component/Form';
        $vendorValidatorDir = $vendorDir . '/symfony/validator/Symfony/Component/Validator';

        /*
            Add built-in translations for core error messages from the
            forms validator
        */
        $translator->addLoader('xlf', new XliffFileLoader());
        $translator->addResource(
            'xlf',
            $vendorFormDir . '/Resources/translations/validators.en.xlf',
            'en',
            'validators'
        );
        $translator->addResource(
            'xlf',
            $vendorValidatorDir . '/Resources/translations/validators.en.xlf',
            'en',
            'validators'
        );

        /*
            Add our translations
        */
        $translator->addLoader('yml', new YamlFileLoader());
        $translationsDir = realpath(__DIR__.'/../../Translations');
        $translator->addResource(
            'yml',
            $translationsDir.'/trans.en.yml',
            'en'
        );

        // add translation extension to twig
        $twig->addExtension(new TranslationExtension($translator));
    }

    /*
        Call this method to get singleton
        @return settings
    */
    public static function Instance($twig)
    {
        static $inst = null;

        if ($inst === null) {
            $inst = new Form_Translations($twig);
        }

        return $inst;
    }
}

/*
    the filter is from the Timber plugin
    https://wordpress.org/plugins/timber-library/
*/
add_filter('twig_apply_filters', function($twig) {
    Form_Translations::Instance($twig);
    return $twig;
}, 10, 3);