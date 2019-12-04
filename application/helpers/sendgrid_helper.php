<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function sendGrid($config, $debug=false)
{
    $email = new \SendGrid\Mail\Mail(); 
    $email->setFrom($config['fromEmail'], $config['fromName']);
    $email->setSubject($config['subject']);
    $email->addTo($config['toEmail'], $config['toName']);
    $email->addContent("text/plain", $config['contentPlain']);
    $email->addContent(
        "text/html", $config['contentHtml']
    );
    $sendgrid = new \SendGrid($this->config->item('sendgrid_api_key'));
    try {
        $response = $sendgrid->send($email);
        if ($debug):
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        endif;
    } catch (Exception $e) {
        echo 'Caught exception: '. $e->getMessage() ."\n";
    }
}
