<script src="<?php echo base_url(); ?>assets/media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>

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

//        $("#show").click(function(){
//            var sDate=$("#start_date").val();
//            var eDate=$("#end_date").val();
//            var eBuildingCode=$("#building_code").val();
//            window.open("index.php?module=reports&view=invoice_view_details&sDate=" +sDate+'&eDate='+eDate+'&eID='+eBuildingCode, 'MyWindow','toolbar=0,location=0,directories=0,status=0,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=600');
//        })
    });


</script>




<!--ggh-->


<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<link href="<?php echo $this->config->base_url(); ?>assets/css/bootstrap-fileupload.css" rel="stylesheet">
<script type="text/javascript">
    $(function() {
        $('form').form();
        $( "#date_of_birth" ).datepicker({
            format: "<?php echo JS_DATE; ?>",
            autoclose: true
        });

    });

</script>

<link href="<?php echo $this->config->base_url(); ?>assets/css/datepicker.css" rel="stylesheet">
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>


<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>

<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=reports&view=invoice_view", $attrib);?>

<div class="control-group">
    <label class="control-label" for="customer"><?php echo $this->lang->line("buildings_name"); ?></label>
    <div class="controls"> <?php
        $cu[""] = "";
        foreach($buildings as $building){
            $cu[$building->building_code] = $building->building_name;
        }
        echo form_dropdown('building_code', $cu, (isset($_POST['building_code']) ? $_POST['building_code'] : ""), 'class="span4" id="building_code" data-placeholder="'.$this->lang->line("select")." ".$this->lang->line("building_name").'"');  ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="start_date"><?php echo $this->lang->line("start_date"); ?></label>
    <div class="controls"> <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="span4" required="required" id="start_date"');?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="end_date"><?php echo $this->lang->line("end_date"); ?></label>
    <div class="controls"> <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="span4" required="required" id="end_date"');?> </div>
</div>


<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("new_client"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?>

<!--ggh-->