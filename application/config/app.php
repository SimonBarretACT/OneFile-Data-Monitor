<?php
defined('BASEPATH') or exit('No direct script access allowed');

//Number of days since user logged in
if (getenv('ARCHIVE_DAYS')) :
    $config['archive_days']     = getenv('ARCHIVE_DAYS');
else :
    $config['archive_days']     = '-90 days';
endif;

//Number of days since user modified
if (getenv('ARCHIVE_MODIFIED')) :
    $config['archive_modified'] = getenv('ARCHIVE_MODIFIED');
else :
    $config['archive_modified'] = '-30 days';
endif;

//Number of days until review
if (getenv('ARCHIVE_REVIEW')) :
    $config['archive_review']   = getenv('ARCHIVE_REVIEW');
else :
    $config['archive_review']   = '30 days';
endif;

// Trend indicators
$config['trend_equal']  = '<span class="text-orange-500"><i class="fas fa-exchange-alt"></i></span>';
$config['trend_up']     = '<span class="text-green-500"><i class="fas fa-caret-up"></i></span>';
$config['trend-down']   = '<span class="text-red-500"><i class="fas fa-caret-down"></i></span>';
