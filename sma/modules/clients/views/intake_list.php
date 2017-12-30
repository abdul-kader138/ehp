<script src="<?php echo base_url(); ?>assets/media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
<style type="text/css">
    .text_filter {
        width: 100% !important;
        font-weight: normal !important;
        border: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding:0 !important;
        margin:0 !important;
        font-size: 1em !important;
    }
    .select_filter {
        width: 100% !important;
        padding:0 !important;
        height: auto !important;
        margin:0 !important;
    }
    .table td {
        width: 12.5%;
        display: table-cell;
    }
    .table th {
        text-align: center;
    }
    .table td:nth-child(5), .table tfoot th:nth-child(5), .table td:nth-child(6), .table tfoot th:nth-child(6), .table td:nth-child(7), .table tfoot th:nth-child(7) {
        text-align:right;
    }
</style>
<script>
    $(document).ready(function() {
        $('#fileData').dataTable( {
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "aaSorting": [[ 0, "desc" ]],
            "iDisplayLength": <?php echo ROWS_PER_PAGE; ?>,
            <?php if(BSTATESAVE) { echo '"bStateSave": true,'; } ?>
            'bProcessing'    : true,
            'bServerSide'    : true,
            'sAjaxSource'    : '<?php echo base_url(); ?>index.php?module=clients&view=getDataTableIntakeAjax',
            'fnServerData': function(sSource, aoData, fnCallback)
            {
                aoData.push( { "name": "<?php echo $this->security->get_csrf_token_name(); ?>", "value": "<?php echo $this->security->get_csrf_hash() ?>" } );
                $.ajax
                ({
                    'dataType': 'json',
                    'type'    : 'POST',
                    'url'     : sSource,
                    'data'    : aoData,
                    'success' : fnCallback
                });
            },

            "oTableTools": {
                "sSwfPath": "assets/media/swf/copy_csv_xls_pdf.swf",
                "aButtons": [
                    {
                        "sExtends": "xls",
                        "sFileName": "client_intake_list.xls",
                        "mColumns": [ 0, 1, 2, 3, 4, 5, 6,7,8,9 ]
                    },
                    {
                        "sExtends": "pdf",
                        "sFileName": "client_intake_list.pdf",
                        "sPdfOrientation": "landscape",
                        "mColumns": [ 0, 1, 2, 3, 4, 5, 6,7,8,9 ]
                    }
                ]
            },
            "oLanguage": {
                "sSearch": "Filter: "
            },
            "aoColumns": [
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                null,
                null,
                null
            ]

        } );

    } );

</script>

<!-- Errors -->
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>
<?php if($success_message) { echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $success_message . "</div>"; } ?>

<h3 class="title"><?php echo $page_title; ?></h3>
<!--<h3 class="title">Product Discount</h3>-->
<table id="fileData" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">
    <thead>
    <tr>
<!--        <th>Intake Code</th>-->
        <th><?php echo $this->lang->line("client_code"); ?></th>
        <th><?php echo $this->lang->line("client_name"); ?></th>
        <th><?php echo $this->lang->line("client_type"); ?></th>
        <th><?php echo $this->lang->line("vendor_name"); ?></th>
        <th><?php echo $this->lang->line("building_code"); ?></th>
        <th><?php echo $this->lang->line("room_code"); ?></th>
        <th><?php echo $this->lang->line("status"); ?></th>
        <th><?php echo $this->lang->line("move_in_date"); ?></th>
        <th><?php echo $this->lang->line("move_out_date"); ?></th>
        <th>Days Staying</th>
        <th style="width:65px;"><?php echo $this->lang->line("actions"); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="10" class="dataTables_empty">Loading data from server</td>
    </tr>

    </tbody>
</table>

