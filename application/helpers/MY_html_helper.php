<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('trendIndicator'))
{

    function trendIndicator($today, $yesterday)
    {

        $ci =& get_instance();

        if ($today == $yesterday):
            return $ci->config->item('trend_equal');
        endif;

        if ($today > $yesterday):
            return $ci->config->item('trend_up');
        endif;

        if ($today < $yesterday):
            return $ci->config->item('trend_down');
        endif;

        return '';
    
    }

}
