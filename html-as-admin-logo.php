<?php
/*
Plugin Name: HTML as Admin Logo
Plugin URI: http://www.ardeearam.com/html-as-admin-logo/
Description: Replaces the Admin Logo in the login area with any arbitrary HTML snippet. Useful for using Google Web Fonts for logo instead of using an image as a WordPress administration logo.
Author: Ardee Aram
Version: 0.1
Author URI: http://www.ardeearam.com/
License: GPL v3 http://www.gnu.org/licenses/quick-guide-gplv3.html
*/

add_action('login_head', array('AdminLogo', 'replace_logo_with_html'));
add_action('init', array('AdminLogo', 'init'));
add_action('admin_menu', array('AdminLogo', 'admin_menu'));
add_action('admin_init', array('AdminLogo', 'settings_registration'));

class AdminLogo
{
	public static function replace_logo_with_html()
	{
?>
		
		
	<?php echo AdminLogo::get_decoded_text(get_option('ardee-admin-logo-head'));?>
		
		<script type="text/javascript">
			jQuery(document).ready(function()
			{
				jQuery("div#login h1 a").remove();
				<?php $html = AdminLogo::get_decoded_text(get_option('ardee-admin-logo-html'));?>
				jQuery("div#login h1").append("<?php echo trim($html)?>");
			});
		</script>
<?php
	}
	
	public static function init()
	{
		wp_enqueue_script('jquery');
	}
	
	public static function display_configuration()
	{
?>
	<style>
	#ardee-admin-logo-configuration .option-element
	{
		margin-bottom: 30px;
	}
	
	#ardee-admin-logo-configuration .option-first
	{
		margin-top: 30px;
	}
	
	#ardee-admin-logo-configuration label
	{
		color: #444444;
	}
	</style>
	<div id="ardee-admin-logo-configuration">
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Ardee Admin Logo Settings</h2>
		
		<form action="options.php" method="post">
		<?php settings_fields('ardee-admin-logo'); ?>
		
		<div class="option-element option-first">			
			<textarea id="ardee-admin-logo-head" name="ardee-admin-logo-head" style="width: 100%; height: 10em;"><?php echo get_option('ardee-admin-logo-head')?></textarea>
			<label for="ardee-admin-logo-head">Here goes additional things to be placed in the &lt;head&gt; tag, such as additional styles and scripts. You can also place your Google Web Fonts declaration here. <strong>(Optional)</strong></label>
		</div>
		
		<div class="option-element">			
			<textarea id="ardee-admin-logo-html" name="ardee-admin-logo-html" style="width: 100%; height: 10em;"><?php echo get_option('ardee-admin-logo-html')?></textarea>
			<label>This HTML snippet will replace the default WordPress logo in the login page.</label>
		</div>
		
		<div class="option-element">
			Possible codes:
			<ul>
				<ol><strong>[template_directory]</strong> - the template directory path.</ol>
				<ol><strong>[url]</strong> - the URL of this site.</ol>
			</ul>
		</div>
		
		<p class="submit">
			
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
		</p>
		</form>
	</div>
	</div>
<?php
	}
	
	public static function admin_menu()
	{
		add_options_page('Ardee Admin Logo', 'Ardee Admin Logo', 'manage_options', __FILE__, array('AdminLogo', 'display_configuration'));	
	}
	
	public static function settings_registration()
	{
		register_setting('ardee-admin-logo', 'ardee-admin-logo-head');
		register_setting('ardee-admin-logo', 'ardee-admin-logo-html');
	}
	
	public static function ardee_admin_logo_head_callback()
	{
	
	}
	
	public static function get_decoded_text($html)
	{
		$html  = str_replace("[template_directory]", get_bloginfo('template_directory'), $html);
		$html  = str_replace("[url]", get_bloginfo('url'), $html);
		$html  = str_replace('"', "'", $html);
		$html  = str_replace(array("\r\n", "\r", "\n"), '', $html);  
		return $html;
	}
}

?>
