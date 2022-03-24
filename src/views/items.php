<div class="wrap ">
	<?php wp_nonce_field( 'items-table' ); ?>
    <div id="icon-options-general" class="icon32"><br/></div>
    <h2><?php esc_html_e( 'CSV To DB Items', 'csv2db' ); ?></h2>
    <table id="table"
           data-toggle="table"
           data-ajax="ajaxRequest"
           data-show-refresh="true"
           data-show-columns="true"
           data-show-export="true"
           data-export-data-type="all"
           data-id-field="<?php esc_attr_e( $this->data_id_field ); ?>"
           data-striped="true"
           data-side-pagination="server"
           data-pagination="true"
           data-page-list="[5, 10, 20, 50, 100, 200]"
           data-search="true">
        <thead>
        <tr>
			<?php foreach ( $data['columns'] as $column ) : ?>
                <th data-field="<?php esc_attr_e( $column['name'] ); ?>"
				    <?php if ( isset( $column['check'] ) ) : ?>data-checkbox="true"<?php endif; ?>
                    data-align="<?php esc_attr_e( $column['align'] ); ?>"><?php esc_html_e( $column['title'] ); ?></th>
			<?php endforeach; ?>
        </tr>
        </thead>
    </table>
</div>
<script>
    var $table = jQuery('#table'),
        $remove = jQuery('#remove'),
        selections;

    $table.on('check.bs.table uncheck.bs.table ' +
        'check-all.bs.table uncheck-all.bs.table', function () {
        $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
        selections = getIdSelections();
    });

    // your custom ajax request here
    function ajaxRequest(params) {
        params.data['action'] = 'get_items';
        params.data['_ajax_nonce'] = jQuery('#_wpnonce').val();
        var jsonDataCall = jQuery.ajax({
            url: ajax.url,
            method: "POST",
            dataType: "json",
            data: params.data,
            success: function (response) {
                params.success(response);
            }
        });
    }

    function getIdSelections() {
        return jQuery.map($table.bootstrapTable('getSelections'), function (row) {
            return row.id
        });
    }
</script>