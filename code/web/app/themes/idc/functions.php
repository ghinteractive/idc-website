<?php
/**
 * Theme includes
 *
 * The $includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 */
$includes = [
  'lib/theme/helpers.php',  // Helper functions
  'lib/theme/setup.php',    // Theme setup
  'lib/theme/custom.php',   // Custom functions
  'lib/theme/assets.php',   // Assets
  'lib/theme/wrapper.php',  // Template Wrapper from Roots.io
  'lib/theme/walker.php',   // Walker Nav
  'lib/theme/rest.php',     // REST Routes
];

foreach ($includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'ghint'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);
