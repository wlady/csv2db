<div class="wrap ">
    <div id="icon-options-general" class="icon32"><br/></div>
    <h2><?php _e( 'CSV to DB', 'csv2db' ); ?></h2>
	<?php if ( $this->message ) : ?>
        <div class="updated <?php if ( $error ) {
			echo 'error';
		} ?>">
            <p><?php _e( $this->message ); ?></p>
        </div>
	<?php endif; ?>
    <div id="output" class="updated hidden"></div>
	<?php if ( ! empty( $this->get_option( 'fields' ) ) ) : ?>
        <form action="" method="post" id="schema-table">
            <input type="hidden" name="action" value="save_schema"/>
            <h3><?php _e( 'Fields', 'csv2db' ); ?></h3>
            <table class="form-table table table-striped">
                <tr>
                    <th>
						<?php _e( 'Name', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php _e( 'Type', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php _e( 'Size', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php _e( 'Null', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php _e( 'AI', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php _e( 'Index', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php _e( 'Title', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php _e( 'Show', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php _e( 'Align', 'csv2db' ); ?>
                    </th>
                    <th>
						<?php _e( 'Check', 'csv2db' ); ?>
                    </th>
                </tr>
                <?php if ( ! empty( $this->get_option( 'fields' ) ) ) : ?>
				<?php foreach ( $this->get_option( 'fields' ) as $key => $field ) : ?>
                    <tr valign="top">
                        <td scope="row">
							<?php echo $field['name']; ?>
                            <input type="hidden" name="csv2db[fields][<?php echo $key; ?>][name]"
                                   value="<?php echo $field['name']; ?>"/>
                        </td>
                        <td>
                            <select name="csv2db[fields][<?php echo $key; ?>][type]" style="width:100%"
                                    onchange="changeSize(this.value, <?php echo $key; ?>)">
                                <option <?php if ( $field['type'] == 'VARCHAR' ) {
									echo 'selected';
								} ?>>VARCHAR
                                </option>
                                <option <?php if ( $field['type'] == 'TEXT' ) {
									echo 'selected';
								} ?>>TEXT
                                </option>
                                <option <?php if ( $field['type'] == 'BLOB' ) {
									echo 'selected';
								} ?>>BLOB
                                </option>
                                <option <?php if ( $field['type'] == 'INT' ) {
									echo 'selected';
								} ?>>INT
                                </option>
                                <option <?php if ( $field['type'] == 'FLOAT' ) {
									echo 'selected';
								} ?>>FLOAT
                                </option>
                                <option <?php if ( $field['type'] == 'DOUBLE' ) {
									echo 'selected';
								} ?>>DOUBLE
                                </option>
                                <option <?php if ( $field['type'] == 'DECIMAL' ) {
									echo 'selected';
								} ?>>DECIMAL
                                </option>
                            </select>
                        </td>
                        <td>
                            <input name="csv2db[fields][<?php echo $key; ?>][size]" type="text"
                                   value="<?php echo $field['size']; ?>" style="width:100%"/>
                        </td>
                        <td>
                            <input type="checkbox" name="csv2db[fields][<?php echo $key; ?>][null]"
                                   value="1" <?php echo ($field['null'] ?? 0) == 1 ? 'checked="checked"' : ''; ?> />
                        </td>
                        <td>
                            <input type="checkbox" name="csv2db[fields][<?php echo $key; ?>][ai]"
                                   value="1" <?php echo ($field['ai'] ?? 0) == 1 ? 'checked="checked"' : ''; ?> />
                        </td>
                        <td>
                            <select class="indexSelector" name="csv2db[fields][<?php echo $key; ?>][index]"
                                    style="width:100%" onchange="checkIndex(this.value, <?php echo $key; ?>)">
                                <option></option>
                                <option <?php if ( $field['index'] == 'PRIMARY' ) {
									echo 'selected';
								} ?>>PRIMARY
                                </option>
                                <option <?php if ( $field['index'] == 'UNIQUE' ) {
									echo 'selected';
								} ?>>UNIQUE
                                </option>
                                <option <?php if ( $field['index'] == 'INDEX' ) {
									echo 'selected';
								} ?>>INDEX
                                </option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="csv2db[fields][<?php echo $key; ?>][title]"
                                   value="<?php echo $field['title']; ?>"/>
                        </td>
                        <td>
                            <input type="checkbox" name="csv2db[fields][<?php echo $key; ?>][show]"
                                   value="1" <?php echo ($field['show'] ?? 0) == 1 ? 'checked="checked"' : ''; ?> />
                        </td>
                        <td>
                            <select name="csv2db[fields][<?php echo $key; ?>][align]" style="width:100%">
                                <option></option>
                                <option value="left" <?php if ( $field['align'] == 'left' ) {
									echo 'selected';
								} ?>><?php _e( 'Left', 'csv2db' ); ?></option>
                                <option value="center" <?php if ( $field['align'] == 'center' ) {
									echo 'selected';
								} ?>><?php _e( 'Center', 'csv2db' ); ?></option>
                                <option value="right" <?php if ( $field['align'] == 'right' ) {
									echo 'selected';
								} ?>><?php _e( 'Right', 'csv2db' ); ?></option>
                            </select>
                        </td>
                        <td>
                            <input class="checkSelector" type="checkbox"
                                   name="csv2db[fields][<?php echo $key; ?>][check]"
                                   value="1" <?php echo ($field['check'] ?? 0) == 1 ? 'checked="checked"' : ''; ?>
                                   onchange="checkOtherCheckboxes(<?php echo $key; ?>)"/>
                        </td>
                    </tr>
				<?php endforeach; ?>
				<?php endif; ?>
            </table>
            <p class="submit">
                <input type="submit" class="button-primary pull-left submitBtn"
                       value="<?php _e( 'Save Changes', 'csv2db' ) ?>" data-action="save_fields"
                       data-toggle="tooltip"
                       title="<?php _e( 'Save fields configuration', 'csv2db' ) ?>"/>
                <input type="submit" class="button pull-left submitBtn"
                       value="<?php _e( 'Export Fields', 'csv2db' ) ?>" data-action="export_fields"
                       data-toggle="tooltip" title="<?php _e( 'Export fields configuration', 'csv2db' ) ?>"/>
                <input type="submit" class="button pull-left submitBtn"
                       value="<?php _e( 'Clear Fields', 'csv2db' ) ?>"
                       data-action="clear_fields" data-toggle="tooltip"
                       title="<?php _e( 'Clear fields', 'csv2db' ) ?>"/>
                <input type="submit" class="button pull-right submitBtn"
                       value="<?php _e( 'Create DB Table', 'csv2db' ) ?>" data-action="create_table"
                       data-toggle="tooltip"
                       title="<?php _e( 'Create DB Table from current fields configuration', 'csv2db' ) ?>"/>
                <input type="submit" class="button pull-right submitBtn"
                       value="<?php _e( 'Export Schema', 'csv2db' ) ?>" data-action="export_schema"
                       data-toggle="tooltip" title="<?php _e( 'Export DB schema', 'csv2db' ) ?>"/>
            </p>
        </form>
	<?php endif; ?>
    <div class="clearfix"></div>
    <hr/>
    <form action="" method="post" enctype="multipart/form-data" id="import_fields_form">
        <input type="hidden" name="action" value="import_fields"/>
        <h3><?php _e( 'Import Fields', 'csv2db' ); ?></h3>
        <table class="form-table" id="import-fields">
            <tr valign="top">
                <td scope="row" width="200">
					<?php _e( 'Data File', 'csv2db' ); ?>
                </td>
                <td>
                    <input name="file" type="file"/>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e( 'Import', 'csv2db' ) ?>"
                   id="import_fields_btn" data-toggle="tooltip"
                   title="<?php _e( 'Import fields configuration', 'csv2db' ) ?>"
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
        <h3><?php _e( 'Analyze CSV', 'csv2db' ); ?></h3>
        <table class="form-table">
            <tr valign="top">
                <td scope="row" width="200">
					<?php _e( 'CSV File', 'csv2db' ); ?>
                </td>
                <td>
                    <input name="file" type="file"/>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="button" class="button-primary" value="<?php _e( 'Analyze', 'csv2db' ) ?>" id="upload_btn"
                   data-toggle="tooltip"
                   title="<?php _e( 'Analyze CSV file and create the fields configuration', 'csv2db' ) ?>"
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
        are_you_sure: "<?php _e( 'Are you sure?', 'csv2db' ) ?>",
        upload: "<?php _e( 'Upload', 'csv2db' ) ?>",
        old_browser: "<?php _e( 'Your browser does not support new File API! Please upgrade.', 'csv2db' ) ?>",
        unsupported: "<?php _e( 'Unsupported File!', 'csv2db' ); ?>",
        limit_exceeded: "<?php _e( 'Limit Exceeded!', 'csv2db' ); ?>",
        file_too_big: "<?php _e( 'File size is too big!', 'csv2db' ); ?>",
        wait: "<?php _e( 'Please Wait...', 'csv2db' ); ?>"
    };

    var max_file_size = <?php echo $this->upload_max_filesize; ?>; //allowed file size. (1 MB = 1048576)
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