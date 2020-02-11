<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['onefile_customer_beta']  = false;
$config['onefile_organisation_id']  = getenv('ONEFILE_ORGANISATION_ID') OR 6901;
$config['onefile_customer_token']   = getenv('ONEFILE_CUSTOMER_TOKEN');
$config['onefile_base_url']         = getenv('ONEFILE_BASE_URL') OR 'https://wsapi.onefile.co.uk/api/v2.1/';

