<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
* Admin System Report Class
* It gives information about the website
*/
class Eos_wi_Admin_System_Report {
	public static function output() {
		add_filter( 'load_textdomain_mofile', 'eos_wi_load_translation_file',99,2 ); //loads plugin translation files
		load_plugin_textdomain( 'eos-wi', FALSE,EOS_WI_PLUGIN_DIR . '/languages/' );
		echo '<div class="wrap eos">';
		self::get_wp_environment_box();
		self::get_server_environment_box();
		self::get_extensions_box();
		self::get_active_plugins_box();
		self::get_plugin_updates_box();
		self::get_theme_box();
		self::get_theme_updates_box();
		self::add_content_box();
		self::add_sitehealth_box();
		self::add_user_agent_box();
		self::add_debug_report_box();
		echo '</div>';
	}
	public static function get_wp_environment_box(){
		?>
    <div class="eos-widget-full top">
      <div class="eos-widget settings-box">
        <p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'WordPress Environment', 'eos-wi' ); ?></p>
        <div class="eos-list">
          <ul>
            <li>
                <p><?php esc_html_e( 'Home URL', 'eos-wi' ); ?>: <strong><?php echo esc_url( home_url() ); ?></strong></p>
            </li>
            <li>
                <p><?php esc_html_e( 'Wordpress Version', 'eos-wi' ); ?>: <strong><?php echo esc_html( get_bloginfo( 'version' ) ); ?></strong></p>
            </li>
            <li>
                <p><?php esc_html_e( 'Wordpress Multisite', 'eos-wi' ); ?>: <strong><?php if ( is_multisite() ) { esc_html_e( 'Yes','eos-wi' ); } else { esc_html_e( 'No','eos-wi' ); } ?></strong></p>
            </li>
            <li>
                <p><?php esc_html_e( 'Wordpress Debug Mode', 'eos-wi' ); ?>: <strong><?php echo defined( 'WP_DEBUG' ) && esc_html( WP_DEBUG ) ? esc_html__( 'Yes','eos-wi' ) : esc_html__( 'No','eos-wi' ); ?></strong></p>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <?php
	}
	public static function get_server_environment_box() {
		global $wpdb;
		$host_name = function_exists( 'gethostname' ) ? gethostname() : false;
		global $wpdb;
		$querystr = 'SELECT table_schema AS "Database", ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS "Size (MB)" FROM information_schema.TABLES GROUP BY table_schema;';
		$dbs_info = $wpdb->get_results( $querystr );
		if( is_array( $dbs_info ) && !empty( $dbs_info ) ){
			foreach( $dbs_info as $db_info ){
				if( is_object( $db_info ) ){
					if( DB_NAME === $db_info->Database ){
						$vars = get_object_vars( $db_info );
						if( NULL !== $vars ){
							foreach( $vars as $k => $v ){
								$db_size = false !== strpos( '_'.$k,'Size' ) ? $v : false;
							}
						}
					}
				}
			}
		}
		?>
    <div class="eos-widget-full top">
        <div class="eos-widget settings-box">
            <p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'Server Environment', 'eos-wi' ); ?></p>
            <div class="eos-list">
                <ul>
									<li>
                    <p><?php esc_html_e( 'PHP Version', 'eos-wi' ); ?>: <strong><?php
										// Check if phpversion function exists.
										if ( function_exists( 'phpversion' ) ) {
											$php_version = phpversion();
											if ( version_compare( $php_version, '7.3', '<' ) ) {
												echo '<mark class="error">' . sprintf( esc_html__( '%s - We recommend a minimum PHP version of 7.3.', 'eos-wi' ), esc_html( $php_version ) ) . '</mark>';
											} else {
												echo '<mark class="yes">' . esc_html( $php_version ) . '</mark>';
											}
										} else {
											esc_html_e( "Couldn't determine PHP version because phpversion() doesn't exist.", 'eos-wi' );
										}
												?></strong></p>
                    </li>
                  <li>
                    <p><?php esc_html_e( 'MySQL Version', 'eos-wi' ); ?>: <strong><?php
										/** @global wpdb $wpdb */
										global $wpdb;
										echo $wpdb->db_version();
										?></strong>
										</p>
                  </li>
                  <li>
										<?php if( $db_size ){ ?>
                    <p><?php echo esc_html( sprintf( __( 'Database size: %s Mb', 'eos-wi' ),$db_size ) ); ?></p>
										<?php } ?>
                  </li>
                  <li>
										<?php if( isset( $_SERVER['SERVER_SOFTWARE'] ) ){ ?>
                    <p><?php echo esc_html( sprintf( __( 'Server software: %s', 'eos-wi' ),$_SERVER['SERVER_SOFTWARE'] ) ); ?></p>
										<?php } ?>
                  </li>
                </ul>
            </div>
        </div>
    </div>
    <?php
	}
	public static function get_extensions_box() {
		$php_extensions = get_loaded_extensions();
		$html = '<ul>';
		foreach( $php_extensions as $extension ){
		 $html .= '<li><span>'.$extension.', </span></li>';
		}
		$html = rtrim( $html,', ' ).'</ul>';
		?>
    <div class="eos-widget-full top">
			<div class="eos-widget-full top">
	        <div class="eos-widget settings-box">
	            <p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'PHP Extensions', 'eos-wi' ); ?></p>
	            <div class="eos-list">
									<?php echo $html; ?>
	            </div>
	        </div>
	    </div>
    </div>
    <?php
	}
	public static function get_active_plugins_box() {
		$mu_plugins = wp_get_mu_plugins();
		$active_plugins = (array) get_option( 'active_plugins', array() );
		?>
		<div class="eos-widget-full top">
			<div class="eos-widget settings-box">
				<p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'Must Use Plugins', 'eos-wi' ); ?></p>
				<div class="eos-list">
					<ul>
					<?php
					foreach( $mu_plugins as $mu_plugin ) {
						?>
						<li><?php echo basename( $mu_plugin ); ?></li>
					<?php } ?>
					</ul>
                </div>
            </div>
        </div>
		<div class="eos-widget-full top">
			<div class="eos-widget settings-box">
				<p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'Active Plugins', 'eos-wi' ); ?></p>
				<div class="eos-list">
					<ul>
					<?php
					$n = 0;
					$plugsN = count( $active_plugins );
					foreach( $active_plugins as $plugin ) {
						$plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
						$version_string = '';
						if ( ! empty( $plugin_data['Name'] ) ) {
							$plugin_name = esc_html( $plugin_data['Name'] );
						}
						if ( ! empty( $version_data['version'] ) && version_compare( $version_data['version'], $plugin_data['Version'], '>' ) ) {
							$version_string = ' &ndash; <strong style="color:red;">' . esc_html( sprintf( _x( '%s is available', 'Version info', 'eos-wi' ), $version_data['version'] ) ) . '</strong>';
						}
						$suffix = $n + 1 < $plugsN ? '; ' : '';
						?>
						<li><?php echo $plugin_name.' '.esc_html( $plugin_data['Version'] ) . $version_string . $suffix; ?></li>
						<?php
						++$n;
					}
					?>
					</ul>
                </div>
            </div>
        </div>
		<?php
	}
	public static function get_plugin_updates_box() {
		$updates = get_plugin_updates();
		?>
		<div class="eos-widget-full top">
			<div class="eos-widget settings-box">
				<p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'Plugins to be updated', 'eos-wi' ); ?></p>
				<div class="eos-list">
					<ul>
					<?php
					foreach( $updates as $update ) {
						?>
						<li><a style="text-decoration:none" href="<?php echo esc_url( $update->PluginURI ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $update->Name.' (From '.$update->Version.' to '.$update->update->new_version.')' ); ?>;</a></li>
						<?php } ?>
						</ul>
	          </div>
	      </div>
	  </div>
		<?php
	}
	public static function get_theme_box() {
		include_once( ABSPATH . 'wp-admin/includes/theme-install.php' );
		$active_theme = wp_get_theme();
		// @codingStandardsIgnoreStart
		$theme_version = $active_theme->Version;
		$theme_template = $active_theme->Template;
		// @codingStandardsIgnoreEnd
		?>
        <div class="eos-widget-full top">
            <div class="eos-widget settings-box">
                <p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'Current Theme', 'eos-wi' ); ?></p>
                <div class="eos-list">
                    <ul>
                        <li>
                            <p><?php esc_html_e( 'Theme', 'eos-wi' ); ?>: <strong><?php echo esc_html( $active_theme ); ?></strong></p>
                        </li>
                        <li>
                            <p><?php esc_html_e( 'Theme Version', 'eos-wi' ); ?>: <strong><?php echo esc_html( $theme_version ); ?></strong></p>
                        </li>
                        <li>
                            <p><?php esc_html_e( 'Child Theme', 'eos-wi' ); ?>: <strong><?php
									echo is_child_theme() ? '<mark class="yes">'.esc_html__( 'Yes','eos-wi' ).'</mark>' : esc_html__( 'No','eos-wi' ); ?></strong></p>
                        </li>
                        <?php
						if ( is_child_theme() ) :
							$parent_theme = wp_get_theme( $theme_template );
							?>
                            <li>
                                <p><?php esc_html_e( 'Parent Theme', 'eos-wi' ); ?>: <strong><?php echo esc_html( $parent_theme ); ?></strong></p>
                            </li>
						<?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
	}
	public static function get_theme_updates_box() {
		$updates = get_theme_updates();
		?>
		<div class="eos-widget-full top">
			<div class="eos-widget settings-box">
				<p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'Themes to be updated', 'eos-wi' ); ?></p>
				<div class="eos-list">
					<ul>
					<?php
					foreach( $updates as $name => $obj ) {
						$update = $obj->update;
						?>
						<li><a style="text-decoration:none" href="<?php echo esc_url( $update['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $name.' (From '.$obj->Version.' to '.$update['new_version'].')' ); ?>;</a></li>
						<?php } ?>
						</ul>
						</div>
				</div>
		</div>
		<?php
	}
	public static function add_content_box() {
		$post_types = get_post_types();
		$post_types_queryable = get_post_types( array( 'publicly_queryable' => true,'public' => true ) );
		$posts = get_posts( array( 'posts_per_page' => -1,'post_type' => $post_types ) );
		$exclude = array(
			'revision',
			'nav_menu_item',
			'wp_block',
			'custom_css',
			'customize_changeset',
			'oembed_cache',
			'user_request',
			'shop_order',
			'shop_order_refund',
			'shop_coupon'
		);
	?>
	<div class="eos-widget-full top">
		<div class="eos-widget settings-box">
			<p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'Content', 'eos-wi' ); ?></p>
				<div class="eos-list">
					<ul>
						<li>
							<p>______</p>
							<p><?php printf( esc_html__( 'Number of Post Types %s', 'eos-wi' ),count( $post_types ) ); ?></p>
							<p><?php printf( esc_html__( 'Queryable Post Types %s', 'eos-wi' ),count( $post_types_queryable ) ); ?></p>
							<p><?php printf( esc_html__( 'Number of posts (any type): %s', 'eos-wi' ),count( $posts) ); ?></p>
							<p>______</p>
							<p></p>
							<?php foreach( $post_types as $post_type ){
								$labsObj = get_post_type_object( $post_type );
								if( !in_array( $post_type,$exclude ) && isset( $labsObj->labels ) ){
									$labs = $labsObj->labels;
									$labs_name = isset( $labs->name ) ? $labs->name : $post_type;
									$posts = get_posts( array( 'posts_per_page' => -1,'post_type' => $post_type ) );
								?>
								<p><?php printf( esc_html__( 'Number of %s: %s', 'eos-wi' ),'<strong>'.$labs_name.'</strong>',count( $posts ) ); ?></p>
								<?php
								}
							} ?>
						</li>
				</ul>
			</div>
		</div>
	</div>
	<?php
	}
	public static function add_sitehealth_box() {
		if( !file_exists( ABSPATH.'/wp-admin/includes/class-wp-site-health.php' ) ) return;
		require_once ABSPATH.'/wp-admin/includes/class-wp-site-health.php';
		$site_health = get_transient( 'health-check-site-status-result' );
		if( !$site_health ) return;
		$site_health = json_decode( sanitize_text_field( $site_health ) );
	?>
	<div class="eos-widget-full top">
		<div class="eos-widget settings-box">
			<p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'Site Health', 'eos-wi' ); ?></p>
				<div class="eos-list">
					<ul>
						<li>
						<?php  foreach( $site_health as $k => $v ){ ?>
						<p><?php echo esc_html( $k ).': '.esc_html( $v ); ?></p>
						<?php } ?>
						</li>
					</ul>
			</div>
		</div>
	</div>
	<?php
	}
	public static function add_user_agent_box() {
	?>
    <div class="eos-widget-full top">
      <div class="eos-widget settings-box">
        <p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'User Agent', 'eos-wi' ); ?></p>
        <div class="eos-list">
	        <ul>
            <li>
              <p><strong id="eos-user-agent"></strong></p>
            </li>
					</ul>
				</div>
			</div>
		</div>
	<?php
	}
	public static function add_debug_report_box() {
		?>
    <div class="eos-widget-full top">
      <div class="eos-widget">
        <p class="eos-label" style="font-size:30px;"><?php esc_html_e( 'Copy System Report for Support', 'eos-wi' ); ?></p>
        <p class="eos-description">
          <div id="eos-debug-report">
            <textarea style="width:100%" rows="20" readonly="readonly"></textarea>
            <p class="submit"><button id="copy-for-support" class="button-primary" href="#" ><?php esc_html_e( 'Copy for Support', 'eos-wi' ); ?></button></p>
          </div>
        </p>
      </div>
    </div>
    <?php
	}
}
