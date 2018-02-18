<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('form').form();
    });
</script>
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>


<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("update_info"); ?></p>
<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=buildings&view=edit&name=".$name, $attrib);?>
<div class="control-group">
    <label class="control-label" for="code"><?php echo $this->lang->line("buildings_code"); ?></label>
    <div class="controls"> <?php echo form_input('buildings_code', $buildings->building_code, 'class="span4" readonly="readonly" id="buildings_code" required="required" pattern="[a-zA-Z0-9_-()]{2,12}" data-error="'.$this->lang->line("code").' '.$this->lang->line("is_required").' '.$this->lang->line("min_2").'"');?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("buildings_name"); ?></label>
    <div class="controls"> <?php echo form_input('buildings_name', $buildings->building_name, 'class="span4" id="buildings_name" required="required" data-error="'.$this->lang->line("name").' '.$this->lang->line("is_required").'"');?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="cf6">Has Medical Support</label>

    <div class="controls">
        Yes &nbsp;<input type="radio" name="hasMedicalSupport" value="1" <?php if($buildings->hasMedicalSupport==1){ echo "checked"; } ?> />&nbsp; No:&nbsp; <input type="radio" name="hasMedicalSupport" value="0" <?php if($buildings->hasMedicalSupport==0){ echo "checked"; } ?> />

    </div>

  </div>

<div class="control-group">
    <label class="control-label" for="cf6">Has Handicap Access</label>

    <div class="controls">
        Yes &nbsp;<input type="radio" name="hasHandicapAccess" value="1" <?php if($buildings->hasHandicapAccess==1){ echo "checked"; } ?> />&nbsp; No:&nbsp; <input type="radio" name="hasHandicapAccess" value="0" <?php if($buildings->hasHandicapAccess==0){ echo "checked"; } ?> />

    </div>
</div>

<div class="control-group">
    <label class="control-label" for="cf6">Is Smoke Free Zone</label>

    <div class="controls">
        Yes &nbsp;<input type="radio" name="isSmokeFreeZone" value="1" <?php if($buildings->isSmokeFreeZone==1){ echo "checked"; } ?> />&nbsp; No:&nbsp; <input type="radio" name="isSmokeFreeZone" value="0" <?php if($buildings->isSmokeFreeZone==0){ echo "checked"; } ?> />

    </div>
</div>

<div class="control-group">
    <label class="control-label" for="cf6">Has Elevator Support</label>


    <div class="controls">
        Yes &nbsp;<input type="radio" name="hasElevatorSupport" value="1" <?php if($buildings->hasElevatorSupport==1){ echo "checked"; } ?> />&nbsp; No:&nbsp; <input type="radio" name="hasElevatorSupport" value="0" <?php if($buildings->hasElevatorSupport==0){ echo "checked"; } ?> />

    </div>

</div>

<div class="control-group">
    <label class="control-label" for="location"><?php echo $this->lang->line("location_buildings"); ?></label>
    <div class="controls">
        <?php echo form_textarea('location', (isset($_POST['location']) ? $_POST['location'] : html_entity_decode($buildings->location)), 'class="input-block-level" required="required" rows="5" cols="5" id="location" style="margin: 10px 0px 0px; height: 106px; width: 415px; " data-error="' . $this->lang->line("location_buildings") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_10") . '" rows="5" cols="5" id="location" style="margin: 10px 0px 0px; height: 106px; width: 415px;"');?>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="location"><?php echo $this->lang->line("comment"); ?></label>

    <div class="controls">
        <?php echo form_textarea('comments', (isset($_POST['comments']) ? $_POST['comments'] : html_entity_decode($buildings->comment)), 'class="input-block-level" data-error="' . $this->lang->line("comment") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_10") . '" rows="5" cols="5" id="location" style="margin: 10px 0px 0px; height: 106px; width: 415px;"'); ?>
    </div>
</div>

<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("edit_buildings"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?> 