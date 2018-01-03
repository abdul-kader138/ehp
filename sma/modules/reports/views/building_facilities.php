<?php
if($this->input->post('submit')) {
		   
		  $v = "";
		  /*if($this->input->post('name')){
			   $v .= "&name=".$this->input->post('name');
		   }*/ 

           if ($this->input->post('building_facilities')) {
               $v .= "&building_facilities=" . $this->input->post('building_facilities');
		   } 
		   if($this->input->post('building_code')){
			   $v .= "&building_code=".$this->input->post('building_code');
		   }

	  
}
?>
<script src="<?php echo base_url(); ?>assets/media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
<style type="text/css">
.text_filter { width: 100% !important; font-weight: normal !important; border: 0 !important; box-shadow: none !important;  border-radius: 0 !important;  padding:0 !important; margin:0 !important; font-size: 1em !important;}
.select_filter { width: 100% !important; padding:0 !important; height: auto !important; margin:0 !important;}
.table td { width: 12.5%; display: table-cell; }
.table th { text-align: center; }
.table td:nth-child(5) { font-size:90%; }
.table td:nth-child(6), .table tfoot th:nth-child(6), .table td:nth-child(7), .table tfoot th:nth-child(7), .table td:nth-child(8), .table tfoot th:nth-child(8) { text-align:right; }
</style>
<link href="<?php echo $this->config->base_url(); ?>assets/css/datepicker.css" rel="stylesheet">
<script src="<?php echo $this->config->base_url(); ?>assets/js/query-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$( "#start_date" ).datepicker({
        	format: "<?php echo JS_DATE; ?>",
			autoclose: true
    	});

		$( "#end_date" ).datepicker({
        	format: "<?php echo JS_DATE; ?>",
			autoclose: true
    	});
		<?php if(!isset($_POST['submit'])) { echo '$( "#end_date" ).datepicker("setDate", new Date());'; } ?>
		<?php if($this->input->post('submit')) { echo "$('.form').hide();"; } ?>
        $(".toggle_form").slideDown('slow');
 
		$('.toggle_form').click(function(){
			$(".form").slideToggle();
			return false;
		});
		
	});
</script>
<script> $(document).ready(function() {

        $('#fileData').dataTable( {
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "aaSorting": [[ 1, "desc" ]],
            "iDisplayLength": <?php echo ROWS_PER_PAGE; ?>,
            'bProcessing'    : true,
            'bServerSide'    : true,
            'sAjaxSource'    : '<?php echo base_url(); ?>index.php?module=reports&view=getBuildings<?php
					if($this->input->post('submit')) { echo $v; } ?>',
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
                        "sExtends": "csv",
                        "sFileName": "Building_Details_Report.csv",
                        "mColumns": [ 0, 1, 2, 3, 4,5]
                    },
                    {
                        "sExtends": "pdf",
                        "sFileName": "Building_Details_Report .pdf",
                        "sPdfOrientation": "landscape",
                        "mColumns": [ 0, 1, 2, 3, 4,5]
                    }
                ]
            },
            "aoColumns": [
                { "bSearchable": true },
                { "bSearchable": true },
                { "bSearchable": true },
                { "bSearchable": true },
                { "bSearchable": true },
                { "bSearchable": true }

            ]
        }).columnFilter({ aoColumns: [
            { type: "text", bRegex:true },
            { type: "text", bRegex:true },
            { type: "text", bRegex:true },
            { type: "text", bRegex:true },
            { type: "text", bRegex:true },
            { type: "text", bRegex:true }
        ]});

    });

</script>

<link href="<?php echo $this->config->base_url(); ?>assets/css/datepicker.css" rel="stylesheet">

<h3 class="title"><?php echo $page_title; ?> <a href="#" class="btn btn-mini toggle_form"><?php echo $this->lang->line("show_hide"); ?></a></h3>

<div class="form">
<p>Please customise the report below.</p>
<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=reports&view=building_facilities", $attrib); ?>

<div class="control-group">
  <label class="control-label" for="user"><?php echo $this->lang->line("building_code"); ?></label>
  <div class="controls"> <?php 
	   		$us[""] = "";
	   		foreach($buildings as $building){
				$us[$building->building_code] = $building->building_name;
			}
			echo form_dropdown('building_code', $us, (isset($_POST['building_code']) ? $_POST['building_code'] : ""), 'class="span4 select_search" id="user" data-placeholder="'.$this->lang->line("select")." ".$this->lang->line("building_code").'"');  ?> </div>
</div>

    <div class="control-group">
        <label class="control-label"
               for="building_facilities"><?php echo $this->lang->line("building_facilities"); ?></label>

        <div class="controls">
            <select name="building_facilities" id="building_facilities" class="span4 select_search">
                <option value="">Select Facilities</option>
                <option value="hasMedicalSupport">Medical Disability</option>
                <option value="hasHandicapAccess">Handicap Accessibility</option>
                <option value="isSmokeFreeZone">Smoke Free</option>
                <option value="hasElevatorSupport">Elevator Avail</option>
            </select>
        </div>
    </div>

<div class="control-group">
  <div class="controls"> <?php echo form_submit('submit', $this->lang->line("submit"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?>

</div>
<div class="clearfix"></div>

<?php if ($this->input->post('submit')) { ?>

    <table id="fileData" class="table table-bordered table-hover table-striped table-condensed"
           style="margin-bottom: 5px;">
        <thead>
        <tr>
            <th><?php echo $this->lang->line("building_code"); ?></th>
            <th><?php echo $this->lang->line("buildings_name"); ?></th>
            <th><?php echo $this->lang->line("level_code"); ?></th>
            <th><?php echo $this->lang->line("apartment_code"); ?></th>
            <th><?php echo $this->lang->line("location_buildings"); ?></th>
            <th>Days Vacant</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td colspan="6" class="dataTables_empty">Loading data from server</td>
        </tr>

        </tbody>
        <tfoot>

        <tr>
            <th><?php echo $this->lang->line("building_code"); ?></th>
            <th><?php echo $this->lang->line("buildings_name"); ?></th>
            <th><?php echo $this->lang->line("level_code"); ?></th>
            <th><?php echo $this->lang->line("apartment_code"); ?></th>
            <th><?php echo $this->lang->line("location_buildings"); ?></th>
            <th>Days Vacant</th>
        </tr>
        </tfoot>
    </table>

<?php } ?>
<p>&nbsp;</p>

