<?php
/*
Plugin Name:	DO Editorial Guidelines
Description:	A simple plugin to add editorial guidelines to your WordPress Admin page.
Version:		1.0.0
Author:			Damien Oh
Author URI:		http://damienoh.com/
*/

if ( ! class_exists( 'DO_Editorial_Guidelines' ) ) {
	class DO_Editorial_Guidelines {
		public function __construct() {

			add_action( 'admin_menu', array( $this, 'register_editorial_menu' ) );

		}

		public function register_editorial_menu() {
			add_menu_page( 'Editorial Guidelines', 'Editorial Guidelines', 'edit_posts', 'editorial', array( $this, 'render_editorial' ), '', 8 );

			add_submenu_page( 'editorial','Settings','Settings', 'edit_posts', 'editorial_settings', array( $this, 'render_editorial_settings' ) );
		}

		public function render_editorial() {
			$user_id  = get_current_user_id();
			$read     = get_user_meta( $user_id, 'read_guidelines', true );
			$option = get_option( 'editorial_settings' );

			if ( empty( $read ) ) {
				$read = isset( $_REQUEST['readandagree'] ) ? $_REQUEST['readandagree'] : '';
				if ( $read ) {
					update_user_meta( $user_id, 'read_guidelines', 1 );
				}
			}
			?>
			<form method="get" action="">
				<input type="hidden" name="page" value="<?php echo sanitize_text_field( $_REQUEST['page'] ); ?>" />

				<?php $title = isset( $option['title'] ) ? $option['title'] : 'Editorial Guidelines'; ?>
				<h1><?php echo $title; ?></h1>
				<?php if ( empty( $read ) ) : ?>
					<div class="update-nag">Please take some time to read through the editorial guideline. At the end, check the box "<strong>I have read and agree to abide by this editorial guidelines.</strong>" and submit your agreement.
					</div>
				<?php endif; ?>
				<div style="font-size:larger;">
					<?php $content = isset( $option['content'] ) ? $option['content'] : ''; ?>
					<?php echo wpautop( $content ); ?>
					<p>
						<input type="checkbox" name="readandagree" value="1" <?php checked( 1, $read ); ?> <?php if ( $read ) echo 'disabled'; ?>>&nbsp;
						<strong>I have read and agree to abide by this editorial guidelines.</strong></p>
				</div>
				<?php if ( empty( $read ) ) : ?>
					<p class="submit">
						<?php submit_button( 'Submit Agreement', 'primary', 'submit', false ); ?>
					</p>
				<?php endif; ?>
			</form>
			<?php
		}

		public function render_editorial_settings() {
			$option = get_option( 'editorial_settings' );
			?>
			<div class="wrap">
				<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
				<form method="post" action="options.php">
					<h3>Editorial Guidelines</h3>

					<div id="titlediv">
						<div id="titlewrap">
							<input id="title" type="text" autocomplete="off" value="<?php echo $option['title'];?>" size="30" name="editorial_settings[title]" placeholder="Enter title here">
						</div>
					</div>
					<?php wp_editor( $option['content'], 'editorial_content', array( 'textarea_name' => 'editorial_settings[content]' ,'wpautop' => false ) ); ?>
					<p class="submit">
						<?php submit_button( 'Save Options', 'primary', 'submit', false ); ?>
					</p>
				</form>
			</div> <!-- end wrap -->

			<style>::-webkit-input-placeholder {
					color: grey;
				}

				::-moz-placeholder {
					color: #808080;
				}</style>
			<?php
		}
	}
}
new DO_Editorial_Guidelines;