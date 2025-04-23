<?php
/*
Plugin Name: Logger
Description: Simple plugin for debugging
Author: Lilith Zakharyan
Version: 2.0

 */

if(!function_exists('_log')) {

	function _log($name, $log = '', $logfile = false) {
		$options = get_option( 'swpl_options' );
		$swpldir = $options['swpl_dir'];
		if(!$swpldir) $swpldir = 'swpl-logs';
		$swplfile = $options['swpl_file'];
		if(!$swplfile) $swplfile = 'swpl';

		$dir = wp_get_upload_dir()['basedir'] . '/' . $swpldir . '/';
		if(!file_exists($dir)) {
			mkdir($dir);
		}
		if($logfile) {
			$logfile = $dir . $swplfile . '_' . $logfile . '_' . date('Y-m-d') .'.log';
		} else {
			$logfile = $dir . $swplfile . '_' . date('Y-m-d') . '.log';
		}

		file_put_contents($logfile, PHP_EOL . '**************************' . PHP_EOL, FILE_APPEND | LOCK_EX);
		if ((is_array($log) || is_object($log) || is_bool($log))) {
			$logb = '';
			if ($log === true) $logb = 'yes';
			elseif ($log === false) $logb = 'no';
			$res = is_bool($log) ? $logb : print_r($log, true);
			file_put_contents($logfile, '[' . date('H:i:s') . '] ' . $name . ': ' . $res . PHP_EOL, FILE_APPEND | LOCK_EX);
		} elseif ($log === '') {
			file_put_contents($logfile, '[' . date('H:i:s') . '] ' . $name . '.' . PHP_EOL, FILE_APPEND | LOCK_EX);
		} else {
			file_put_contents($logfile, '[' . date('H:i:s') . '] ' . $name . ': ' . $log . PHP_EOL, FILE_APPEND | LOCK_EX);
		}
	}
}

add_action( 'admin_init', 'swpl_options_init');
add_action( 'admin_menu', 'swpl_options_page');

function swpl_options_init(){
	register_setting(
		'swpl_options_group',
		'swpl_options',
		'swpl_options_validate'
	);

}

function swpl_options_page() {
	add_options_page(
		'Simple WP Logger Options',
		'Simple WP Logger Options',
		'manage_options',
		'swpl_options',
		'swpl_render_options'
	);
}

function swpl_render_options() {
?>
		<div class="wrap">
				<form method="post" action="options.php">
<?php
	settings_fields( 'swpl_options_group' );
	$options = get_option( 'swpl_options' );
	$dir = $options['swpl_dir'];
	if(!$dir) $dir = 'swpl_logs';
	$file = $options['swpl_file'];
	if(!$file) $file = 'swpl';
?>
						<h1>Simple WP Logger Options</h1>
						<table class="form-table">
								<tr valign="top">
										<th scope="row">
											Set directory for Simple WP Logger (this will go inside wp-content/uploads)
										</th>
										<td>
																						<input type="text" id="swpl_dir" name="swpl_options[swpl_dir]" value="<?php echo $dir; ?>" style="width:260px;">
										</td>
								</tr>
								<tr valign="top">
										<th scope="row">
											File prefix for Simple WP Logger (it will look like filename_2021-01-31.log)
										</th>
										<td>
											<input type="text" id="swpl_file" name="swpl_options[swpl_file]" value="<?php echo $file; ?>" style="width:260px;">
										</td>
								</tr>
						</table>
						<p class="submit">
								<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'agp') ?>" />
						</p>
				</form>
		</div>
<style>
		code {display: block;}
</style>
<p class="description">
To use the Simple WP Logger in your code, just type smth like
<code>
		_log('My log title', $myvar);
</code>
</p>
<p class="description">
If you don't want to output a variable, you may use it like
<code>
		_log('Just log this line of text');
</code>
</p>
<p class="description">
If you want to output several variables at once, put them into an array like this:
<code>
		_log('Several variables', array($var1, $var2, $var3, ...));
</code>
</p>
<p class="description">
If you want to log things in a separate file, you can use the third argument:
<code>
		$logfile = 'another-file';<br>
		_log('Logging to another file', $myvar, $logfile);
</code>
This file will be stored in the directory you set in the options (or "swpl_dir"), with the file prefix you also set in the options (or "swpl"):
<code>
				wp-content/uploads/<?php echo $options['swpl_dir']; ?>/<?php echo $options['swpl_file']; ?>_another-file_<?php echo date('Y-m-d'); ?>.log
</code>
		</p>
<?php   
}

function swpl_options_validate( $input ) {
	// do some validation here if necessary
	return $input;
}
