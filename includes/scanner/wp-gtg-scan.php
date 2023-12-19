<?php

namespace WP_Good_To_Go\Scanner;

interface WP_GTG_Scan {

    public function scan();

    public function fix_needed();

    public function fix_action();
    
}