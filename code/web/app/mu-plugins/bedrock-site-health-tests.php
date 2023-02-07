<?php
/**
 * Plugin Name:  Site Health Tests
 * Description:  Adjusts Site Health Check Criteria
 * Version:      1.0.0
 * Author:       Garrison Hughes Interactive
 * Author URI:   https://garrisonhughes.com
 * License:      MIT License
 */

add_filter('site_status_tests', function ($tests) {
    unset($tests['async']['background_updates']);
    return $tests;
});