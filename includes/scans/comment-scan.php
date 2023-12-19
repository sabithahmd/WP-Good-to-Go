<?php

namespace WP_Good_To_Go\Scans;

use WP_Good_To_Go\Scanner\WP_GTG_Scan;

class Comment_Scan implements WP_GTG_Scan {

    public function scan() {
      $comment_status = $this->fix_needed();
   
      return [
        'class' => 'Comment_Scan',
        'issue' => $comment_status['comment'],
        'label' => $comment_status['value'] ? 'Turn off Comments': 'No fixes needed',
        'value' => $comment_status['value']
      ];
    }
    
    public function fix_needed() {
      global $wpdb;
      $comment_query = $wpdb->get_results("SELECT ID from {$wpdb->prefix}posts WHERE comment_status = 'open' && post_type = 'post'");

      if($comment_query){
        return ['value' => true, 'comment' => 'Comment Status is open for '.count($comment_query).' post/posts'];
      } else {
        return ['value' => false, 'comment' => 'Comments are closed'];
      }
      
    }

    public function fix_action() {
      global $wpdb;
      $wpdb->query("UPDATE {$wpdb->prefix}posts SET comment_status = 'closed' WHERE post_type = 'post'");
      return true;
    }

}

