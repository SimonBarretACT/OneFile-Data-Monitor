<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Mailgun\Mailgun;

if ( ! function_exists('sendMailgun'))
{

    function sendMailgun($config)
    {
        $ci =& get_instance();
    
        // First, instantiate the SDK with your API credentials
        $mg = Mailgun::create($ci->config->item('mailgun_api_key')); // For US servers
    
        // Now, compose and send your message.
        // $mg->messages()->send($domain, $params);
        $mg->messages()->send($ci->config->item('mailgun_domain'), $config);  
    
    }

}
