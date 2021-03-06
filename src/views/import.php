<div class="wrap ">
    <h2><?php esc_html_e( 'CSV To DB', 'csv2db' ); ?></h2>
	<?php if ( $this->message ) : ?>
        <div class="updated <?php if ( $error ) {
	        esc_html_e( 'Error' );
		} ?>">
            <p><?php esc_html_e( $this->message ); ?></p>
        </div>
	<?php endif; ?>
    <div id="output" class="updated hidden"></div>
    <form action="" method="post" enctype="multipart/form-data" id="upload_form" onsubmit="return false">
	    <?php wp_nonce_field( 'import-csv' ); ?>
        <input type="hidden" name="action" value="import_csv"/>
        <h3><?php esc_html_e( 'CSV Import', 'csv2db' ); ?></h3>
        <table class="form-table">
            <tr valign="top">
                <td scope="row" width="200">
					<?php esc_html_e( 'CSV File', 'csv2db' ); ?>
                </td>
                <td>
                    <input name="file" type="file"/>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php esc_html_e( 'Skip first rows', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="number" name="skip-rows" value="1" size="100"/>
                </td>
            </tr>
            <tr valign="top">
                <td scope="row">
					<?php esc_html_e( 'Re-Create table', 'csv2db' ); ?>
                </td>
                <td>
                    <input type="checkbox" name="re-create" value="1" checked="checked"/>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Import', 'csv2db' ) ?>" id="upload_btn"
                   data-toggle="tooltip" title="<?php esc_attr_e( 'Import CSV file', 'csv2db' ) ?>"/>
        </p>
    </form>
    <div id="progress-wrp" class="progress progress-striped active">
        <div class="progress-bar pbwrapper" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
             style="width:0%"></div>
    </div>
</div>
<script>
    var labels = {
        are_you_sure: "<?php esc_html_e( 'Are you sure?', 'csv2db' ) ?>",
        import: "<?php esc_html_e( 'Import', 'csv2db' ) ?>",
        old_browser: "<?php esc_html_e( 'Your browser does not support new File API! Please upgrade.', 'csv2db' ) ?>",
        unsupported: "<?php esc_html_e( 'Unsupported File!', 'csv2db' ); ?>",
        limit_exceeded: "<?php esc_html_e( 'Limit Exceeded!', 'csv2db' ); ?>",
        file_too_big: "<?php esc_html_e( 'File size is too big!', 'csv2db' ); ?>",
        wait: "<?php esc_html_e( 'Please Wait...', 'csv2db' ); ?>"
    };

    var max_file_size = parseInt("<?php esc_html_e( $this->upload_max_filesize ); ?>"); //allowed file size. (1 MB = 1048576)
    var allowed_file_types = ['text/csv', 'text/plain']; //allowed file types
    var result_output = '#output'; //ID of an element for response output
    var total_files_allowed = 1; //Number files allowed to upload
    var frm;
    var my_form_id;
    var button_id;
    var progress_bar;
    var progress_bar_wrapper;

    jQuery(document).ready(function () {
        jQuery('[data-toggle="tooltip"]').tooltip();
    });

    jQuery('#upload_btn').on("click", function (event) {
        event.preventDefault();
        my_form_id = '#upload_form'; //ID of an element for response output
        button_id = '#upload_btn';
        progress_bar = '.pbwrapper';
        progress_bar_wrapper = '#progress-wrp';
        frm = jQuery(my_form_id)[0];
        uploadFile(importItemsBtnCallback);
    });

</script>