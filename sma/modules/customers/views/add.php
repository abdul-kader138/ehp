<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
$(function() {
	$('form').form();
    $( "#join_date" ).datepicker({
        format: "<?php echo JS_DATE; ?>",
        autoclose: true
    });

});

</script>

<link href="<?php echo $this->config->base_url(); ?>assets/css/datepicker.css" rel="stylesheet">
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>


	<h3 class="title"><?php echo $page_title; ?></h3>
	<p><?php echo $this->lang->line("enter_info"); ?></p>

   	<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=customers&view=add", $attrib);?>

<div class="control-group">
    <label class="control-label" for="code">Vendor Code</label>
    <div class="controls"> <?php echo form_input('code', $rnumber, 'class="span4"  id="code" pattern=".{2,55}" required="required" data-error="'.$this->lang->line("code").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>


<div class="control-group">
  <label class="control-label" for="name"><?php echo $this->lang->line("name"); ?></label>
  <div class="controls"> <?php echo form_input('name', (isset($_POST['name']) ? $_POST['name'] : ''), 'class="span4" id="name" pattern=".{2,55}" required="required" data-error="'.$this->lang->line("name").' '.$this->lang->line("is_required").'"');?>
  </div>
</div>

<div class="control-group">
    <label class="control-label" for="start_date">Join Date</label>
    <div class="controls"> <?php echo form_input('join_date', (isset($_POST['join_date']) ? $_POST['join_date'] : ""), 'class="span4" id="join_date"');?> </div>
</div>

<div class="control-group">
  <label class="control-label" for="address"><?php echo $this->lang->line("address"); ?></label>
  <div class="controls"> <?php echo form_input('address', (isset($_POST['address']) ? $_POST['address'] : ''), 'class="span4"  id="address" pattern=".{10,255}" required="required" data-error="'.$this->lang->line("address").' '.$this->lang->line("is_required").'"');?>
  </div>
</div>  

<div class="control-group">
  <label class="control-label" for="city"><?php echo $this->lang->line("city"); ?></label>
  <div class="controls"> <?php echo form_input('city', (isset($_POST['city']) ? $_POST['city'] : ''), 'class="span4" id="city"  pattern=".{2,55}" data-error="'.$this->lang->line("city").' '.$this->lang->line("is_required").'"');?>
  </div>
</div> 
<div class="control-group" >
  <label class="control-label" for="state"><?php echo $this->lang->line("state"); ?></label>
  <div class="controls"> <?php echo form_input('state', (isset($_POST['state']) ? $_POST['state'] : ''), 'class="span4"  id="state" pattern=".{2,55}" data-error="'.$this->lang->line("state").' '.$this->lang->line("is_required").'"');?>
  </div>
</div> 
<div class="control-group" >
  <label class="control-label" for="postal_code"><?php echo $this->lang->line("postal_code"); ?></label>
  <div class="controls"> <?php echo form_input('postal_code', (isset($_POST['postal_code']) ? $_POST['postal_code'] : ''), 'class="span4"  id="postal_code"pattern=".{4,8}" data-error="'.$this->lang->line("postal_code").' '.$this->lang->line("is_required").'"');?>
  </div>
</div>

<div class="control-group">
    <label class="control-label" for="email_address">Email</label>
    <div class="controls"> <input type="email" name="email" class="span4" data-error="<?php echo $this->lang->line("email_address").' '.$this->lang->line("is_required"); ?>" />
    </div>
</div>



<div class="control-group">
    <label class="control-label" for="phone"><?php echo $this->lang->line("phone"); ?></label>
    <div class="controls"> <input type="tel" name="phone" class="span4" pattern="[0-9]{7,15}"  data-error="<?php echo $this->lang->line("phone").' '.$this->lang->line("is_required"); ?>" />
    </div>
</div>


<div class="control-group">
  <div class="controls"> <?php echo form_submit('submit', $this->lang->line("add_vendor"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?> 
   