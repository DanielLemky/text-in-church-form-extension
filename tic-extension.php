<?php
/*
Plugin Name: Text In Church Form Extension
Plugin URI: https://quantumfirelabs.com
Description: A WordPress form builder plugin with notification emails and ability to view form submissions in WordPress Admin Area.
Version: 1.0.0
Author: Quantum Fire Labs
Author URI: https://quantumfirelabs.com
License: GPLv2 or later
Text Domain: tic_extension
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants
define( 'TIC_EXTENSION_PLUGIN_DIR', WP_PLUGIN_DIR . '/tic-extension.php/' );


if ( ! class_exists( 'TICFormExtension' ) ) :

	class TICFormExtension {

		public function __construct() {
			add_shortcode( 'tic-form-extension', array( $this, 'render_form' ) );
		}

		public function render_form( $atts ) {
			ob_start(); ?>

				<div id="submit-results" class="text-center"></div>
				<div>
					<form action="https://textinchurch.com/groups/saveWebform/<?= $atts['id'] ?>/new" method="post" id="tic-form-<?= $atts['id'] ?>">
						<input type="hidden" name="redir" value="webform_en/<?= $atts['id'] ?>/thankyou/new/0/new">
						<input type="hidden" name="contact_country" value="CA">
						<input type="hidden" name="failed_redir" value="/groups/webform/<?= $atts['id'] ?>">
						<div class="">
							<div class="form-row">
								<div class="form-group col-md-6">
									<label for="first-name">First Name</label>
									<input class="form-control" type="text" name="contact_first_name" id="first-name">
								</div>
								<div class="form-group col-md-6">
									<label for="last-name">Last Name</label>
									<input class="form-control" type="text" name="contact_last_name" id="last-name">
								</div>
							</div>
							<div class="form-group">
								<label for="email-address">Email Address</label>
								<input class="form-control" type="email" name="contact_email" id="email-address">
							</div>
							<div class="form-group">
								<label for="phone">Mobile Phone</label>
								<input class="form-control" type="tel" name="contact_mobile" id="phone">
							</div>
							<input id="tic-submit-button" type="submit" class="btn btn-primary">
						</div>
					</form>
				</div>

				<script>
				jQuery("#tic-form-<?= $atts['id'] ?>").submit(function(event) {
					event.preventDefault();
					var post_url = jQuery(this).attr("action");
					var request_method = jQuery(this).attr("method");
					var form_data = jQuery(this).serialize();

					jQuery.ajax({
						url : post_url,
						type: request_method,
						data : form_data
					}).done(function(response){ //
						thanksResponse = jQuery(response).find(".contact-card-container h3").text()
						if (thanksResponse) {
							jQuery("#submit-results").html("<span><strong>Thanks!</strong><br>We've successfully received your submission.</span>")
							jQuery("#submit-results").removeClass("alert alert-danger");
							jQuery("#submit-results").addClass("alert alert-success");
							jQuery('#tic-form-<?= $atts['id'] ?> #phone').css("border", "initial")
						} else {
							jQuery("#submit-results").html("<span>An error has occurred. Please verify that your phone number is correct.<br><a href='/contact-us'>If this problem continues, please contact us here</a>.</span>")
							jQuery("#submit-results").addClass("alert alert-danger");
							jQuery('#tic-form-<?= $atts['id'] ?> input').removeAttr('readonly');
							jQuery('#tic-submit-button').removeAttr('readonly');
							jQuery('#tic-submit-button').removeAttr('disabled');
							jQuery('#tic-form-<?= $atts['id'] ?> #phone').css("border", "1px solid #f97a86")
						}
					});

				});
				</script>

			<?php return ob_get_clean();
		}

	}

endif;

$tic_form_extension = new TICFormExtension;