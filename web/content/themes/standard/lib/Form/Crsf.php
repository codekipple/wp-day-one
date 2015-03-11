<?php
namespace CodekippleWPTheme\Forms;

use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\SessionCsrfProvider;
use Symfony\Component\HttpFoundation\Session\Session;

class Form_Crsf
{
    public $csrfProvider;

    /*
        Private because this class is a singleton
    */
    private function __construct()
    {
        $this->csrfProvider();
    }

    /*
        Call this method to get singleton
        @return settings
    */
    public static function Instance()
    {
        static $inst = null;

        if ($inst === null) {
            $inst = new Form_Crsf();
        }

        return $inst;
    }

    protected function csrfProvider()
    {
        // CSRF secret token, currently set in wp-config.php
        $csrfSecret = FORMS_CRSF_TOKEN;

        // create a Session object from the HttpFoundation component
        $session = new Session();

        $this->csrfProvider = new SessionCsrfProvider($session, $csrfSecret);
    }
}