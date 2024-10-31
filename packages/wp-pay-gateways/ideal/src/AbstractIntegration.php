<?php

namespace Pronamic\WordPress\Pay\Gateways\IDeal;

use Pronamic\WordPress\Pay\AbstractGatewayIntegration;

/**
 * Title: iDEAL abstract integration
 * Description:
 * Copyright: 2005-2023 Pronamic
 * Company: Pronamic
 *
 * @author  Remco Tolsma
 * @version 2.0.0
 * @since   1.1.2
 */
abstract class AbstractIntegration extends AbstractGatewayIntegration {
	/**
	 * Acquirer URL.
	 *
	 * @var string|null
	 */
	public $acquirer_url;

	/**
	 * Acquirer test URL.
	 *
	 * @var string|null
	 */
	public $acquirer_test_url;

	/**
	 * Get settings fields.
	 *
	 * @return array<int, array<string, callable|int|string|bool|array<int|string,int|string>>>
	 */
	public function get_settings_fields() {
		$fields = [];

		// Merchant ID
		$fields[] = [
			'section'  => 'general',
			'meta_key' => '_pronamic_gateway_ideal_merchant_id',
			'title'    => __( 'Merchant ID', 'pronamic-ideal' ),
			'type'     => 'text',
			'classes'  => [ 'code' ],
			'tooltip'  => sprintf(
				'%s %s.',
				__( 'Merchant ID (or Acceptant ID)', 'pronamic-ideal' ),
				__( 'as mentioned in the payment provider dashboard', 'pronamic-ideal' )
			),
		];

		// Sub ID
		$fields[] = [
			'section'     => 'advanced',
			'meta_key'    => '_pronamic_gateway_ideal_sub_id',
			'name'        => 'subId',
			'id'          => 'pronamic_ideal_sub_id',
			'title'       => __( 'Sub ID', 'pronamic-ideal' ),
			'type'        => 'text',
			'classes'     => [ 'small-text', 'code' ],
			'default'     => '0',
			'description' => sprintf(
				/* translators: %s: default code */
				__( 'Default: <code>%s</code>', 'pronamic-ideal' ),
				0
			),
			'tooltip'     => sprintf(
				'%s %s.',
				__( 'Sub ID', 'pronamic-ideal' ),
				__( 'as mentioned in the payment provider dashboard', 'pronamic-ideal' )
			),
		];

		// Purchase ID
		$fields[] = [
			'section'     => 'advanced',
			'meta_key'    => '_pronamic_gateway_ideal_purchase_id',
			'title'       => __( 'Purchase ID', 'pronamic-ideal' ),
			'type'        => 'text',
			'classes'     => [ 'regular-text', 'code' ],
			'tooltip'     => sprintf(
				/* translators: %s: <code>purchaseID</code> */
				__( 'The iDEAL %s parameter.', 'pronamic-ideal' ),
				sprintf( '<code>%s</code>', 'purchaseID' )
			),
			'description' => sprintf(
				'%s %s<br />%s',
				__( 'Available tags:', 'pronamic-ideal' ),
				sprintf(
					'<code>%s</code> <code>%s</code>',
					'{order_id}',
					'{payment_id}'
				),
				sprintf(
					/* translators: %s: default code */
					__( 'Default: <code>%s</code>', 'pronamic-ideal' ),
					'{payment_id}'
				)
			),
		];

		// Return fields
		return $fields;
	}
}
