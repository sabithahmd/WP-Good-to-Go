<?php

namespace WP_Good_To_Go\Scans;

use WP_Good_To_Go\Tools\WPConfigTransformer;

use WP_Good_To_Go\Scanner\WP_GTG_Scan;

class Debug_Scan implements WP_GTG_Scan {

  public function scan() {
    $debug_status = $this->fix_needed();

    return [
      'class' => 'Debug_Scan',
      'issue' => $debug_status ? 'Debug mode is active' : 'Debug mode is inactive',
      'label' => $debug_status ? 'Turn off Debug': 'No fix needed',
      'value' => $debug_status
    ];
  }

  public function fix_needed() {
    return WP_DEBUG;
  }

  public function fix_action() {
    $config_transformer = new WPConfigTransformer(ABSPATH.'/wp-config.php' );
    $config_transformer->update( 'constant', 'WP_DEBUG', 'false', array( 'raw' => true ) );
    $config_transformer->add( 'constant', 'DEBUG_FIX', 'foo' );
    $config_transformer->remove( 'constant', 'DEBUG_FIX' );
    return "<p>Turned off Debug Mode</p>";
  }
    
}