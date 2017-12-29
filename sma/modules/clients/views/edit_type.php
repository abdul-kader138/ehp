<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('form').form();
    });
</script>
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>


<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("update_info"); ?></p>
<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=clients&view=edit_type&name=".$name, $attrib);?>
<div class="control-group">
    <label class="control-label" for="code"><?php echo $this->lang->line("type_code"); ?></label>
    <div class="controls"> <?php echo form_input('type_code', $types->type_code, 'class="span4" readonly="readonly" id="type_code" required="required" pattern="[a-zA-Z0-9_-]{2,12}" data-error="'.$this->lang->line("type_code").' '.$this->lang->line("is_required").' '.$this->lang->line("min_2").'"');?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("type_name"); ?></label>
    <div class="controls"> <?php echo form_input('type_name', $types->type_name, 'class="span4" id="type_name" required="required" data-error="'.$this->lang->line("type_name").' '.$this->lang->line("is_required").'"');?> </div>
</div>


<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("edit_type"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?> 