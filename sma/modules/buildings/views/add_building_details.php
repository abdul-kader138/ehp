<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
//        $(".chosen-select").chosen();
        $('form').form();
    });
</script>
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>

<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>
<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=buildings&view=add_building_details", $attrib);?>


<div class="control-group">
    <label class="control-label" for="buildings_name"><?php echo $this->lang->line("buildings_name"); ?></label>

    <div class="controls"> <?php
        $buildingList[''] = "";
        foreach ($buildings as $building) {
            $buildingList[$building->building_code] = $building->building_name;
        }
        echo form_dropdown('buildings_name', $buildingList, (isset($_POST['buildings_name']) ? $_POST['buildings_name'] : ""), ' class="select_search" required="required" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("buildings_name") . '" data-error="' . $this->lang->line("buildings_name") . ' ' . $this->lang->line("is_required") . '"');  ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="level_name"><?php echo $this->lang->line("level_name"); ?></label>

    <div class="controls">  <?php
        $levelList[''] = "";
        foreach ($levels as $level) {
            $levelList[$level->level_code] = $level->level_name;
        }
        echo form_dropdown('level_name', $levelList, (isset($_POST['level_name']) ? $_POST['level_name'] : ""), 'class="select_search" id="level_name" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("level_name") . '"  required="required" data-error="' . $this->lang->line("level_name") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
</div>


<div class="control-group">
    <div
        class="controls"> <?php echo form_submit('submit', $this->lang->line("add_level_buildings"), 'class="btn btn-primary"'); ?> </div>
</div>
<?php echo form_close(); ?>
