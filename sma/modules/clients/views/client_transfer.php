<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<link href="<?php echo $this->config->base_url(); ?>assets/css/bootstrap-fileupload.css" rel="stylesheet">
<?php if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
} ?>

<h3 class="title"><?php echo $page_title; ?></h3>
<!--<p>--><?php //echo $this->lang->line("enter_info"); ?><!--</p>-->
<?php $attrib = array('class' => 'form-horizontal');
echo form_open_multipart("module=clients&view=client_transfer&name=$name", $attrib); ?>
<div class="control-group">
    <label class="control-label" for="types"><?php echo $this->lang->line("client_code"); ?></label>

    <div class="controls">
        <?php echo form_input('client_code', $client->client_code, 'class="span4" readonly="readonly" id="client_code" readonly="readonly" required="required" pattern="[a-zA-Z0-9_-]{2,12}" data-error="' . $this->lang->line("client_code") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_2") . '"'); ?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="types"><?php echo $this->lang->line("vendor_code"); ?></label>

    <div class="controls">
        <?php echo form_input('vendor_code', $client->vendor_code, 'class="span4" id="vendor_code" readonly="readonly" required="required" pattern="[a-zA-Z0-9_-]{2,12}" data-error="' . $this->lang->line("vendor_code") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_2") . '"'); ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="types"><?php echo $this->lang->line("building_code"); ?></label>

    <div class="controls">
        <?php echo form_input('building_code', $client->building_code, 'class="span4" id="building_code" readonly="readonly" required="required" pattern="[a-zA-Z0-9_-]{2,12}" data-error="' . $this->lang->line("building_code") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_2") . '"'); ?> </div>
</div>


<div class="control-group">
    <label class="control-label" for="types"><?php echo $this->lang->line("apartment_code"); ?></label>

    <div class="controls"> <?php
        $typeList[''] = "";
        foreach ($types as $obj) {
            $typeList[$obj->room_code] = $obj->room_code;
        }
        echo form_dropdown('apartment_code', $typeList, (isset($_POST['apartment_code']) ? $_POST['apartment_code'] : ""), 'required="required" class="span4" data-error="Apartment Code is required"');  ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="header_image">Upload Document</label>
    <div class="controls">
        <div class="fileupload fileupload-new" data-provides="fileupload">
    <span class="btn btn-file"><span class="fileupload-new"><?php echo $this->lang->line("select_image"); ?></span><span class="fileupload-exists"><?php echo $this->lang->line("chnage"); ?></span>
      <input type="file" name="userfile" id="userfile" required="required" data-error="Doc. is required" />
      </span> <span class="fileupload-preview"></span> <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a> </div>
        <span class="help-block"><?php echo $this->lang->line('new_logo_tip_client'); ?></span> </div>
</div>


<div class="control-group">
    <label class="control-label" for="location"><?php echo $this->lang->line("comment"); ?></label>

    <div class="controls">
        <?php echo form_textarea('comments', (isset($_POST['comments']) ? $_POST['comments'] : ''), 'required="required" class="span4" data-error="' . $this->lang->line("comment") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_10") . '" rows="5" cols="5" id="location" style="margin: 10px 0px 0px; height: 106px; width: 415px;"'); ?>
    </div>
</div>

<div class="control-group">
    <div
        class="controls"> <?php echo form_submit('submit', $this->lang->line("client_transfer"), 'class="btn btn-primary"'); ?> </div>
</div>
<?php echo form_close(); ?>
<div id="loading" style="display: none;">
    <div class="blackbg"></div>
    <div class="loader"></div>
</div>