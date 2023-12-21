jQuery(document).ready( function() {
    jQuery("#scan_btn").click( function(e) {
       e.preventDefault(); 
       var data = {
             'action': 'scan_action'
       };
       jQuery.post(ajaxurl, data, function(response) {
          console.log('test',JSON.parse(response));
          var html = scan_result_form_html(JSON.parse(response));
          jQuery('.js-scan_result_wrapper').html(html);
          jQuery("#scan_list").click( function(e) {
            e.preventDefault(); 
            var formdata = jQuery('form.scan_result_form').serialize();
            var data = {
               'action': 'fix_issue',
               'formdata': formdata
            };
 
            jQuery.post(ajaxurl, data, function(response) {
               console.log(response);
            });
         });
       });
    });
 });


 function scan_result_form_html(data) {
   var form_input_html = scan_input_html(data);
   var form_submit_html = '<input type="submit" name="fix_issues" value="APPLY" id="scan_list" class="button button-primary"/>';
   var html = '<form action="" method="POST" class="scan_result_form"><table class="scan_results"><thead><tr><th>Issues</th><th>Solution</th></tr></thead>' +form_input_html + '</tbody></table>' +form_submit_html +  '</form>'; 
   return html;
 }

 function scan_input_html(data) {
   var html = '';
   jQuery.each(data, function(key, val) {
      html += '<tr><td>'+val.issue+'</td><td><input type="checkbox" name='+val.class+' value="True"/><label>'+val.label+'</label></td></tr>';
      
   });
   return html;
 }