<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('form').form();
        $( "#move_in_date" ).datepicker({
            format: "<?php echo JS_DATE; ?>",
            autoclose: true
        });

        $('#vendor_code').change(function () {
                var v = $(this).find(":selected").val();
                $('#loading').show();
                $.ajax({
                    type: "get",
                    async: false,
                    url: "index.php?module=clients&view=getBuildings",
                    data: {
                <?php echo $this->security->get_csrf_token_name(); ?>:
                "<?php echo $this->security->get_csrf_hash() ?>", vendor_id:v},
            dataType:"html",
            success:function (data) {
            if (data != "") {
                $('#building_code').empty();
                $('#building_code').html(data);
            } else {
                $('#building_code').empty();
                var default_data = '<select name="building_code" class="select_search span4" id="building_code" data-placeholder="Select Building Code"></select>';
                $('#building_code').html(default_data);
            }
        },
        error: function () {
            bootbox.alert('<?php echo $this->lang->line('ajax_error'); ?>');
            $('#loading').hide();
        }

    });

    $('#loading').hide();
    });


    $('#building_code').change(function () {
            var c = $(this).find(":selected").val();
            console.log(c);
            var v = $("#vendor_code").val();
            $('#loading').show();
            $.ajax({
                type: "get",
                async: false,
                url: "index.php?module=clients&view=getApartments",
                data: {
            <?php echo $this->security->get_csrf_token_name(); ?>:
            "<?php echo $this->security->get_csrf_hash() ?>", vendor_id:v,building_code:c},
        dataType:"html",
        success:function (data) {
        if (data != "") {
            $('#apartment_code').empty();
            $('#apartment_code').html(data);
        } else {
            $('#apartment_code').empty();
            var default_data = '<select name="apartment_code" class="select_search span4" id="apartment_code" data-placeholder="Select Apartment Code"></select>';
            $('#apartment_code').html(default_data);
        }
    },
    error: function () {
        bootbox.alert('<?php echo $this->lang->line('ajax_error'); ?>');
        $('#loading').hide();
    }

    });

    $('#loading').hide();
    });
    });
</script>
<?php if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
} ?>

<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>
<?php $attrib = array('class' => 'form-horizontal');
echo form_open("module=clients&view=client_intake", $attrib); ?>
<div class="control-group">
    <label class="control-label" for="code"><?php echo $this->lang->line("code"); ?></label>

    <div
        class="controls"> <?php echo form_input('code', $rnumber, 'class="span4" id="code" readonly="readonly" required="required" pattern="[a-zA-Z0-9_-]{2,12}" data-error="' . $this->lang->line("code") . ' ' . $this->lang->line("is_required") . ' ' . $this->lang->line("min_2") . '"'); ?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="types"><?php echo $this->lang->line("client_name"); ?></label>

    <div class="controls"> <?php
        $clientList[''] = "";
        foreach ($clients as $client) {
            $clientList[$client->code] = $client->first_name . " " . $client->Last_name;
        }
        echo form_dropdown('client_code', $clientList, (isset($_POST['client_code']) ? $_POST['client_code'] : ""), ' class="select_search span4" required="required"  data-error="' . $this->lang->line("client_code") . ' ' . $this->lang->line("is_required") . '"');  ?> </div>
</div>
<div class="control-group">
    <label class="control-label" for="types"><?php echo $this->lang->line("vendor_name"); ?></label>

    <div class="controls"> <?php
        $vendorList[''] = "";
        foreach ($vendors as $vendor) {
            $vendorList[$vendor->code] = $vendor->name;
        }
        echo form_dropdown('vendor_code', $vendorList, (isset($_POST['vendor_code']) ? $_POST['vendor_code'] : ""), ' class="select_search span4" id="vendor_code" required="required"  data-error="' . $this->lang->line("vendor_code") . ' ' . $this->lang->line("is_required") . '"');  ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="types"><?php echo $this->lang->line("buildings_name"); ?></label>

    <div class="controls" id="building_code"> <?php
        $sct1[""] = '';

        echo form_dropdown('building_code', $sct1, '', 'class="span4 select_search" id="building_code"  data-placeholder="Select Building Code" required="required"  data-error="' . $this->lang->line("building_code") . ' ' . $this->lang->line("is_required") . '"');  ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="types"><?php echo $this->lang->line("room_name"); ?></label>

    <div class="controls" id="apartment_code"> <?php
        $sct2[""] = '';

        echo form_dropdown('apartment_code', $sct2, '', 'class="span4 select_search" id="apartment_code"  data-placeholder="Select Apartment Code" required="required"  data-error="' . $this->lang->line("apartment_code") . ' ' . $this->lang->line("is_required") . '"');  ?> </div>
</div>

<div class="control-group">
    <label class="control-label" for="start_date"><?php echo $this->lang->line("move_in_date"); ?></label>
    <div class="controls"> <?php echo form_input('move_in_date', (isset($_POST['move_in_date']) ? $_POST['move_in_date'] : ""), 'class="span4" required="required"  id="move_in_date" data-error="' . $this->lang->line("move_in_date") . ' ' . $this->lang->line("is_required") . '"');?> </div>
</div>


<div class="control-group">
    <div
        class="controls"> <?php echo form_submit('submit', $this->lang->line("add_intake"), 'class="btn btn-primary"'); ?> </div>
</div>
<?php echo form_close(); ?>
<div id="loading" style="display: none;">
    <div class="blackbg"></div>
    <div class="loader"></div>
</div>