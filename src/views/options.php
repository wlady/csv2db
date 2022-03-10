<div class="wrap ">
    <h2><?php _e( 'CSV To DB', 'csv2db' ); ?></h2>
    <form action="options.php" method="post">
		<?php settings_fields( 'csv2db' ); ?>
        <h3><?php _e( 'CSV Import Options', 'csv2db' ); ?></h3>
        <table class="form-table">
            <tr valign="top">
                <td scope="row" width="200">
					<?php _e( 'Use LOCAL', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="checkbox" name="csv2db[use-local]"
                           value="1" <?php echo( $this->get_option( 'use-local' ) ? 'checked="checked"' : '' ) ?> />
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php _e( 'Fields Terminated By', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="text" name="csv2db[fields-terminated]"
                           value="<?php echo htmlspecialchars( $this->get_option( 'fields-terminated' ), ENT_QUOTES ); ?>"
                           size="100"/>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php _e( 'Fields Enclosed By', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="text" name="csv2db[fields-enclosed]"
                           value="<?php echo htmlspecialchars( $this->get_option( 'fields-enclosed' ), ENT_QUOTES ); ?>"
                           size="100"/>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php _e( 'Fields Escaped By', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="text" name="csv2db[fields-escaped]"
                           value="<?php echo htmlspecialchars( $this->get_option( 'fields-escaped' ), ENT_QUOTES ); ?>"
                           size="100"/>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php _e( 'Lines Starting By', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="text" name="csv2db[lines-starting]"
                           value="<?php echo $this->get_option( 'lines-starting' ); ?>" size="100"/>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php _e( 'Lines Terminated By', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="text" name="csv2db[lines-terminated]"
                           value="<?php echo htmlspecialchars( $this->get_option( 'lines-terminated' ), ENT_QUOTES ); ?>"
                           size="100"/>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'csv2db' ) ?>"/>
            <input type="submit" name="csv2db-defaults" id="wp-csv2db-defaults" class="button-primary"
                   value="<?php _e( 'Reset to Defaults', 'csv2db' ) ?>" onclick="return confirmResetCSV2DB()"/>
        </p>
    </form>
</div>
<script>
    function confirmResetCSV2DB() {
        return confirm("<?php _e( 'Are you sure to reset CSV To DB settings?', 'csv2db' ) ?>");
    }
</script>