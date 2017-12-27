<link href="<?php echo $this->config->base_url(); ?>assets/css/bootstrap-fileupload.css" rel="stylesheet">
<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>


<?php if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
} ?>


<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>

<?php $attrib = array('class' => 'form-horizontal');
echo form_open_multipart("module=buildings&view=edit_building_details&id=".$id, $attrib); ?>


<div class="control-group">
    <label class="control-label" for="building"><?php echo $this->lang->line("buildings_name"); ?></label>

    <div class="controls">  <?php
        $buildingList[''] = "";
        foreach ($buildings as $building) {
            $buildingList[$building->building_code] = $building->building_name;
        }
        echo form_dropdown('buildings_name', $buildingList, $building_details->building_name, 'class="tip chzn-select span4" id="buildings_name" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("buildings_name") . '"  required="required" data-error="' . $this->lang->line("buildings_name") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="level_name"><?php echo $this->lang->line("level_name"); ?></label>

    <div class="controls">  <?php
        $levelList[''] = "";
        foreach ($levels as $level) {
            $levelList[$level->level_code] = $level->level_name;
        }
        echo form_dropdown('level_name', $levelList, $building_details->level_name, 'class="tip chzn-select span4" id="level_name" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("level_name") . '"  required="required" data-error="' . $this->lang->line("level_name") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
</div>



<div class="control-group">
    <div
        class="controls"> <?php echo form_submit('submit', $this->lang->line("edit_level_buildings"), 'class="btn btn-primary"'); ?> </div>
</div>
<?php echo form_close(); ?>
