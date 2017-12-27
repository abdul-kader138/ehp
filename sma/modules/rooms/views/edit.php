<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('form').form();
    });
</script>
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>


<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("update_info"); ?></p>
<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=rooms&view=edit&name=".$name, $attrib);?>
<div class="control-group">
    <label class="control-label" for="code"><?php echo $this->lang->line("room_code"); ?></label>
    <div class="controls"> <?php echo form_input('room_code', $room->room_code, 'class="span4" readonly="readonly" id="code" required="required" pattern="[a-zA-Z0-9_-]{2,12}" data-error="'.$this->lang->line("room_code").' '.$this->lang->line("is_required").' '.$this->lang->line("min_2").'"');?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("room_name"); ?></label>
    <div class="controls"> <?php echo form_input('room_name', $room->room_name, 'class="span4" id="name" required="required" data-error="'.$this->lang->line("room_name").' '.$this->lang->line("is_required").'"');?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("total_bed_qty"); ?></label>
    <div class="controls"> <?php echo form_input('total_bed_qty', $room->total_bed_qty, 'class="span4" id="name" required="required" data-error="'.$this->lang->line("total_bed_qty").' '.$this->lang->line("is_required").'"');?> </div>
</div>
<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("edit_room"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?> 