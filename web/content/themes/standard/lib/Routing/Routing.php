<?php
namespace CodekippleWPTheme\Routing;

/*
    example rules array.

    $rules = array(
        'rule' => 'search/([^/]*)',
        'rewrite_to' => 'index.php?pagename=search&pagination=$matches[1]',
        'query_vars' => array('pagination'),
        'position' => 'top'
    )
*/
class Routing {

    protected $rules = array();

    public function __construct($rules)
    {
        $this->rules = $rules;

        add_action('init', array($this, 'add_rules'));
        add_action('wp_loaded', array($this, 'flush_rules'));
    }

    public function add_rules()
    {
        foreach($this->rules as $rule) {
            // add the rule
            add_rewrite_rule(
                $rule['rule'],
                $rule['rewrite_to'],
                $rule['position']
            );

            // add any query vars associated with the rule
            if(!empty($rule['query_vars'])) {
                foreach($rule['query_vars'] as $query_var) {
                    add_rewrite_tag('%'. $query_var .'%','([^&]+)');
                }
            }
        }
    }

    public function flush_rules()
    {
        $flush_rules = false;
        $current_rules = get_option('rewrite_rules');

        /*
            check if our custom rules exist
        */
        foreach($this->rules as $rule) {
            if(!isset($current_rules[$rule['rule']])) {
                $flush_rules = true;
            }
        }

        /*
            TODO: how to test if rule removed or changed?
        */

        /*
            flush_rules() is expensive so we don't want to do it on every page load, only if our rules are not yet included
        */
        if($flush_rules) {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }
    }

}