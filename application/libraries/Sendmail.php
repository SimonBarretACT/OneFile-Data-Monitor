<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sendmail {

        public $fromEmail   = 'cavc@unarchive.xyz';
        public $fromName    = 'OneFile @ CAVC';

        public function sendGrid($toEmail, $toName, $subject, $html, $plain='')
        {
            $email = new \SendGrid\Mail\Mail(); 
            $email->setFrom($this->fromEmail, $this->fromName);
            $email->setSubject($subject);
            $email->addTo($toEmail, $toName);
            if ($plain !== ''):
            $email->addContent("text/plain", $plain);
            endif;
            $email->addContent("text/html", $html);
            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
            try {
                $sendgrid->send($email);
            } catch (Exception $e) {
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }
        }

        public function sendGridDynamic($toEmail, $toName, $subject, $fullname, $id)
        {
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($this->fromEmail, $this->fromName);
            $email->setSubject($subject);
            $email->addTo(
                $toEmail,
                $toName,
                [
                    "fullname" => $fullname,
                    "ID" => $id
                ],
                0
            );
            $email->setTemplateId("d-e60395fa29584b0bb352cf7a18dbddd4");
            $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));
            try {
                $sendgrid->send($email);
            } catch (Exception $e) {
                echo 'Caught exception: '.  $e->getMessage(). "\n";
            }
        }

}
