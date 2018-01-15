<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('form').form();
    });
</script>
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>

<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>
<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=inspection&view=edit_deficiency_concern&name=".$name, $attrib);?>
<div class="control-group">
    <label class="control-label" for="code"><?php echo $this->lang->line("details_code"); ?></label>
    <div class="controls"> <?php echo form_input('concern_code', $deficiency->concern_code, 'class="span4" id="code" readonly="readonly" required="required" pattern="[a-zA-Z0-9_-()]{2,12}" data-error="'.$this->lang->line("concern_code").' '.$this->lang->line("is_required").' '.$this->lang->line("min_2").'"');?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("concern_name"); ?></label>
    <div class="controls"> <?php echo form_input('concern_name', $deficiency->concern_name, 'class="span4" id="name"  required="required" data-error="'.$this->lang->line("concern_name").' '.$this->lang->line("is_required").'"');?> </div>
</div>


<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("edit_deficiency_concern"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?>
