<?php

/**
 * Class ActionScheduler_wpPostStore_PostTypeRegistrar
 * @codeCoverageIgnore
 */
class ActionScheduler_wpPostStore_PostTypeRegistrar {
	/**
	 * Registrar.
	 */
	public function register() {
		register_post_type( ActionScheduler_wpPostStore::POST_TYPE, $this->post_type_args() );
	}

	/**
	 * Build the args array for the post type definition
	 *
	 * @return array
	 */
	protected function post_type_args() {
		$args = array(
			'label' => __( 'Scheduled Actions', 'pronamic-ideal' ),
			'description' => __( 'Scheduled actions are hooks triggered on a certain date and time.', 'pronamic-ideal' ),
			'public' => false,
			'map_meta_cap' => true,
			'hierarchical' => false,
			'supports' => array('title', 'editor','comments'),
			'rewrite' => false,
			'query_var' => false,
			'can_export' => true,
			'ep_mask' => EP_NONE,
			'labels' => array(
				'name' => __( 'Scheduled Actions', 'pronamic-ideal' ),
				'singular_name' => __( 'Scheduled Action', 'pronamic-ideal' ),
				'menu_name' => _x( 'Scheduled Actions', 'Admin menu name', 'pronamic-ideal' ),
				'add_new' => __( 'Add', 'pronamic-ideal' ),
				'add_new_item' => __( 'Add New Scheduled Action', 'pronamic-ideal' ),
				'edit' => __( 'Edit', 'pronamic-ideal' ),
				'edit_item' => __( 'Edit Scheduled Action', 'pronamic-ideal' ),
				'new_item' => __( 'New Scheduled Action', 'pronamic-ideal' ),
				'view' => __( 'View Action', 'pronamic-ideal' ),
				'view_item' => __( 'View Action', 'pronamic-ideal' ),
				'search_items' => __( 'Search Scheduled Actions', 'pronamic-ideal' ),
				'not_found' => __( 'No actions found', 'pronamic-ideal' ),
				'not_found_in_trash' => __( 'No actions found in trash', 'pronamic-ideal' ),
			),
		);

		$args = apply_filters('action_scheduler_post_type_args', $args);
		return $args;
	}
}
