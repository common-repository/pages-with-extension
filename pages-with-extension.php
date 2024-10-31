<?php
/*
Plugin Name: Pages with extension
Plugin URI: http://wpintent.com/
Description: Add <strong>.html or .htm or .php</strong> to pages. Go to <a href="tools.php?page=pages-with-extensions">setting page</a> to add whatever extension you like!
Author: wpintent
Version: 1.0
Author URI: http://wpintent.com/
*/

add_action('init', 'ext_page_permalink', -1);
register_activation_hook(__FILE__, 'active');
register_deactivation_hook(__FILE__, 'deactive');
add_action('admin_menu', 'eop_plugin_menu');
add_action('admin_init', 'eop_redirect');

//session_start();
function eop_plugin_menu() {
add_management_page('Page Extension', 'Page with Extension', 8, 'pages-with-extensions', 'eop_main');
}

function ext_page_permalink() {
    global $wp_rewrite;
    if(get_option('eop_ext')!=''){
        if ( !strpos($wp_rewrite->get_page_permastruct(), get_option('eop_ext'))){
            $wp_rewrite->page_structure = $wp_rewrite->page_structure . get_option('eop_ext');
        }
    }
}
add_filter('user_trailingslashit', 'no_page_slash',66,2);
function no_page_slash($string, $type){
   global $wp_rewrite;
   if(get_option('eop_ext')!=''){
    if ($wp_rewrite->using_permalinks()  && $type == 'page'){
        return untrailingslashit($string);
    }

    else return $string;
   }else return $string;
}



function eop_main(){
    
$location = $footer_options_page; // Form Action URI
    global $wp_rewrite;
/* Check for admin Options submission and update options*/
if ('process' == $_POST['stage']) {
    global $wp_rewrite;
    update_option('eop_ext', $_POST['eop_ext']);
    
    
    $wp_rewrite->flush_rules();
    $status = "<p>settings updated successfully.</p>
    <p>Flushing Rules... Please wait the page will reload...</p>";
}
if($_GET['do']=='flush_rules'){
$wp_rewrite->flush_rules();
$status = "settings updated successfully.";
    //add_action('init', 'ext_page_permalink', -1);
    //add_filter('user_trailingslashit', 'no_page_slash',66,2);
    //$wp_rewrite->flush_rules();
//$fiv_fhtml = stripslashes(get_option('fiv_fhtml'));
}
?>

<div class="wrap">
  <h2><?php _e('Pages Extension Settings', 'pages-with-extension') ?></h2>
  <?php if(isset($status)) {  ?>
      <div class="updated fade" id="message" style="background-color: rgb(255, 251, 204);">
          <?php echo $status;?>
          
          <?php if ('process' == $_POST['stage']){?>
          <script type="text/javascript">
          window.location = '<?php echo admin_url()."tools.php?page=pages-with-extensions&do=flush_rules";?>'
          </script>
          <?php } ?>
          
    </div>
  <?php } 
  
  //echo "";
  ?>

  <form name="form1" method="post" action="">
    <input type="hidden" name="stage" value="process" />
     <table width="100%" cellspacing="2" cellpadding="5" class="form-table">
        <tr valign="baseline">
         <th scope="row"><?php _e('Extension', 'pages-with-extension') ?></th> 
         <td><input type="text" name="eop_ext" id="eop_ext" value="<?php echo get_option('eop_ext'); ?>" /><br />
         (.html, .htm, .php, .xml, .shtml, .asp or whatever in your mind!)
         </td>

        </tr>
        
    
        
        
     </table>


    <p class="submit">
      <input type="submit" name="Submit" value="<?php _e('Save Changes', 'pages-with-extension') ?>" />
    </p>
  </form>
  
  
  
</div>
<?php

}



function active() {
    global $wp_rewrite;
    if(get_option('eop_ext')!=''){
    if ( !strpos($wp_rewrite->get_page_permastruct(), ''.get_option('eop_ext').'')){
        $wp_rewrite->page_structure = $wp_rewrite->page_structure . ''.get_option('eop_ext').'';
 }
  $wp_rewrite->flush_rules();
    }
    add_option('eop_do_activation_redirect', true);

}    
    function deactive() {
        global $wp_rewrite;
        $wp_rewrite->page_structure = str_replace("".get_option('eop_ext')."","",$wp_rewrite->page_structure);
        $wp_rewrite->flush_rules();
    }
function eop_redirect() {
    if (get_option('eop_do_activation_redirect', false)) {
        delete_option('eop_do_activation_redirect');
        wp_redirect(admin_url()."tools.php?page=pages-with-extensions");
    }
}
