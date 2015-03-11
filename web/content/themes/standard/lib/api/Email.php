<?php

namespace CodekippleWPTheme\Api\Email;

use CodekippleWPTheme\Api\Environment as Environment;
use CodekippleWPTheme\Config as Config;

class Email
{
    protected $options;

    public function __construct($options, $headers = array())
    {
        $option_defaults = array(
            'template' => 'emails/contact.twig',
            'data' => array(),
            'to' => Config\Settings::Instance()->settings['email'],
            'subject' => '',
            'from' => Config\Settings::Instance()->settings['email'],
            'dev_email' => sprintf('%s@codekipple.com', sanitize_title(Config\Settings::Instance()->settings['company_name'])),
            'headers' => array(
                'From' => sprintf('%s <%s>', Config\Settings::Instance()->settings['company_name'], Config\Settings::Instance()->settings['email'])
            )
        );

        $this->options = array_replace_recursive($option_defaults, $options);
        $this->setHeaders();
        $this->send();
    }

    protected function setHeaders()
    {
        if (Environment\is_dev()) {
            // override and send to us from dev env
            $this->options['to'] = $this->options['dev_email'];
        } else {
            // not dev, just Bcc us
            $this->headers['Bcc'] = sprintf('%s <%s>', 'Dev', $this->options['dev_email']);
        }

        foreach ($this->options['headers'] as $header => $value) {
            $this->options['headers'][$header] = sprintf("%s: %s\r\n", $header, $value);
        }
    }

    protected function send()
    {
        // Buffer
        ob_start();
        \Timber::render($this->options['template'], $this->options['data']);
        // Get contents
        $body = ob_get_clean();

        add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));

        // send the mail
        return wp_mail(
            $this->options['to'],
            $this->options['subject'],
            $body,
            implode('', $this->options['headers'])
        );
    }
}