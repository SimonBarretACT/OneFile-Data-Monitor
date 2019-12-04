<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['onefile_customer_beta']  = getenv('ONEFILE_BETA') or false;
$config['onefile_organisation_id']  = getenv('ONEFILE_ORGANISATION_ID') OR 517;

if ($config['onefile_customer_beta']):
    $config['onefile_customer_token']   = getenv('ONEFILE_CUSTOMER_TOKEN_BETA');
    $config['onefile_base_url']         = getenv('ONEFILE_BASE_URL_BETA') OR 'https://wsapibeta.onefile.co.uk/api/v2.1/';
else:
    $config['onefile_customer_token']   = getenv('ONEFILE_CUSTOMER_TOKEN');
    $config['onefile_base_url']         = getenv('ONEFILE_BASE_URL') OR 'https://wsapi.onefile.co.uk/api/v2.1/';
endif;

