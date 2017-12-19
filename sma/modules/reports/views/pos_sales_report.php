<?php
if($this->input->post('submit')) {

    $v = "";
    /*if($this->input->post('name')){
         $v .= "&name=".$this->input->post('name');
     }*/
    if($this->input->post('reference_no')){
        $v .= "&reference_no=".$this->input->post('reference_no');
    }
    if($this->input->post('customer')){
        $v .= "&customer=".$this->input->post('customer');
    }
    if($this->input->post('biller')){
        $v .= "&biller=".$this->input->post('biller');
    }
    if($this->input->post('warehouse')){
        $v .= "&warehouse=".$this->input->post('warehouse');
    }
    if($this->input->post('paid_by')){
        $v .= "&paid_by=".$this->input->post('paid_by');
    }
    if($this->input->post('user')){
        $v .= "&user=".$this->input->post('user');
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
//            var sDate="11";
            var eDate=$("#end_date").val();
            console.log(sDate);
            window.open("index.php?module=reports&view=pos_view&sDate=" +sDate+'&eDate='+eDate, 'MyWindow','toolbar=0,location=0,directories=0,status=0,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=600');
        })
    });


</script>


<link href="<?php echo $this->config->base_url(); ?>assets/css/datepicker.css" rel="stylesheet">

<h3 class="title"><?php echo $page_title; ?> <a href="#" class="btn btn-mini toggle_form"><?php echo $this->lang->line("show_hide"); ?></a></h3>

<div class="form">
    <p>Please customise the report below.</p>
    <?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=reports&view=sales", $attrib); ?>
    <div class="control-group">
        <label class="control-label" for="reference_no"><?php echo $this->lang->line("reference_no"); ?></label>
        <div class="controls"> <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="span4 tip" title="Filter Sales by Reference No" id="reference_no"');?>
        </div>
    </div>
    <!--<div class="control-group">
  <label class="control-label" for="name"><?php echo $this->lang->line("product_name"); ?></label>
  <div class="controls"> <?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : ""), 'class="span4" id="name"');?>
  </div>
</div>-->
    <div class="control-group">
        <label class="control-label" for="user"><?php echo $this->lang->line("created_by"); ?></label>
        <div class="controls"> <?php
            $us[""] = "";
            foreach($users as $user){
                $us[$user->id] = $user->first_name." ".$user->last_name;
            }
            echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="span4" id="user" data-placeholder="'.$this->lang->line("select")." ".$this->lang->line("user").'"');  ?> </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="customer"><?php echo $this->lang->line("customer"); ?></label>
        <div class="controls"> <?php
            $cu[""] = "";
            foreach($customers as $customer){
                $cu[$customer->id] = $customer->name;
            }
            echo form_dropdown('customer', $cu, (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="span4" id="customer" data-placeholder="'.$this->lang->line("select")." ".$this->lang->line("customer").'"');  ?> </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="biller"><?php echo $this->lang->line("biller"); ?></label>
        <div class="controls"> <?php
            $bl[""] = "";
            foreach($billers as $biller){
                $bl[$biller->id] = $biller->name;
            }
            echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'class="span4" id="biller" data-placeholder="'.$this->lang->line("select")." ".$this->lang->line("biller").'"');  ?> </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="warehouse"><?php echo $this->lang->line("warehouse"); ?></label>
        <div class="controls"> <?php
            //	   		$wh[""] = "";
            foreach($warehouses as $warehouse){
                $wh[$warehouse->id] = $warehouse->name;
            }
            echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'class="span4" id="warehouse" data-placeholder="'.$this->lang->line("select")." ".$this->lang->line("warehouse").'"');  ?> </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="start_date"><?php echo $this->lang->line("start_date"); ?></label>
        <div class="controls"> <?php echo form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="span4" id="start_date"');?> </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="end_date"><?php echo $this->lang->line("end_date"); ?></label>
        <div class="controls"> <?php echo form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="span4" id="end_date"');?> </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="end_date">Paid By</label>
        <div class="controls">
            <select name="paid_by" id="paid_by">
                <option value="">Select Payment Mode</option>
                <option value="cash" <?php if($_POST['paid_by']=='cash') echo "selected"; ?>>Cash</option>
                <option value="CC" <?php if($_POST['paid_by']=='CC') echo "selected"; ?>>Cards</option>
                <option value="CC_cash" <?php if($_POST['paid_by']=='CC_cash') echo "selected"; ?>>Card & Cash</option>
                <option value="Cheque" <?php if($_POST['paid_by']=='Cheque') echo "selected"; ?>><?php echo $this->lang->line("cheque"); ?></option>
            </select>
        </div>
    </div>
    <a href="#" onclick="MyWindow=window.open('index.php?module=sales&amp;view=view_invoice_print&amp;id=4', 'MyWindow','toolbar=0,location=0,directories=0,status=0,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=600'); return false;" title="" class="tip" data-original-title="Small Invoice">Click Here</a>
    <div class="control-group">
        <div class="controls"> <?php echo form_button('show', $this->lang->line("show"), ' id="show" class="btn btn-primary"');?> </div>
    </div>
    <?php echo form_close();?>

</div>
<div class="clearfix"></div>

<p>&nbsp;</p>
