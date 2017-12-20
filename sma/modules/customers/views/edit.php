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

<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=customers&view=edit&id=".$id, $attrib);?>

<div class="control-group">
    <label class="control-label" for="code">Code</label>
    <div class="controls"> <?php echo form_input('code', $customer->code, 'class="span4" id="code" readonly="readonly" pattern=".{2,55}" required="required" data-error="'.$this->lang->line("code").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>


<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("name"); ?></label>
    <div class="controls"> <?php echo form_input('name', $customer->name, 'class="span4" id="name" pattern=".{2,55}" required="required" data-error="'.$this->lang->line("name").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>

<div class="control-group">
    <?php
    $originalDate = $customer->date_of_join;
    $newDate = date("d-m-Y", strtotime($originalDate));?>
    <label class="control-label" for="start_date">Join Date</label>
    <div class="controls"> <?php echo form_input('join_date', $newDate, 'class="span4" id="join_date"');?> </div>
</div>


<div class="control-group">
    <label class="control-label" for="email_address">Email</label>
    <div class="controls"> <?php echo form_input('email', $customer->email, 'class="span4" id="email" required="required" data-error="'.$this->lang->line("email_address").' '.$this->lang->line("is_required").'"');?>
    </div>
   </div>

<div class="control-group">
    <label class="control-label" for="cf4">NID</label>
    <div class="controls"> <?php echo form_input('nid', $customer->nid, 'class="span4" id="nid" required="required" data-error="NID is required"');?>
    </div>
   </div>

<div class="control-group">
    <label class="control-label" for="phone"><?php echo $this->lang->line("phone"); ?></label>
    <div class="controls"> <?php echo form_input('phone', $customer->phone, 'class="span4" id="nid" type="tel" pattern="[0-9]{7,15}" required="required" data-error="'.$this->lang->line("phone").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="address"><?php echo $this->lang->line("address"); ?></label>
    <div class="controls"> <?php echo form_input('address',$customer->address, 'class="span4" required="required" id="address" pattern=".{2,255}" required="required" data-error="'.$this->lang->line("address").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="city"><?php echo $this->lang->line("city"); ?></label>
    <div class="controls"> <?php echo form_input('city', $customer->city,  'class="span4" id="city" required="required" pattern=".{2,55}" data-error="'.$this->lang->line("city").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>
<div class="control-group" >
    <label class="control-label" for="state"><?php echo $this->lang->line("state"); ?></label>
    <div class="controls"> <?php echo form_input('state',$customer->state, 'class="span4" required="required" id="state" pattern=".{2,55}" data-error="'.$this->lang->line("state").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>
<div class="control-group" >
    <label class="control-label" for="postal_code"><?php echo $this->lang->line("postal_code"); ?></label>
    <div class="controls"> <?php echo form_input('postal_code', $customer->postal_code, 'class="span4" required="required" id="postal_code"pattern=".{4,8}" data-error="'.$this->lang->line("postal_code").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>
<div class="control-group" >
    <label class="control-label" for="country"><?php echo $this->lang->line("country"); ?></label>
    <div class="controls"> <?php echo form_input('country', $customer->country, 'class="span4" id="country" required="required" pattern=".{2,55}" data-error="'.$this->lang->line("country").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>


<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("edit_vendor"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?>
