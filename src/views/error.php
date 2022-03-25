<div class="wrap ">
    <h2><?php esc_html_e( 'CSV To DB', 'csv2db' ); ?></h2>
	<?php if ( $this->message ) : ?>
        <div class="updated error">
            <p><?php /* already sanitized message */ _e( $this->message );  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
        </div>
	<?php endif; ?>
</div>
