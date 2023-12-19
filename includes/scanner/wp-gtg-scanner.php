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
        foreach($this->scans as $value) {
            $scan_class = '\\WP_Good_To_Go\Scans\\'.$value;
            $scan = new $scan_class;
            $data[] = $scan->scan();
        }
        $data = $this->scan_result_form_html($data);
        echo json_encode($data);

    }

    public function fix_scan (){
        parse_str($_POST['formdata'], $formdata);
        $data = [];
        foreach($formdata as $key => $value) {
            $scan_class = '\\WP_Good_To_Go\Scans\\'.$key;
            $scan = new $scan_class;
            $data[] = $scan->fix_action();
            
        }
        echo json_encode($data);
    }

    private function scan_result_form_html($data = []) {

        return '
            <form action="" method="POST" class="scan_result_form">
                <table class="scan_results">
                    <thead>
                        <tr>
                            <th>Issues</th>
                            <th>Solution</th>
                        </tr>
                    </thead>
                    <tbody>'
                        .$this->get_scan_input_elements($data).
                    '</tbody>
                </table> 
                <input type="submit" name="fix_issues" value="APPLY" id="scan_list" class="button button-primary"/>
            </form>
            <div class="scan_response"></div>
        ';

    }
    
    private function get_scan_input_elements($data) {
        $html = '';
        foreach ($data as $key => $value) {
            $hide_option = ($value['value']) ? '':'hidden disabled';
            $issue = $value['issue'];
            $name = $value['class'];
            $label = $value['label'];
            $html .= "
            <tr>
                <td>$issue</td>
                <td> 
                    <input $hide_option type='checkbox' name='$name' value='True'/>
                    <label>$label</label>
                </td>
            </tr>
            ";
        
        }
        return $html;
    }

}

