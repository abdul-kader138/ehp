<script>
    $(document).ready(function() {
        $('#fileData').dataTable( {
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "aaSorting": [[ 0, "desc" ]],
            "iDisplayLength": <?php echo ROWS_PER_PAGE; ?>,
            <?php if(BSTATESAVE) { echo '"bStateSave": true,'; } ?>
            'bProcessing'    : true,
            'bServerSide'    : true,
            'sAjaxSource'    : '<?php echo base_url(); ?>index.php?module=reports&view=getDataTableInvoiceAjax',
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
                        "sFileName": "client_list.xls",
                        "mColumns": [ 0, 1, 2, 3,4,5,6,7 ]
                    },
                    {
                        "sExtends": "pdf",
                        "sFileName": "client_list.pdf",
                        "sPdfOrientation": "landscape",
                        "mColumns": [0, 1, 2, 3,4,5,6,7 ]
                    }
                ]
            },
            "aoColumns": [
                { "mRender": format_date },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "bSortable": true },
                { "mRender": format_date },
                { "mRender": format_date },
                { "mRender": currencyFormate },
                null
            ]

        } );

    } );

</script>

<!-- Errors -->
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>
<?php if($success_message) { echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $success_message . "</div>"; } ?>

<h3 class="title"><?php echo $page_title; ?></h3>

<table id="fileData" cellpadding="0" cellspacing="0" border="0" class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">
    <thead>
    <tr>
        <th>Creation <br/>Date</th>
        <th><?php echo $this->lang->line("code"); ?></th>
        <th>Vendor Name</th>
        <th>Building Name</th>
        <th>Location</th>
        <th>Date(From)</th>
        <th>Date(To)</th>
        <th>Total Amount</th>
        <th style="width:65px;"><?php echo $this->lang->line("actions"); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="9" class="dataTables_empty">Loading data from server</td>
    </tr>

    </tbody>
</table>

<!--<p><a href="--><?php //echo site_url('module=clients&view=add');?><!--" class="btn btn-primary">--><?php //echo $this->lang->line("new_client"); ?><!--</a></p>-->


