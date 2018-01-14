<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('form').form();
    });
</script>
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>

<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>
<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=inspection&view=edit_deficiency_category&name=".$name, $attrib);?>
<div class="control-group">
    <label class="control-label" for="code"><?php echo $this->lang->line("category_code"); ?></label>
    <div class="controls"> <?php echo form_input('category_code', $deficiency->category_code, 'class="span4" id="code" readonly="readonly" required="required" pattern="[a-zA-Z0-9_-()]{2,12}" data-error="'.$this->lang->line("category_code").' '.$this->lang->line("is_required").' '.$this->lang->line("min_2").'"');?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("category_name"); ?></label>
    <div class="controls"> <?php echo form_input('category_name', $deficiency->category_name, 'class="span4" id="name"  required="required" data-error="'.$this->lang->line("category_name").' '.$this->lang->line("is_required").'"');?> </div>
</div>


<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("add_deficiency_category"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?>
