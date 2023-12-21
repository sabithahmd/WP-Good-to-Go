<?php

namespace WP_Good_To_Go\Scanner;

class WP_GTG_Scanner {

    public $scans = [];

    public function __construct() {
        $this->find_scans();
    }

    private function find_scans() {
        $scan_files = glob(WPGTG_DIR_PATH . 'includes/scans/*.php');

        foreach ($scan_files as $scan_file) {
            $scan_file_name = basename( $scan_file, '.php' );
            $this->scans[] = $this->name_to_class( $scan_file_name );
        }
    }

    private function name_to_class( $scan_file_name ) {
        $class_name = str_replace( '-', '_', $scan_file_name );
        return ucwords( $class_name, '_' );
    }

    public function start_scan () {
        $data = [];
        foreach($this->scans as $class) {
            $scan_class = '\\WP_Good_To_Go\Scans\\'.$class;
            $scan = new $scan_class;
            $data[] = $scan->scan();
        }
        //$data = $this->scan_result_form_html($data);
        $filter_data = array_filter($data, function($scan_result) {
            return $scan_result['value'];
        });
        echo json_encode(array_values($filter_data));

    }

    public function fix_scan () {
        parse_str($_POST['formdata'], $formdata);
        $data = [];
        foreach($formdata as $key => $value) {
            $scan_class = '\\WP_Good_To_Go\Scans\\'.$key;
            $scan = new $scan_class;
            $data[] = $scan->fix_action();
            
        }
        echo json_encode($data);
    }
}

