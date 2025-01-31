<?php
extract( wpsc_update_debug_settings() ); // $wp_super_cache_debug, $wp_cache_debug_log, $wp_cache_debug_ip, $wp_super_cache_comments, $wp_super_cache_front_page_check, $wp_super_cache_front_page_clear, $wp_super_cache_front_page_text, $wp_super_cache_front_page_notification, $wp_super_cache_advanced_debug, $wp_cache_debug_username
$admin_url = admin_url( 'options-general.php?page=wpsupercache' );

echo '<a name="debug"></a>';
echo '<fieldset class="options">';
echo '<p>' . __( 'Fix problems with the plugin by debugging it here. It will log to a file in your cache directory.', 'wp-super-cache' ) . '</p>';
// $wp_cache_debug_log is declared when this file is included.
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
if ( ! isset( $wp_cache_debug_log ) || $wp_cache_debug_log == '' ) {
	extract( wpsc_create_debug_log() ); // $wp_cache_debug_log, $wp_cache_debug_username
}

$server_root = isset( $_SERVER['DOCUMENT_ROOT'] ) ? esc_url_raw( wp_unslash( $_SERVER['DOCUMENT_ROOT'] ) ) : ABSPATH;
// $wp_cache_home_path and $cache_path are declared when this file is included.
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$log_file_link = "<a href='" . home_url( str_replace( $server_root . $wp_cache_home_path, '', "{$cache_path}view_{$wp_cache_debug_log}?wp-admin=1&wp-json=1&filter=" ) ) . "'>$wp_cache_debug_log</a>";

if ( $wp_super_cache_debug == 1 ) {
	echo "<p>" . sprintf( __( 'Currently logging to: %s', 'wp-super-cache' ), $log_file_link ) . "</p>";
} else {
	echo "<p>" . sprintf( __( 'Last Logged to: %s', 'wp-super-cache' ), $log_file_link ) . "</p>";
}
echo "<p>" . sprintf( __( 'Username/Password: %s', 'wp-super-cache' ), $wp_cache_debug_username ) . "</p>";

echo '<form name="wpsc_delete" action="' . esc_url_raw( add_query_arg( 'tab', 'debug', $admin_url ) ) . '" method="post">';
wp_nonce_field('wp-cache');
echo "<input type='hidden' name='wpsc_delete_log' value='1' />";
submit_button( __( 'Delete', 'wp-super-cache' ), 'delete', 'wpsc_delete_log_form', false );
echo "</form>";

echo '<form name="wpsc_delete" action="' . esc_url_raw( add_query_arg( 'tab', 'debug', $admin_url ) ) . '" method="post">';
if ( ! isset( $wp_super_cache_debug ) || $wp_super_cache_debug == 0 ) {
	$debug_status_message = __( 'Enable Logging', 'wp-super-cache' );
	$not_status = 1;
} else {
	$debug_status_message = __( 'Disable Logging', 'wp-super-cache' );
	$not_status = 0;
}
echo "<input type='hidden' name='wp_super_cache_debug' value='" . $not_status . "' />";
wp_nonce_field('wp-cache');
submit_button( $debug_status_message, 'primary', 'wpsc_log_status', true );
echo "</form>";

echo '<form name="wp_cache_debug" action="' . esc_url_raw( add_query_arg( 'tab', 'debug', $admin_url ) ) . '" method="post">';
echo "<input type='hidden' name='wp_cache_debug' value='1' /><br />";
echo "<table class='form-table'>";
echo "<tr><th>" . __( 'IP Address', 'wp-super-cache' ) . "</th><td> <input type='text' size='20' name='wp_cache_debug_ip' value='{$wp_cache_debug_ip}' /> " . sprintf( __( '(only log requests from this IP address. Your IP is %s)', 'wp-super-cache' ), $_SERVER[ 'REMOTE_ADDR' ] ) . "</td></tr>";
echo "<tr><th valign='top'>" . __( 'Cache Status Messages', 'wp-super-cache' ) . "</th><td><input type='checkbox' name='wp_super_cache_comments' value='1' " . checked( 1, $wp_super_cache_comments, false ) . " /> " . __( 'enabled', 'wp-super-cache' ) . "<br />";
echo  __( 'Display comments at the end of every page like this:', 'wp-super-cache' ) . "<br />";
echo "<pre>&lt;!-- Dynamic page generated in 0.450 seconds. -->
	&lt;!-- Cached page generated by WP-Super-Cache on " . date( "Y-m-d H:i:s", time() ) . " -->
	&lt;!-- super cache --></pre></td></tr>";
echo "</table>\n";
if ( isset( $wp_super_cache_advanced_debug ) ) {
	echo "<h5>" . __( 'Advanced', 'wp-super-cache' ) . "</h5><p>" . __( 'In very rare cases two problems may arise on some blogs:<ol><li> The front page may start downloading as a zip file.</li><li> The wrong page is occasionally cached as the front page if your blog uses a static front page and the permalink structure is <em>/%category%/%postname%/</em>.</li></ol>', 'wp-super-cache' ) . '</p>';
	echo "<p>" . __( 'I&#8217;m 99% certain that they aren&#8217;t bugs in WP Super Cache and they only happen in very rare cases but you can run a simple check once every 5 minutes to verify that your site is ok if you&#8217;re worried. You will be emailed if there is a problem.', 'wp-super-cache' ) . "</p>";
	echo "<table class='form-table'>";
	echo "<tr><td valign='top' colspan='2'><input type='checkbox' name='wp_super_cache_front_page_check' value='1' " . checked( 1, $wp_super_cache_front_page_check, false ) . " /> " . __( 'Check front page every 5 minutes.', 'wp-super-cache' ) . "</td></tr>";
	echo "<tr><td valign='top'>" . __( 'Front page text', 'wp-super-cache' ) . "</td><td> <input type='text' size='30' name='wp_super_cache_front_page_text' value='{$wp_super_cache_front_page_text}' /> (" . __( 'Text to search for on your front page. If this text is missing, the cache will be cleared. Leave blank to disable.', 'wp-super-cache' ) . ")</td></tr>";
	echo "<tr><td valign='top' colspan='2'><input type='checkbox' name='wp_super_cache_front_page_clear' value='1' " . checked( 1, $wp_super_cache_front_page_clear, false ) . " /> " . __( 'Clear cache on error.', 'wp-super-cache' ) . "</td></tr>";
	echo "<tr><td valign='top' colspan='2'><input type='checkbox' name='wp_super_cache_front_page_notification' value='1' " . checked( 1, $wp_super_cache_front_page_notification, false ) . " /> " . __( 'Email the blog admin when checks are made. (useful for testing)', 'wp-super-cache' ) . "</td></tr>";

	echo "</table>\n";
}
echo '<div class="submit"><input class="button-primary" type="submit" ' . SUBMITDISABLED . 'value="' . __( 'Save Settings', 'wp-super-cache' ) . '" /></div>';
wp_nonce_field('wp-cache');
echo "</form>\n";
echo '</fieldset>';
