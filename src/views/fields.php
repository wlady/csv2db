<div class="wrap ">
    <div id="icon-options-general" class="icon32"><br/></div>
    <h2><?php esc_html_e( 'CSV To DB', 'csv2db' ); ?></h2>
	<?php if ( $this->message ) : ?>
        <div class="updated <?php if ( $error ) {
	        esc_html_e( 'Error' );
		} ?>">
            <p><?php esc_html_e( $this->message ); ?></p>
        </div>
	<?php endif; ?>
    <div id="output" class="updated hidden"></div>
	<?php if ( ! empty( $this->get_option( 'fields' ) ) ) : ?>
        <form action="" method="post" id="schema-table">
	        <?php wp_nonce_field( 'csv2db-options' ); ?>
            <input type="hidden" name="action" value="save_schema"/>
            <h3><?php esc_html_e( 'Fields', 'csv2db' ); ?></h3>
            <table class="form-table table table-striped">
                <tr>
                    <th>
						<?php esc_html_e( 'Name', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php esc_html_e( 'Type', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php esc_html_e( 'Size', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php esc_html_e( 'Null', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php esc_html_e( 'AI', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php esc_html_e( 'Index', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php esc_html_e( 'Title', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php esc_html_e( 'Show', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php esc_html_e( 'Align', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php esc_html_e( 'Check', 'csv2db' ); ?>
                    </th>
                </tr>
                <?php if ( ! empty( $this->get_option( 'fields' ) ) ) : ?>
				<?php foreach ( $this->get_option( 'fields' ) as $key => $field ) : ?>
                    <tr valign="top">
                        <td scope="row">
							<?php esc_html_e( $field['name'] ); ?>
                            <input type="hidden" name="csv2db[fields][<?php esc_attr_e( $key ); ?>][name]"
                                   value="<?php esc_attr_e( $field['name'] ); ?>"/>
                        </td>
                        <td>
                            <select name="csv2db[fields][<?php esc_attr_e( $key ); ?>][type]" style="width:100%"
                                    onchange="changeSize(this.value, <?php esc_attr_e( $key ); ?>)">
                                <option <?php if ( $field['type'] == 'VARCHAR' ) {
	                                esc_html_e( 'selected' );
								} ?>>VARCHAR
                                </option>
                                <option <?php if ( $field['type'] == 'TEXT' ) {
	                                esc_html_e( 'selected' );
								} ?>>TEXT
                                </option>
                                <option <?php if ( $field['type'] == 'BLOB' ) {
	                                esc_html_e( 'selected' );
								} ?>>BLOB
                                </option>
                                <option <?php if ( $field['type'] == 'INT' ) {
	                                esc_html_e( 'selected' );
								} ?>>INT
                                </option>
                                <option <?php if ( $field['type'] == 'FLOAT' ) {
	                                esc_html_e( 'selected' );
								} ?>>FLOAT
                                </option>
                                <option <?php if ( $field['type'] == 'DOUBLE' ) {
	                                esc_html_e( 'selected' );
								} ?>>DOUBLE
                                </option>
                                <option <?php if ( $field['type'] == 'DECIMAL' ) {
	                                esc_html_e( 'selected' );
								} ?>>DECIMAL
                                </option>
                            </select>
                        </td>
                        <td>
                            <input name="csv2db[fields][<?php esc_attr_e( $key ); ?>][size]" type="text"
                                   value="<?php esc_attr_e( $field['size'] ); ?>" style="width:100%"/>
                        </td>
                        <td>
                            <input type="checkbox" name="csv2db[fields][<?php esc_attr_e( $key ); ?>][null]"
                                   value="1" <?php esc_html_e( ($field['null'] ?? 0) == 1 ? 'checked' : '' ); ?> />
                        </td>
                        <td>
                            <input type="checkbox" name="csv2db[fields][<?php esc_attr_e( $key ); ?>][ai]"
                                   value="1" <?php esc_html_e( ($field['ai'] ?? 0) == 1 ? 'checked' : '' ); ?> />
                        </td>
                        <td>
                            <select class="indexSelector" name="csv2db[fields][<?php esc_attr_e( $key ); ?>][index]"
                                    style="width:100%" onchange="checkIndex(this.value, <?php esc_attr_e( $key ); ?>)">
                                <option></option>
                                <option <?php if ( $field['index'] == 'PRIMARY' ) {
	                                esc_html_e( 'selected' );
								} ?>>PRIMARY
                                </option>
                                <option <?php if ( $field['index'] == 'UNIQUE' ) {
	                                esc_html_e( 'selected' );
								} ?>>UNIQUE
                                </option>
                                <option <?php if ( $field['index'] == 'INDEX' ) {
	                                esc_html_e( 'selected' );
								} ?>>INDEX
                                </option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="csv2db[fields][<?php esc_attr_e( $key ); ?>][title]"
                                   value="<?php esc_attr_e( $field['title'] ); ?>"/>
                        </td>
                        <td>
                            <input type="checkbox" name="csv2db[fields][<?php esc_attr_e( $key ); ?>][show]"
                                   value="1" <?php esc_html_e( ($field['show'] ?? 0) == 1 ? 'checked' : '' ); ?> />
                        </td>
                        <td>
                            <select name="csv2db[fields][<?php esc_attr_e( $key ); ?>][align]" style="width:100%">
                                <option></option>
                                <option value="left" <?php if ( $field['align'] == 'left' ) {
	                                esc_html_e( 'selected' );
								} ?>><?php _e( 'Left', 'csv2db' ); ?></option>
                                <option value="center" <?php if ( $field['align'] == 'center' ) {
	                                esc_html_e( 'selected' );
								} ?>><?php _e( 'Center', 'csv2db' ); ?></option>
                                <option value="right" <?php if ( $field['align'] == 'right' ) {
	                                esc_html_e( 'selected' );
								} ?>><?php _e( 'Right', 'csv2db' ); ?></option>
                            </select>
                        </td>
                        <td>
                            <input class="checkSelector" type="checkbox"
                                   name="csv2db[fields][<?php esc_attr_e( $key ); ?>][check]"
                                   value="1" <?php esc_html_e( ($field['check'] ?? 0) == 1 ? 'checked' : '' ); ?>
                                   onchange="checkOtherCheckboxes(<?php esc_attr_e( $key ); ?>)"/>
                        </td>
                    </tr>
				<?php endforeach; ?>
				<?php endif; ?>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary pull-left submitBtn"
                       value="<?php esc_attr_e( 'Save Changes', 'csv2db' ) ?>" data-action="save_fields"
                       data-toggle="tooltip"
                       title="<?php esc_attr_e( 'Save fields configuration', 'csv2db' ) ?>"/>
                <input type="submit" class="button pull-left submitBtn"
                       value="<?php esc_attr_e( 'Export Fields', 'csv2db' ) ?>" data-action="export_fields"
                       data-toggle="tooltip" title="<?php esc_attr_e( 'Export fields configuration', 'csv2db' ) ?>"/>
                <input type="submit" class="button pull-left submitBtn"
                       value="<?php esc_attr_e( 'Clear Fields', 'csv2db' ) ?>"
                       data-action="clear_fields" data-toggle="tooltip"
                       title="<?php esc_attr_e( 'Clear fields', 'csv2db' ) ?>"/>
                <input type="submit" class="button pull-right submitBtn"
                       value="<?php esc_attr_e( 'Create DB Table', 'csv2db' ) ?>" data-action="create_table"
                       data-toggle="tooltip"
                       title="<?php esc_attr_e( 'Create DB Table from current fields configuration', 'csv2db' ) ?>"/>
                <input type="submit" class="button pull-right submitBtn"
                       value="<?php esc_attr_e( 'Export Schema', 'csv2db' ) ?>" data-action="export_schema"
                       data-toggle="tooltip" title="<?php esc_attr_e( 'Export DB schema', 'csv2db' ) ?>"/>
            </p>
        </form>
	<?php endif; ?>
    <div class="clearfix"></div>
    <hr/>
    <form action="" method="post" enctype="multipart/form-data" id="import_fields_form">
	    <?php wp_nonce_field( 'csv2db-options' ); ?>
        <input type="hidden" name="action" value="import_fields"/>
        <h3><?php esc_html_e( 'Import Fields', 'csv2db' ); ?></h3>
        <table class="form-table" id="import-fields">
            <tr valign="top">
                <td scope="row" width="200">
					<?php esc_html_e( 'Data File', 'csv2db' ); ?>
                </td>
                <td>
                    <input name="file" type="file"/>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Import', 'csv2db' ) ?>"
                   id="import_fields_btn" data-toggle="tooltip"
                   title="<?php esc_attr_e( 'Import fields configuration', 'csv2db' ) ?>"
                   onclick="return confirmImportFields()"/>
        </p>
    </form>
    <div id="progress-wrp2" class="progress progress-striped active">
        <div class="progress-bar pbwrapper2" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
             style="width:0%"></div>
    </div>
    <div class="clearfix"></div>
    <hr/>
    <form action="" method="post" enctype="multipart/form-data" id="analyze_form" onsubmit="return false">
	    <?php wp_nonce_field( 'csv2db-options' ); ?>
        <h3><?php esc_html_e( 'Analyze CSV', 'csv2db' ); ?></h3>
        <table class="form-table">
            <tr valign="top">
                <td scope="row" width="200">
					<?php esc_html_e( 'CSV File', 'csv2db' ); ?>
                </td>
                <td>
                    <input name="file" type="file"/>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="button" class="button-primary" value="<?php esc_attr_e( 'Analyze', 'csv2db' ) ?>" id="upload_btn"
                   data-toggle="tooltip"
                   title="<?php esc_attr_e( 'Analyze CSV file and create the fields configuration', 'csv2db' ) ?>"
            onclick="return analyzeForm()"/>
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
        upload: "<?php esc_html_e( 'Upload', 'csv2db' ) ?>",
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

    jQuery('#upload_btn').on("click", function (event) {
        event.preventDefault();
        my_form_id = '#upload_form'; //ID of an element for response output
        button_id = '#upload_btn';
        progress_bar = '.pbwrapper';
        progress_bar_wrapper = '#progress-wrp';
        frm = jQuery(my_form_id)[0];
        uploadFile(uploadBtnCallback);
    });

    jQuery('#import_fields_btn').on("click", function (event) {
        event.preventDefault();
        my_form_id = '#import_fields_form'; //ID of an element for response output
        button_id = '#import_fields_btn';
        progress_bar = '.pbwrapper2';
        progress_bar_wrapper = '#progress-wrp2';
        frm = jQuery(my_form_id)[0];
        uploadFile(importFieldsBtnCallback);
    });

    jQuery(document).ready(function () {
        jQuery('[data-toggle="tooltip"]').tooltip();
    });

    jQuery('.submitBtn').on('click', function () {
        var action = jQuery(this).data('action');
        if (action == 'create_table' || action == 'clear_fields') {
            if (!confirm(labels.are_you_sure)) {
                return false;
            }
        }
        jQuery('input[name=action]').val(action);
    });
</script>