<?php
/**
 * Subscription Post Type
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2024 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Subscriptions
 */

namespace Pronamic\WordPress\Pay\Subscriptions;

use Pronamic\WordPress\Pay\Payments\PaymentPostType;

/**
 * Title: WordPress iDEAL post types
 *
 * @author  Remco Tolsma
 * @version 2.2.6
 * @since   1.0.0
 */
class SubscriptionPostType {
	/**
	 * Constructs and initializes an post types object
	 */
	public function __construct() {
		/**
		 * Priority of the initial post types function should be set to < 10.
		 *
		 * @link https://core.trac.wordpress.org/ticket/28488
		 * @link https://core.trac.wordpress.org/changeset/29318
		 *
		 * @link https://github.com/WordPress/WordPress/blob/4.0/wp-includes/post.php#L167
		 */
		add_action( 'init', [ $this, 'register_subscription_post_type' ], 0 ); // Highest priority.
		add_action( 'init', [ $this, 'register_post_status' ], 9 );
	}

	/**
	 * Register post types.
	 *
	 * @link https://github.com/WordPress/WordPress/blob/4.6.1/wp-includes/post.php#L1277-L1300
	 * @return void
	 */
	public function register_subscription_post_type() {
		register_post_type(
			'pronamic_pay_subscr',
			[
				'label'              => __( 'Subscriptions', 'pronamic-ideal' ),
				'labels'             => [
					'name'                     => __( 'Subscriptions', 'pronamic-ideal' ),
					'singular_name'            => __( 'Subscription', 'pronamic-ideal' ),
					'add_new'                  => __( 'Add New', 'pronamic-ideal' ),
					'add_new_item'             => __( 'Add New Subscription', 'pronamic-ideal' ),
					'edit_item'                => __( 'Edit Subscription', 'pronamic-ideal' ),
					'new_item'                 => __( 'New Subscription', 'pronamic-ideal' ),
					'all_items'                => __( 'All Subscriptions', 'pronamic-ideal' ),
					'view_item'                => __( 'View Subscription', 'pronamic-ideal' ),
					'search_items'             => __( 'Search Subscriptions', 'pronamic-ideal' ),
					'not_found'                => __( 'No subscriptions found.', 'pronamic-ideal' ),
					'not_found_in_trash'       => __( 'No subscriptions found in Trash.', 'pronamic-ideal' ),
					'menu_name'                => __( 'Subscriptions', 'pronamic-ideal' ),
					'filter_items_list'        => __( 'Filter subscriptions list', 'pronamic-ideal' ),
					'items_list_navigation'    => __( 'Subscriptions list navigation', 'pronamic-ideal' ),
					'items_list'               => __( 'Subscriptions list', 'pronamic-ideal' ),

					/*
					 * New Post Type Labels in 5.0.
					 * @link https://make.wordpress.org/core/2018/12/05/new-post-type-labels-in-5-0/
					 */
					'item_published'           => __( 'Subscription published.', 'pronamic-ideal' ),
					'item_published_privately' => __( 'Subscription published privately.', 'pronamic-ideal' ),
					'item_reverted_to_draft'   => __( 'Subscription reverted to draft.', 'pronamic-ideal' ),
					'item_scheduled'           => __( 'Subscription scheduled.', 'pronamic-ideal' ),
					'item_updated'             => __( 'Subscription updated.', 'pronamic-ideal' ),
				],
				'public'             => false,
				'publicly_queryable' => false,
				'show_ui'            => true,
				'show_in_nav_menus'  => false,
				'show_in_menu'       => false,
				'show_in_admin_bar'  => false,
				'show_in_rest'       => true,
				'rest_base'          => 'pronamic-subscriptions',
				'supports'           => [
					'pronamic_pay_subscription',
				],
				'rewrite'            => false,
				'query_var'          => false,
				'capabilities'       => PaymentPostType::get_capabilities(),
				'map_meta_cap'       => true,
			]
		);
	}

	/**
	 * Get subscription states.
	 *
	 * @return array
	 */
	public static function get_states() {
		return [
			'subscr_pending'   => _x( 'Pending', 'Subscription status', 'pronamic-ideal' ),
			'subscr_cancelled' => _x( 'Cancelled', 'Subscription status', 'pronamic-ideal' ),
			'subscr_expired'   => _x( 'Expired', 'Subscription status', 'pronamic-ideal' ),
			'subscr_failed'    => _x( 'Failed', 'Subscription status', 'pronamic-ideal' ),
			'subscr_on_hold'   => _x( 'On Hold', 'Subscription status', 'pronamic-ideal' ),
			'subscr_active'    => _x( 'Active', 'Subscription status', 'pronamic-ideal' ),
			'subscr_completed' => _x( 'Completed', 'Subscription status', 'pronamic-ideal' ),
		];
	}

	/**
	 * Register our custom post statuses, used for order status.
	 *
	 * @return void
	 */
	public function register_post_status() {
		/**
		 * Subscription post statuses.
		 */
		register_post_status(
			'subscr_pending',
			[
				'label'                     => _x( 'Pending', 'Subscription status', 'pronamic-ideal' ),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: count value */
				'label_count'               => _n_noop( 'Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>', 'pronamic-ideal' ),
			]
		);

		register_post_status(
			'subscr_cancelled',
			[
				'label'                     => _x( 'Cancelled', 'Subscription status', 'pronamic-ideal' ),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: count value */
				'label_count'               => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'pronamic-ideal' ),
			]
		);

		register_post_status(
			'subscr_expired',
			[
				'label'                     => _x( 'Expired', 'Subscription status', 'pronamic-ideal' ),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: count value */
				'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'pronamic-ideal' ),
			]
		);

		register_post_status(
			'subscr_failed',
			[
				'label'                     => _x( 'Failed', 'Subscription status', 'pronamic-ideal' ),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: count value */
				'label_count'               => _n_noop( 'Failed <span class="count">(%s)</span>', 'Failed <span class="count">(%s)</span>', 'pronamic-ideal' ),
			]
		);

		register_post_status(
			'subscr_on_hold',
			[
				'label'                     => _x( 'On Hold', 'Subscription status', 'pronamic-ideal' ),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: count value */
				'label_count'               => _n_noop( 'On Hold <span class="count">(%s)</span>', 'On Hold <span class="count">(%s)</span>', 'pronamic-ideal' ),
			]
		);

		register_post_status(
			'subscr_active',
			[
				'label'                     => _x( 'Active', 'Subscription status', 'pronamic-ideal' ),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: count value */
				'label_count'               => _n_noop( 'Active <span class="count">(%s)</span>', 'Active <span class="count">(%s)</span>', 'pronamic-ideal' ),
			]
		);

		register_post_status(
			'subscr_completed',
			[
				'label'                     => _x( 'Completed', 'Subscription status', 'pronamic-ideal' ),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: %s: count value */
				'label_count'               => _n_noop( 'Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'pronamic-ideal' ),
			]
		);
	}
}
