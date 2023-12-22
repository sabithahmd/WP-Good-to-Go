<?php

namespace WP_Good_To_Go\Scans;

use WP_Good_To_Go\Scanner\WP_GTG_Scan;

class Xml_Scan implements WP_GTG_Scan {

  public function scan() {

      $xmlr_status = $this->fix_needed();
    
      return [
        'class' => 'Xml_Scan',
        'issue' => $xmlr_status ? 'XML-RPC is enabled' : 'XML-RPC is disabled',
        'label' => $xmlr_status ? 'Turn off XML-RPC': 'No fix needed',
        'value' => $xmlr_status
      ];
    }

  public function fix_needed() {
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => get_bloginfo('url').'/xmlrpc.php',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
    ));
    
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($httpCode >= 200 && $httpCode < 300) {
        return true;
      }
      else {
        return false;
      }
    
  }

  public function fix_action() {
    
    add_filter( 'xmlrpc_enabled', '__return_false' );
    return "<p>Disabled XML-RPC</p>";
  }

}