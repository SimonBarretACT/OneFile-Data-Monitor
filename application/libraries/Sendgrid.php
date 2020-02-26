<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sendgrid {

        public function send($fromEmail, $fromName, $toEmail, $toName, $subject, $html, $plain='')
        {
            $email = new \SendGrid\Mail\Mail(); 
            $email->setFrom($fromEmail, $fromName);
            $email->setSubject($subject);
            $email->addTo($toEmail, $toName);
            if ($plain !== ''):
            $email->addContent("text/plain", $plain);
            endif;
            $email->addContent("text/html", $html);
            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }
        }

}