<?php
/*
Plugin Name: Append extensions on Pages
Plugin URI: http://www.skmukhiya.com.np
Description: Appends different types of extensions like .html, .php, .asp, .jsp, .asp, .aspx on the wordpress pages when used with permalink.
Version: 1.1.2
Author: Suresh Kumar Mukhiya
Author URI: https://www.odesk.com/users/~0182e0779315e50896
Tags: append .html on pages, .html on permalink, add .html on pages, add .php on pages, add .aspx on pages, add .cfm on page, add .jsp on pages
*/

// initiating hooks and plugins
add_action('init', 'aeop_html_page_permalink', -1);
register_activation_hook(__FILE__, 'aeop_active');

//initiating plugin deactivation hooks
register_deactivation_hook(__FILE__, 'aeop_deactive');

add_action('admin_menu', 'aeop_settings_menu');


function aeop_set_up_options()
{
    add_option('aeop_fburl', '.html');
    register_setting('aeop_settings_group', 'aeop_fburl');
}

//Adding settings link
function aeop_settings_link($links)
{
    $settings_link = '<a href="'.admin_url('admin.php?page=aeop_settings').'">Settings</a>';
    array_push($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);

add_filter("plugin_action_links_$plugin", 'aeop_settings_link');

function validateExtension($extension)
{
    $extension = get_option('aeop_fburl');
    if ($extension == '.cfm' || $extension == '.html' || $extension == '.htm' || $extension == '.asp' || $extension == '.aspx' || $extension == '.jsp' || $extension == '.php') {
        return $extension;
    } else {
        $extension = ".html";
        return $extension;
    }
}


//adding a dummy page
function aeop_settings_menu()
{
    add_submenu_page(
          null,
        'Append Extension settings',
        'Append Extension settings',
        'administrator',
        'aeop_settings',
        'aeop_display_settings'
    );
    add_action('admin_init', 'aeop_set_up_options');
}



function aeop_display_settings()
{
    ?>
<div class="clear"></div>
<div id="welcome-panel" class="welcome-panel">
	<div class="welcome-panel-content">
	<h3>Appending Extension Settings Page!</h3>
	<p class="about-description">Add suitable Extensions to your WordPress page</p>
	<div class="welcome-panel-column-container">
		<div class="welcome-panel-column" style="width:50%;">
			<h4>Get Started</h4>
			<form method="post" action="options.php">
		    <?php settings_fields('aeop_settings_group'); ?>
		    <?php do_settings_sections('aeop_settings_group'); ?>
		    <table class="form-table">
		        <tr valign="top">
		        <th scope="row">Enter the extension you want to append?</th>
		        <td><input type="text" name="aeop_fburl" value="<?php echo get_option('aeop_fburl'); ?>" /></td>
		        </tr>
				<tr><i>You can enter valid extensions such as .html, .htm, .jsp, .php, .asp, .cfm and .aspx only</i></tr>
		    </table>

		    <?php
            $other_attributes = array( 'id' => 'aeop-submit-button' );
    submit_button('Save Settings', 'primary', 'wpdocs-save-settings', true, $other_attributes); ?>
		    <p>Save your permalink setting everytime you update extension.</p>
			</form>
		</div>

	<div class="welcome-panel-column welcome-panel-last">
		<h4>More Actions</h4>
		<ul>
			<li><div class="welcome-icon welcome-widgets-menus">Found this plugin Useful <a href="http://www.skmukhiya.com.np/donation-page/">Buy me a beer</a></div></li>
			<li><a href="mailto:itsmeskm99@gmail.com" class="welcome-icon welcome-comments">Contact Author For more support</a></li>
			<li><a href="http://www.skmukhiya.com.np/append-extensions-to-pages/" class="welcome-icon welcome-learn-more">Learn more about getting started</a></li>
		</ul>
	</div>
	</div>
	</div>
</div>
<?php
}


/**
 * aeop function to initiate the global settings permalink initiations
 * @param null
 * @return string
**/
if (!function_exists('aeop_html_page_permalink')) {
    function aeop_html_page_permalink()
    {
        global $wp_rewrite;
        if (!strpos($wp_rewrite->get_page_permastruct(), validateExtension(get_option('aeop_fburl')))) {
            $wp_rewrite->page_structure = $wp_rewrite->page_structure . validateExtension(get_option('aeop_fburl'));
        }
    }
}

add_filter('user_trailingslashit', 'aeop_no_page_slash', 66, 2);

/**
 * aeop function to check page slash
 * @param string, string
 * @return string
**/
if (!function_exists('aeop_no_page_slash')) {
    function aeop_no_page_slash($string, $type)
    {
        global $wp_rewrite;
        if ($wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes==true && $type == 'page') {
            return untrailingslashit($string);
        } else {
            return $string;
        }
    }
}

/**
 * aeop function to deactivate the plugin
 * @param null
 * @return void
**/
if (!function_exists('aeop_deactive')) {
    function aeop_deactive()
    {
        global $wp_rewrite;
        $wp_rewrite->page_structure = str_replace(validateExtension(get_option('aeop_fburl')), "", $wp_rewrite->page_structure);
        $wp_rewrite->flush_rules();
    }
}

/**
 * aeop function to activate the plugin
 * @param null
 * @return void
**/
function aeop_active()
{
    global $wp_rewrite;
    if (!strpos($wp_rewrite->get_page_permastruct(), validateExtension(get_option('aeop_fburl')))) {
        $wp_rewrite->page_structure = $wp_rewrite->page_structure . validateExtension(get_option('aeop_fburl'));
    }
    $wp_rewrite->flush_rules();
}

?>
