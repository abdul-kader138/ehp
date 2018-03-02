<?php
if($this->input->post('submit')) {

    $v = "";

    if($this->input->post('building_code')){
        $v .= "&building_code=".$this->input->post('building_code');
    }
    if($this->input->post('start_date')){
        $v .= "&start_date=".$this->input->post('start_date');
    }
    if($this->input->post('end_date')) {
        $v .= "&end_date=".$this->input->post('end_date');
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
        $("#show").click(function(){
            var sDate=$("#start_date").val();
            var eDate=$("#end_date").val();
            var eBuildingCode=$("#building_code").val();
            window.open("index.php?module=reports&view=invoice_view_details&sDate=" +sDate+'&eDate='+eDate+'&eID='+eBuildingCode, 'MyWindow','toolbar=0,location=0,directories=0,status=0,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=600');
        })
    });


</script>


<link href="<?php echo $this->config->base_url(); ?>assets/css/datepicker.css" rel="stylesheet">

<h3 class="title"><?php echo $page_title; ?> <a href="#" class="btn btn-mini toggle_form"><?php echo $this->lang->line("show_hide"); ?></a></h3>

<div class="form">
    <p>Please customise the report below.</p>
    <?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=reports&view=sales", $attrib); ?>

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
        <div class="controls"> <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="span4" id="start_date"');?> </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="end_date"><?php echo $this->lang->line("end_date"); ?></label>
        <div class="controls"> <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="span4" id="end_date"');?> </div>
    </div>

<!--    <a href="#" onclick="MyWindow=window.open('index.php?module=reports&amp;view=view_invoice_print&amp;id=4', 'MyWindow','toolbar=0,location=0,directories=0,status=0,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=600'); return false;" title="" class="tip" data-original-title="Small Invoice">Click Here</a>-->
    <div class="control-group">
        <div class="controls"> <?php echo form_button('show', $this->lang->line("show"), ' id="show" class="btn btn-primary"');?> </div>
    </div>
    <?php echo form_close();?>

</div>
<div class="clearfix"></div>

<p>&nbsp;</p>
