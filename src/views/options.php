<div class="wrap ">
    <h2><?php esc_html_e( 'CSV To DB', 'csv2db' ); ?></h2>
    <form action="options.php" method="post">
		<?php settings_fields( 'csv2db' ); ?>
        <h3><?php esc_html_e( 'CSV Import Options', 'csv2db' ); ?></h3>
        <table class="form-table">
            <tr valign="top">
                <td scope="row" width="200">
					<?php esc_html_e( 'Use LOCAL', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="checkbox" name="csv2db[use-local]"
                           value="1" <?php esc_attr_e( $this->get_option( 'use-local' ) ? 'checked' : '' ) ?> />
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php esc_html_e( 'Fields Terminated By', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="text" name="csv2db[fields-terminated]"
                           value="<?php esc_attr_e( $this->get_option( 'fields-terminated' ) ); ?>"
                           size="100"/>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php esc_html_e( 'Fields Enclosed By', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="text" name="csv2db[fields-enclosed]"
                           value="<?php esc_attr_e( $this->get_option( 'fields-enclosed' ) ); ?>"
                           size="100"/>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php esc_html_e( 'Fields Escaped By', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="text" name="csv2db[fields-escaped]"
                           value="<?php esc_attr_e( $this->get_option( 'fields-escaped' ) ); ?>"
                           size="100"/>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php esc_html_e( 'Lines Starting By', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="text" name="csv2db[lines-starting]"
                           value="<?php esc_attr_e( $this->get_option( 'lines-starting' ) ); ?>" size="100"/>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php esc_html_e( 'Lines Terminated By', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="text" name="csv2db[lines-terminated]"
                           value="<?php esc_attr_e( $this->get_option( 'lines-terminated' ) ); ?>"
                           size="100"/>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'csv2db' ) ?>"/>
            <input type="submit" name="csv2db-defaults" id="wp-csv2db-defaults" class="button-primary"
                   value="<?php esc_attr_e( 'Reset to Defaults', 'csv2db' ) ?>" onclick="return confirmResetCSV2DB()"/>
        </p>
    </form>
</div>
<script>
    function confirmResetCSV2DB() {
        return confirm("<?php esc_html_e( 'Are you sure to reset CSV To DB settings?', 'csv2db' ) ?>");
    }
</script>