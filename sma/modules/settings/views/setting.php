<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('form').form();
    });
</script>
<style type="text/css">
    .span11, .chzn-container {
        width: 91.453% !important;
    }
</style>
<?php if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
} ?>
<?php if ($success_message) {
    echo "<div class=\"alert alert-success\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $success_message . "</div>";
} ?>

<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line('update_info'); ?></p>
<?php $attrib = array('class' => 'form-horizontal');
echo form_open("module=settings&view=system_setting", $attrib); ?>
<div class="row-fluid">
    <div class="span5">
        <div class="control-group">
            <label class="control-label" for="site_name"><?php echo $this->lang->line("site_name"); ?></label>

            <div
                class="controls"> <?php echo form_input('site_name', $settings->site_name, 'class="span11 tip" id="site_name" title="' . $this->lang->line("site_name_tip") . '" required="required" data-error="' . $this->lang->line("site_name") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="language"><?php echo $this->lang->line("language"); ?></label>

            <div class="controls">
                <?php
                /*
                  'chinese' => '普通话',
                 */
                $lang = array(
                    'arabic' => 'العربية',
                    'english' => 'English',
                    'french' => 'Le Français',
                    'indonesian ' => 'Indonesian',
                    'bportuguese' => 'Português Do Brasil',
                    'eportuguese' => 'Português Europeu',
                    'romanian' => 'Română',
                    'spanish' => 'Español'
                );
                echo form_dropdown('language', $lang, $settings->language, 'class="span11 tip chzn-select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("language") . '" title="' . $this->lang->line("language_tip") . '" required="required" data-error="' . $this->lang->line("language") . ' ' . $this->lang->line("is_required") . '"');
                ?>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="currency_code"><?php echo $this->lang->line("currency_code"); ?></label>

            <div
                class="controls"> <?php echo form_input('currency_prefix', $settings->currency_prefix, 'class="span11 tip" id="currency_code" title="' . $this->lang->line("currency_code_tip") . '" required="required" data-error="' . $this->lang->line("currency_code") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="date_format"><?php echo $this->lang->line("date_format"); ?></label>

            <div class="controls">
                <?php
                foreach ($date_formats as $date_format) {
                    $dt[$date_format->id] = $date_format->js;
                }
                echo form_dropdown('date_format', $dt, $settings->dateformat, 'class="span11 tip chzn-select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("date_format") . '" required="required" data-error="' . $this->lang->line("date_format") . ' ' . $this->lang->line("is_required") . '"');
                ?>
            </div>
        </div>

    </div>
    <div class="span5 offset1">
        <div class="control-group">
            <label class="control-label" for="tax_rate2"><?php echo $this->lang->line("rows_per_page"); ?></label>

            <div class="controls">
                <?php
                $options = array(
                    '10' => '10',
                    '25' => '25',
                    '50' => '50',
                    '100' => '100',
                    '-1' => 'All (not recommended)');
                echo form_dropdown('rows_per_page', $options, $settings->rows_per_page, 'class="span11 tip chzn-select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("rows_per_page") . '" title="' . $this->lang->line("rows_per_page_tip") . '" required="required" data-error="' . $this->lang->line("rows_per_page") . ' ' . $this->lang->line("is_required") . '"');
                ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="total_rows"><?php echo $this->lang->line("total_rows"); ?></label>

            <div class="controls">
                <!--<input type="number" name="total_rows" value="<?php echo $settings->total_rows; ?>" min="10" max="99" id="inputComputer" class="span11 tip" id="total_rows" title="<?php echo $this->lang->line("total_rows_tip"); ?>" data-error="<?php echo $this->lang->line("total_rows") . ' ' . $this->lang->line("is_required"); ?>">-->
                <?php echo form_input('total_rows', $settings->total_rows, 'class="span11 tip" id="total_rows" title="' . $this->lang->line("total_rows_tip") . '" required="required" data-error="' . $this->lang->line("total_rows") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
        </div>

        <div class="control-group">
            <label class="control-label" for="restrict_calendar"><?php echo $this->lang->line("calendar"); ?></label>

            <div class="controls">
                <?php
                $opt_cal = array(1 => $this->lang->line('private'), 0 => $this->lang->line('shared'));
                echo form_dropdown('restrict_calendar', $opt_cal, $settings->restrict_calendar, 'class="span11 tip chzn-select" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("restrict_calendar") . '" title="' . $this->lang->line("restrict_calendar_tip") . '" required="required" data-error="' . $this->lang->line("restrict_calendar") . ' ' . $this->lang->line("is_required") . '"');
                ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="types">Intake Client Type</label>

            <div class="controls"> <?php
                $typeList[''] = "";
                foreach ($types as $obj) {
                    $typeList[$obj->type_code] = $obj->type_name;
                }
                echo form_dropdown('types', $typeList, $settings->client_type, ' required="required" id="types" class="span11 tip chzn-select" data-error="Type is required"');  ?> </div>
        </div>

    </div>
</div>

<div class="control-group">
    <div
        class="controls"> <?php echo form_submit('submit', $this->lang->line("update_settings"), 'class="btn btn-primary"'); ?> </div>
</div>
<?php echo form_close(); ?> 