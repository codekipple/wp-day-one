<?php
namespace CodekippleWPTheme\Forms;

use Symfony\Component\Form\Forms;

/*
    CSrf and session
*/
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;

/*
    validator
*/
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

class Form_Factory
{
    public $formFactory;

    /*
        Private because this class is a singleton
    */
    private function __construct()
    {
        // create a validator to add as an extension
        $validator = Validation::createValidator();

        $this->formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->addExtension(new CsrfExtension(Form_Crsf::Instance()->csrfProvider))
            ->addExtension(new ValidatorExtension($validator))
            ->getFormFactory();
    }

    /*
        Call this method to get singleton
        @return settings
    */
    public static function Instance()
    {
        static $inst = null;

        if ($inst === null) {
            $inst = new Form_Factory();
        }

        return $inst;
    }
}