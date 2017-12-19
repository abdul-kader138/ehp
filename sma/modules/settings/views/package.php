<script src="<?php echo base_url(); ?>assets/media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>

<style type="text/css">
    .text_filter { width: 100% !important; font-weight: normal !important; border: 0 !important; box-shadow: none !important;  border-radius: 0 !important;  padding:0 !important; margin:0 !important; font-size: 1em !important;}
    .select_filter { width: 100% !important; padding:0 !important; height: auto !important; margin:0 !important;}
    .table td { width: 12.5%; display: table-cell; }
    .table th { text-align: center; }
    .table td:nth-child(4), .table tfoot th:nth-child(4), .table td:nth-child(5), .table tfoot th:nth-child(5), .table td:nth-child(6), .table tfoot th:nth-child(6) { text-align:right; }
</style>
<script>
    $(document).ready(function() {

        $('#fileData').dataTable( {
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "aaSorting": [[ 1, "desc" ]],
            "iDisplayLength": <?php echo ROWS_PER_PAGE; ?>,
            <?php if(BSTATESAVE) { echo '"bStateSave": true,'; } ?>
            'bProcessing'    : true,
            'bServerSide'    : true,
            'sAjaxSource'    : '<?php echo base_url(); ?>index.php?module=settings&view=getPackageDataTableAjax<?php
					if($search_term) { echo "&search_term=".$search_term; } ?>',
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

                ]
            },
            "aoColumns": [
                 null,  null,null
            ],
            "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
            }
        }).columnFilter({ aoColumns: [
            null, null, null
        ]});

    });

</script>
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>
<?php if($success_message) { echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $success_message . "</div>"; } ?>







<h3 class="title"><?php echo $page_title; ?></h3>
<?php $attrib = array('class' => 'form-horizontal', 'id' => 'addSale_form');
echo form_open("module=settings&view=package", $attrib);
?>
<table id="fileData" class="table table-bordered table-hover table-striped table-condensed" style="margin-bottom: 5px;">

    <thead>
    <tr>

        <th><?php echo $this->lang->line("package_name"); ?></th>
        <th><?php echo $this->lang->line("package_qty"); ?></th>
        <th><?php echo $this->lang->line("actions"); ?></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="3" class="dataTables_empty">Loading data from server</td>
    </tr>
    </tbody>
    <tfoot>
    <tr>

        <th></th>
        <th></th>
        <th></th>
    </tr>
    </tfoot>
</table>


<p><a href="<?php echo site_url('module=settings&view=add_pck');?>" class="btn btn-primary">Add Package</a></p>

<?php echo form_close(); ?>

</div>


