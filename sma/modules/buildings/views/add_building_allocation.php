<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
//        $(".chosen-select").chosen();
        $('form').form();
    });
</script>
<?php if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
} ?>


<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>
<?php $attrib = array('class' => 'form-horizontal');
echo form_open("module=buildings&view=add_building_allocation", $attrib); ?>


<div class="control-group">
    <label class="control-label" for="buildings_name"><?php echo $this->lang->line("vendor_name"); ?></label>

    <div class="controls"> <?php
        $customerList[''] = "";
        foreach ($vendors as $vendor) {
            $customerList[$vendor->id] = $vendor->name;
        }
        echo form_dropdown('vendor_id', $customerList, (isset($_POST['vendor_id']) ? $_POST['vendor_id'] : ""), 'id="tip chzn-select span4"  class="chosen-select" required="required" data-error="' . $this->lang->line("vendor_name") . ' ' . $this->lang->line("is_required") . '"');  ?> </div>
</div>


<div class="control-group">
    <label class="control-label" for="buildings_name"><?php echo $this->lang->line("buildings_name"); ?></label>

    <div class="controls"> <?php
        $buildingList[''] = "";
        foreach ($buildings as $building) {
            $buildingList[$building->building_name] = $building->building_code;
        }
        echo form_dropdown('buildings_name', $buildingList, (isset($_POST['buildings_name']) ? $_POST['buildings_name'] : ""), 'id="tip chzn-select span4"  class="chosen-select" required="required" data-error="' . $this->lang->line("buildings_name") . ' ' . $this->lang->line("is_required") . '"');  ?> </div>
</div>



<div class="control-group">
    <div
        class="controls"> <?php echo form_submit('submit', $this->lang->line("new_building_allocation"), 'class="btn btn-primary"'); ?> </div>
</div>
<?php echo form_close(); ?>
