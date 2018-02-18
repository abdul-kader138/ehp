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

<?php $attrib = array('class' => 'form-horizontal'); echo form_open_multipart("module=clients&view=edit&name=$name", $attrib);?>

<div class="control-group">
    <label class="control-label" for="code">Code</label>
    <div class="controls"> <?php echo form_input('code', $client->code, 'class="span4" readonly="readonly"  id="code" pattern=".{2,55}" required="required" data-error="'.$this->lang->line("code").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>


<div class="control-group">
    <label class="control-label" for="first_name"><?php echo $this->lang->line("first_name"); ?></label>
    <div class="controls"> <?php echo form_input('first_name', $client->first_name, 'class="span4" id="name" pattern=".{2,55}" required="required" data-error="'.$this->lang->line("first_name").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="last_name"><?php echo $this->lang->line("last_name"); ?></label>
    <div class="controls"> <?php echo form_input('last_name',  $client->last_name, 'class="span4" id="name" pattern=".{2,55}" required="required" data-error="'.$this->lang->line("last_name").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="email_address">Email</label>
    <div class="controls"> <?php echo form_input('email', $client->email, 'class="span4" id="email" data-error="'.$this->lang->line("email_address").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>


<div class="control-group">
    <label class="control-label" for="phone"><?php echo $this->lang->line("phone"); ?></label>
    <div class="controls"> <?php echo form_input('phone', $client->phone, 'class="span4" id="phone" type="tel" pattern="[0-9]{7,15}" data-error="'.$this->lang->line("phone").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>



<div class="control-group">
    <label class="control-label" for="email_address">SSN</label>
    <div class="controls"> <?php echo form_input('ssn', $client->ssn, 'class="span4" id="ssn"  data-error="'.$this->lang->line("ssn").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="email_address">Medicaid No</label>
    <div class="controls"> <?php echo form_input('medicaid_no', $client->medicaid_no, 'class="span4" id="medicaid_no"  data-error="'.$this->lang->line("ssn").' '.$this->lang->line("is_required").'"');?>
    </div>
</div>


<div class="control-group">
    <label class="control-label" for="types">Client Type</label>

    <div class="controls"> <?php
        $typeList[''] = "";
        foreach ($types as $obj) {
            $typeList[$obj->type_code] = $obj->type_name;
        }
        echo form_dropdown('types', $typeList, $client->client_type, '  required="required" class="span4" data-error="Type is required"');  ?> </div>
</div>

<div class="control-group">
    <?php
    $originalDate = $client->date_of_birth;
    if($originalDate !=='0000-00-00')$newDate = date("d-m-Y", strtotime($originalDate));
    else {
        $newDate="";
    }
    ?>
    <label class="control-label" for="start_date">DOB</label>
    <div class="controls"> <?php echo form_input('date_of_birth', $newDate, 'class="span4" required="required" id="date_of_birth"');?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="location"><?php echo $this->lang->line("comment"); ?></label>

    <div class="controls">
        <?php echo form_textarea('comments', (isset($_POST['comments']) ? $_POST['comments'] : html_entity_decode($client->comment)), 'class="input-block-level" data-error="' . $this->lang->line("comment") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_10") . '" rows="5" cols="5" id="location" style="margin: 10px 0px 0px; height: 106px; width: 415px;"'); ?>
    </div>
</div>


<div class="control-group">
    <label class="control-label" for="header_image">Upload Document</label>
    <div class="controls">
        <div class="fileupload fileupload-new" data-provides="fileupload">
    <span class="btn btn-file"><span class="fileupload-new"><?php echo $this->lang->line("select_image"); ?></span><span class="fileupload-exists"><?php echo $this->lang->line("chnage"); ?></span>
      <input type="file" name="userfile" id="userfile" data-error="Doc. is required" />
      </span> <span class="fileupload-preview"></span> <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a> </div>
        <span class="help-block"><?php echo $this->lang->line('new_logo_tip_client'); ?></span> </div>
</div>


<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("edit_client"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?>
