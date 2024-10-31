<?php
/**
 * Redirect message
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<!DOCTYPE html>

<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

		<title><?php esc_html_e( 'Checkout', 'pronamic-ideal' ); ?></title>

		<?php

		/**
		 * Prints scripts or data in the head tag on the Adyen checkout page.
		 *
		 * This action can be used, for example, to register and print a custom style.
		 *
		 * See the following link for an example:
		 * https://github.com/wp-pay-gateways/adyen#pronamic_pay_adyen_checkout_head
		 *
		 * @link https://github.com/WordPress/WordPress/blob/5.7/wp-includes/general-template.php#L3004-L3009
		 *
		 * @since 1.1 Added.
		 */
		do_action( 'pronamic_pay_adyen_checkout_head' );

		?>
	</head>

	<body>
		<div class="pronamic-pay-redirect-page">
			<div class="pronamic-pay-redirect-container">

				<div class="pp-page-section-container">
					<div class="pp-page-section-wrapper">
						<div id="pronamic-pay-checkout"></div>
					</div>
				</div>

				<div class="pp-page-section-container">
					<div class="pp-page-section-wrapper alignleft">
						<h1><?php esc_html_e( 'Payment', 'pronamic-ideal' ); ?></h1>

						<dl>
							<dt><?php esc_html_e( 'Date', 'pronamic-ideal' ); ?></dt>
							<dd><?php echo esc_html( $payment->get_date()->format_i18n() ); ?></dd>

							<dt><?php esc_html_e( 'Description', 'pronamic-ideal' ); ?></dt>
							<dd><?php echo esc_html( (string) $payment->get_description() ); ?></dd>

							<dt><?php esc_html_e( 'Amount', 'pronamic-ideal' ); ?></dt>
							<dd><?php echo esc_html( $payment->get_total_amount()->format_i18n() ); ?></dd>
						</dl>
					</div>
				</div>

			</div>
		</div>

		<?php

		wp_print_scripts( 'pronamic-pay-adyen-checkout-drop-in' );

		?>
	</body>
</html>
