<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('form').form();
    });
</script>
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>


<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("update_info"); ?></p>
<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=level&view=edit&name=".$name, $attrib);?>
<div class="control-group">
    <label class="control-label" for="code"><?php echo $this->lang->line("level_code"); ?></label>
    <div class="controls"> <?php echo form_input('level_code', $level->level_code, 'class="span4" readonly="readonly" id="code" required="required" pattern="[a-zA-Z0-9_-()]{2,12}" data-error="'.$this->lang->line("code").' '.$this->lang->line("is_required").' '.$this->lang->line("min_2").'"');?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("level_name"); ?></label>
    <div class="controls"> <?php echo form_input('level_name', $level->level_name, 'class="span4" id="name" required="required" data-error="'.$this->lang->line("name").' '.$this->lang->line("is_required").'"');?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="room_name"><?php echo $this->lang->line("room_name"); ?></label>

    <div class="controls">  <?php
        $roomList[''] = "";
        foreach ($rooms as $room) {
            $roomList[$room->room_code] = $room->room_name;
        }
        echo form_dropdown('room_name', $roomList, $level->room_name, 'class="tip chzn-select span4 room_name" id="room_name" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("room_name") . '"  required="required" data-error="' . $this->lang->line("room_name") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
</div>


<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("edit_level"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?> 