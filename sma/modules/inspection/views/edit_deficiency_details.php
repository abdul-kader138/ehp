<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('form').form();
    });
</script>
<?php if($message) { echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>

<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>
<?php $attrib = array('class' => 'form-horizontal'); echo form_open("module=inspection&view=edit_deficiency_details&name=".$name, $attrib);?>
<div class="control-group">
    <label class="control-label" for="code"><?php echo $this->lang->line("details_code"); ?></label>
    <div class="controls"> <?php echo form_input('details_code', $details->details_code, 'class="span4" readonly="readonly" id="details_code" required="required" pattern="[a-zA-Z0-9_-()]{2,12}" data-error="'.$this->lang->line("details_code").' '.$this->lang->line("is_required").' '.$this->lang->line("min_2").'"');?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="category_name">Category Name</label>

    <div class="controls"> <?php
        //        $roomList[''] = "";
        foreach ($categories as $category) {
            $categoryList[$category->category_code] = $category->category_name;
        }
        echo form_dropdown('category_code', $categoryList,$details->category_code, 'class="select_search span4" data-error="' . $this->lang->line("category_name") . ' ' . $this->lang->line("is_required") . '"');  ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="category_name">Concern Name</label>

    <div class="controls"> <?php
        //        $roomList[''] = "";

        foreach ($concerns as $concern) {
            $concernList[$concern->concern_code] = $concern->concern_name;
        }
        echo form_dropdown('concern_code', $concernList, $details->concern_code, 'class="select_search span4" data-error="' . $this->lang->line("concern_name") . ' ' . $this->lang->line("is_required") . '"');  ?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("weight"); ?></label>
    <div class="controls"> <?php echo form_input('weight', $details->weight, 'class="span4" id="name"  required="required" type="numeric" data-error="'.$this->lang->line("weight").' '.$this->lang->line("is_required").'"');?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("details_name"); ?></label>
    <div class="controls">
        <?php echo form_textarea('details_name',$details->details_name,'class="input-block-level" required="required" data-error="' . $this->lang->line("details_name") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_10") . '" rows="5" cols="5" id="location" style="margin: 10px 0px 0px; height: 106px; width: 415px;"'); ?>
    </div>
</div>

<div class="control-group">
    <label class="control-label" for="name"><?php echo $this->lang->line("details_comment"); ?></label>
    <div class="controls">
        <?php echo form_textarea('details_comment', $details->details_comment, 'class="input-block-level" data-error="' . $this->lang->line("details_comment") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_10") . '" rows="5" cols="5" id="location" style="margin: 10px 0px 0px; height: 106px; width: 415px;"'); ?>
    </div>
</div>
<div class="control-group">
    <div class="controls"> <?php echo form_submit('submit', $this->lang->line("edit_deficiency_details"), 'class="btn btn-primary"');?> </div>
</div>
<?php echo form_close();?>
