<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('form').form();
    });
</script>
<?php if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
} ?>

<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>
<?php $attrib = array('class' => 'form-horizontal');
echo form_open("module=buildings&view=add", $attrib); ?>
<div class="control-group">
    <label class="control-label" for="code"><?php echo $this->lang->line("buildings_code"); ?></label>

    <div
        class="controls"> <?php echo form_input('code', $rnumber, 'class="span4" id="code" required="required" pattern="[a-zA-Z0-9_-()]{2,12}" data-error="' . $this->lang->line("code") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_2") . '"'); ?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("buildings_name"); ?></label>

    <div
        class="controls"> <?php echo form_input($name, '', 'class="span4" id="name" required="required" data-error="' . $this->lang->line("name") . ' ' . $this->lang->line("is_required") . '" data-error="' . $this->lang->line("name") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_2") . '"'); ?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="cf6">Has Medical Support</label>

    <div class="controls">
        Yes &nbsp;<input type="radio" required="required" name="hasMedicalSupport" value="1"/>&nbsp; No:&nbsp; <input
            type="radio" checked="checked"
            name="hasMedicalSupport"
            value="0"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="cf6">Has Handicap Access</label>

    <div class="controls">
        Yes &nbsp;<input required="required" type="radio" name="hasHandicapAccess" value="1"/>&nbsp; No:&nbsp; <input
            type="radio"
            checked="checked" name="hasHandicapAccess"
            value="0"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="cf6">Is Smoke Free Zone</label>

    <div class="controls">
        Yes &nbsp;<input type="radio" required="required" name="isSmokeFreeZone" value="1"/>&nbsp; No:&nbsp; <input
            type="radio" checked="checked"
            name="isSmokeFreeZone"
            value="0"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="cf6">Has Elevator Support</label>

    <div class="controls">
        Yes &nbsp;<input type="radio" required="required" name="hasElevatorSupport" value="1"/>&nbsp; No:&nbsp; <input
            type="radio" checked="checked"
            name="hasElevatorSupport"
            value="0"/>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="location"><?php echo $this->lang->line("location_buildings"); ?></label>

    <div class="controls">
        <?php echo form_textarea('location', (isset($_POST['location']) ? $_POST['location'] : ''), 'class="input-block-level" required="required" data-error="' . $this->lang->line("location_buildings") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_10") . '" rows="5" cols="5" id="location" style="margin: 10px 0px 0px; height: 106px; width: 415px;"'); ?>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="location"><?php echo $this->lang->line("comment"); ?></label>

    <div class="controls">
        <?php echo form_textarea('comments', (isset($_POST['comments']) ? $_POST['comments'] : ''), 'class="input-block-level" data-error="' . $this->lang->line("comment") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_10") . '" rows="5" cols="5" id="location" style="margin: 10px 0px 0px; height: 106px; width: 415px;"'); ?>
    </div>
</div>

<div class="control-group">
    <div
        class="controls"> <?php echo form_submit('submit', $this->lang->line("add_buildings"), 'class="btn btn-primary"'); ?> </div>
</div>
<?php echo form_close(); ?>
