<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['mailgun_api_key']  = getenv('MAILGUN_API_KEY');
$config['mailgun_domain']   = getenv('MAILGUN_DOMAIN');
$config['mailgun_from']     = getenv('MAILGUN_FROM');
