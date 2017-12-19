<style type="text/css">
    .loader {
        background-color: #CF4342;
        color: white;
        top: 30%;
        left: 50%;
        margin-left: -50px;
        position: fixed;
        padding: 3px;
        width: 100px;
        height: 100px;
        background: url('<?php echo $this->config->base_url(); ?>assets/img/wheel.gif') no-repeat center;
    }

    .blackbg {
        z-index: 5000;
        background-color: #666;
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
        filter: alpha(opacity=20);
        opacity: 0.2;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        position: absolute;
    }
</style>
<link href="<?php echo $this->config->base_url(); ?>assets/css/bootstrap-fileupload.css" rel="stylesheet">
<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>


<?php if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
} ?>


<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_product_info"); ?></p>

<?php $attrib = array('class' => 'form-horizontal');
echo form_open_multipart("module=buildings&view=add_building_details", $attrib); ?>


<div class="control-group">
    <label class="control-label" for="building"><?php echo $this->lang->line("buildings_name"); ?></label>

    <div class="controls">  <?php
        $buildingList[''] = "";
        foreach ($buildings as $building) {
            $buildingList[$building->building_name] = $building->building_code;
        }
        echo form_dropdown('buildings_name', $buildingList, (isset($_POST['buildings_name']) ? $_POST['buildings_name'] : ""), 'class="tip chzn-select span4" id="buildings_name" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("buildings_name") . '"  required="required" data-error="' . $this->lang->line("buildings_name") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="level_name"><?php echo $this->lang->line("level_name"); ?></label>

    <div class="controls">  <?php
        $levelList[''] = "";
        foreach ($levels as $level) {
            $levelList[$level->level_name] = $level->level_code;
        }
        echo form_dropdown('level_name', $levelList, (isset($_POST['level_name']) ? $_POST['level_name'] : ""), 'class="tip chzn-select span4" id="level_name" data-placeholder="' . $this->lang->line("select") . " " . $this->lang->line("level_name") . '"  required="required" data-error="' . $this->lang->line("level_name") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
</div>


<div class="control-group">
    <label class="control-label" for="size"><?php echo $this->lang->line("total_room_qty"); ?></label>

    <div
        class="controls"> <?php echo form_input('total_room_qty', (isset($_POST['total_room_qty']) ? $_POST['total_room_qty'] : ""), 'class="span4 tip" id="total_room_qty" title="' . $this->lang->line("total_room_qty") . '"'); ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="size"><?php echo $this->lang->line("total_bed_qty"); ?></label>

    <div
        class="controls"> <?php echo form_input('total_bed_qty', (isset($_POST['total_bed_qty']) ? $_POST['total_bed_qty'] : ""), 'class="span4 tip" id="total_bed_qty" title="' . $this->lang->line("total_bed_qty") . '"'); ?> </div>
</div>

<div class="control-group">
    <div
        class="controls"> <?php echo form_submit('submit', $this->lang->line("add_level_buildings"), 'class="btn btn-primary"'); ?> </div>
</div>
<?php echo form_close(); ?>
