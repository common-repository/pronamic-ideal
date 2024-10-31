<?php

/**
 * Class ActionScheduler_Logger
 * @codeCoverageIgnore
 */
abstract class ActionScheduler_Logger {

	/** @var null|self */
	private static $logger = NULL;

	/**
	 * Get instance.
	 *
	 * @return ActionScheduler_Logger
	 */
	public static function instance() {
		if ( empty(self::$logger) ) {
			$class = apply_filters('action_scheduler_logger_class', 'ActionScheduler_wpCommentLogger');
			self::$logger = new $class();
		}
		return self::$logger;
	}

	/**
	 * Create log entry.
	 *
	 * @param string   $action_id Action ID.
	 * @param string   $message   Log message.
	 * @param DateTime $date      Log date.
	 *
	 * @return string The log entry ID
	 */
	abstract public function log( $action_id, $message, DateTime $date = NULL );

	/**
	 * Get action's log entry.
	 *
	 * @param string $entry_id Entry ID.
	 *
	 * @return ActionScheduler_LogEntry
	 */
	abstract public function get_entry( $entry_id );

	/**
	 * Get action's logs.
	 *
	 * @param string $action_id Action ID.
	 *
	 * @return ActionScheduler_LogEntry[]
	 */
	abstract public function get_logs( $action_id );


	/**
	 * Initialize.
	 *
	 * @codeCoverageIgnore
	 */
	public function init() {
		$this->hook_stored_action();
		add_action( 'action_scheduler_canceled_action', array( $this, 'log_canceled_action' ), 10, 1 );
		add_action( 'action_scheduler_begin_execute', array( $this, 'log_started_action' ), 10, 2 );
		add_action( 'action_scheduler_after_execute', array( $this, 'log_completed_action' ), 10, 3 );
		add_action( 'action_scheduler_failed_execution', array( $this, 'log_failed_action' ), 10, 3 );
		add_action( 'action_scheduler_failed_action', array( $this, 'log_timed_out_action' ), 10, 2 );
		add_action( 'action_scheduler_unexpected_shutdown', array( $this, 'log_unexpected_shutdown' ), 10, 2 );
		add_action( 'action_scheduler_reset_action', array( $this, 'log_reset_action' ), 10, 1 );
		add_action( 'action_scheduler_execution_ignored', array( $this, 'log_ignored_action' ), 10, 2 );
		add_action( 'action_scheduler_failed_fetch_action', array( $this, 'log_failed_fetch_action' ), 10, 2 );
		add_action( 'action_scheduler_failed_to_schedule_next_instance', array( $this, 'log_failed_schedule_next_instance' ), 10, 2 );
		add_action( 'action_scheduler_bulk_cancel_actions', array( $this, 'bulk_log_cancel_actions' ), 10, 1 );
	}

	/**
	 * Register callback for storing action.
	 */
	public function hook_stored_action() {
		add_action( 'action_scheduler_stored_action', array( $this, 'log_stored_action' ) );
	}

	/**
	 * Unhook callback for storing action.
	 */
	public function unhook_stored_action() {
		remove_action( 'action_scheduler_stored_action', array( $this, 'log_stored_action' ) );
	}

	/**
	 * Log action stored.
	 *
	 * @param int $action_id Action ID.
	 */
	public function log_stored_action( $action_id ) {
		$this->log( $action_id, __( 'action created', 'pronamic-ideal' ) );
	}

	/**
	 * Log action cancellation.
	 *
	 * @param int $action_id Action ID.
	 */
	public function log_canceled_action( $action_id ) {
		$this->log( $action_id, __( 'action canceled', 'pronamic-ideal' ) );
	}

	/**
	 * Log action start.
	 *
	 * @param int    $action_id Action ID.
	 * @param string $context Action execution context.
	 */
	public function log_started_action( $action_id, $context = '' ) {
		if ( ! empty( $context ) ) {
			/* translators: %s: context */
			$message = sprintf( __( 'action started via %s', 'pronamic-ideal' ), $context );
		} else {
			$message = __( 'action started', 'pronamic-ideal' );
		}
		$this->log( $action_id, $message );
	}

	/**
	 * Log action completion.
	 *
	 * @param int                         $action_id Action ID.
	 * @param null|ActionScheduler_Action $action Action.
	 * @param string                      $context Action exeuction context.
	 */
	public function log_completed_action( $action_id, $action = NULL, $context = '' ) {
		if ( ! empty( $context ) ) {
			/* translators: %s: context */
			$message = sprintf( __( 'action complete via %s', 'pronamic-ideal' ), $context );
		} else {
			$message = __( 'action complete', 'pronamic-ideal' );
		}
		$this->log( $action_id, $message );
	}

	/**
	 * Log action failure.
	 *
	 * @param int       $action_id Action ID.
	 * @param Exception $exception Exception.
	 * @param string    $context Action execution context.
	 */
	public function log_failed_action( $action_id, Exception $exception, $context = '' ) {
		if ( ! empty( $context ) ) {
			/* translators: 1: context 2: exception message */
			$message = sprintf( __( 'action failed via %1$s: %2$s', 'pronamic-ideal' ), $context, $exception->getMessage() );
		} else {
			/* translators: %s: exception message */
			$message = sprintf( __( 'action failed: %s', 'pronamic-ideal' ), $exception->getMessage() );
		}
		$this->log( $action_id, $message );
	}

	/**
	 * Log action timeout.
	 *
	 * @param int    $action_id  Action ID.
	 * @param string $timeout Timeout.
	 */
	public function log_timed_out_action( $action_id, $timeout ) {
		/* translators: %s: amount of time */
		$this->log( $action_id, sprintf( __( 'action marked as failed after %s seconds. Unknown error occurred. Check server, PHP and database error logs to diagnose cause.', 'pronamic-ideal' ), $timeout ) );
	}

	/**
	 * Log unexpected shutdown.
	 *
	 * @param int     $action_id Action ID.
	 * @param mixed[] $error     Error.
	 */
	public function log_unexpected_shutdown( $action_id, $error ) {
		if ( ! empty( $error ) ) {
			/* translators: 1: error message 2: filename 3: line */
			$this->log( $action_id, sprintf( __( 'unexpected shutdown: PHP Fatal error %1$s in %2$s on line %3$s', 'pronamic-ideal' ), $error['message'], $error['file'], $error['line'] ) );
		}
	}

	/**
	 * Log action reset.
	 *
	 * @param int $action_id Action ID.
	 */
	public function log_reset_action( $action_id ) {
		$this->log( $action_id, __( 'action reset', 'pronamic-ideal' ) );
	}

	/**
	 * Log ignored action.
	 *
	 * @param int    $action_id Action ID.
	 * @param string $context Action execution context.
	 */
	public function log_ignored_action( $action_id, $context = '' ) {
		if ( ! empty( $context ) ) {
			/* translators: %s: context */
			$message = sprintf( __( 'action ignored via %s', 'pronamic-ideal' ), $context );
		} else {
			$message = __( 'action ignored', 'pronamic-ideal' );
		}
		$this->log( $action_id, $message );
	}

	/**
	 * Log the failure of fetching the action.
	 *
	 * @param string         $action_id Action ID.
	 * @param null|Exception $exception The exception which occurred when fetching the action. NULL by default for backward compatibility.
	 */
	public function log_failed_fetch_action( $action_id, Exception $exception = NULL ) {

		if ( ! is_null( $exception ) ) {
			/* translators: %s: exception message */
			$log_message = sprintf( __( 'There was a failure fetching this action: %s', 'pronamic-ideal' ), $exception->getMessage() );
		} else {
			$log_message = __( 'There was a failure fetching this action', 'pronamic-ideal' );
		}

		$this->log( $action_id, $log_message );
	}

	/**
	 * Log the failure of scheduling the action's next instance.
	 *
	 * @param int       $action_id Action ID.
	 * @param Exception $exception Exception object.
	 */
	public function log_failed_schedule_next_instance( $action_id, Exception $exception ) {
		/* translators: %s: exception message */
		$this->log( $action_id, sprintf( __( 'There was a failure scheduling the next instance of this action: %s', 'pronamic-ideal' ), $exception->getMessage() ) );
	}

	/**
	 * Bulk add cancel action log entries.
	 *
	 * Implemented here for backward compatibility. Should be implemented in parent loggers
	 * for more performant bulk logging.
	 *
	 * @param array $action_ids List of action ID.
	 */
	public function bulk_log_cancel_actions( $action_ids ) {
		if ( empty( $action_ids ) ) {
			return;
		}

		foreach ( $action_ids as $action_id ) {
			$this->log_canceled_action( $action_id );
		}
	}
}