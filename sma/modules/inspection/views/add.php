<link href="<?php echo $this->config->base_url(); ?>assets/css/datepicker.css" rel="stylesheet">
<style type="text/css">
    .table th {
        text-align: center;
    }

    .table td {
        vertical-align: middle;
    }

    .table td:last-child {
        text-align: center !important;
    }


</style>
<script src="<?php echo $this->config->base_url(); ?>assets/js/jquery-ui.js"></script>
<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
var concern_id_div = 0;
$(document).ready(function () {



    $("#dyTable").on("focus", 'input[id^="weight_"]', function() {
        oqty = parseFloat($(this).val());
    });
    $("#dyTable").on("blur", 'input[id^="weight_"]', function() {
        var total= parseFloat($("#total").val());
        var rID = $(this).attr('id');
        var r_id = rID.split("_");
        var rw_no = r_id[1];
        var nqty = parseFloat($(this).val());
        var oldrowtotal = oqty ;
        var newrowtotal = nqty;
        total -= oldrowtotal;
        total += newrowtotal;
        $('#total').val(total.toFixed(2));
    });


        $("#dyTable").on("click", '.del', function () {
            var delID = $(this).attr('id');
            var delRowTotal= parseFloat($("#weight_"+delID).val());
            var total= parseFloat($("#total").val());
            total -= delRowTotal;
            $('#total').val(total.toFixed(2));
            row_id = $('#row_' + delID);
            row_id.remove();
            an--;

        });

        $("#date").datepicker({
            format: "<?php echo JS_DATE; ?>",
            autoclose: true
        });

        $('#byTab a, #noteTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
        $('#byTab #select_by_name, #noteTab a:last').tab('show');

        $("#date").datepicker("setDate", new Date());
        $('form').form();

        $('#item_name').bind('keypress', function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                return false;
            }
        });
        $("form").submit(function () {
            if (an <= 1) {
                alert("<?php echo $this->lang->line('no_invoice_item'); ?>");
                return false;
            }
        });

        $("#add_options").draggable({refreshPositions: true});
        var an = 1;
        var count = 0;
        $("#name").autocomplete({
            source: function (request, response) {
                $.ajax({
                    url: "<?php echo site_url('module=inspection&view=suggestions'); ?>",
                    data: {
                <?php echo $this->security->get_csrf_token_name(); ?>:
                "<?php echo $this->security->get_csrf_hash() ?>", term
                :
                $("#name").val()
            },
            dataType: "json",
            type: "get",
            success: function (data) {
                response(data);
            },
            error: function (result) {
                alert('<?php echo $this->lang->line('no_suggestions'); ?>');
                $('.ui-autocomplete-input').removeClass("ui-autocomplete-loading");
                $('#codes').val('');
                return false;
            }
        });
    },
    minLength
:
2,
    select
:
function (event, ui) {
    $(this).removeClass('ui-autocomplete-loading');

    if (an >=<?php echo TOTAL_ROWS; ?>) {
        alert("You have reached the max item limit.");
        return false;
    }
    if (count >= 200) {
        alert("You have reached the max item limit.");
        return false;
    }
    var item_name = ui.item.label;


    $.ajax({
        type: "get",
        async: false,
        url: "<?php echo $this->config->base_url(); ?>index.php?module=inspection&view=add_room",
        data: {
    <?php echo $this->security->get_csrf_token_name(); ?>:
    "<?php echo $this->security->get_csrf_hash() ?>", name
:
    item_name
}
,
dataType: "json",
    success
:
function (data) {

    apt_code = data.code;

}
,
error: function () {
    alert('<?php echo $this->lang->line('code_error'); ?>');
    $('.ui-autocomplete-loading').removeClass("ui-autocomplete-loading");
    item_name = false;
}

})
;

if (item_name == false) {
    $(this).val('');
    return false;
}

var newTr = $('<tr id="row_' + count + '"></tr>');
newTr.html('<td><input class="span5 tran" name="apt_' + count + '" id="apt_' + count + '" type="text" value="' + item_name + '"></td><?php
    echo '<td><select class="span5 select_search" onchange="loadDetails(this);" data-placeholder="Select..."  name="category_\' + count + \'" id="\' + count + \'">';
    echo "<option>Select Category</option>";
    foreach ($categories as $category) {
        echo "<option value=" . $category->category_code;
        echo ">" . $category->category_name . "</option>";
    }
    echo '</select></td>';
    echo '<td><select class="span4 select_search"  data-placeholder="Select..." name="concern_\' + count + \'" id="concern_\' + count + \'">';
    echo "<option>Select Concern</option>";
    foreach ($concerns as $concern) {
        echo "<option value=" . $concern->concern_code;
        echo ">" . $concern->concern_name . "</option>";
    }
    echo '</select></td>';
?><td id="details_'+count+'"><select class="span6 select_search" id="detail_'+count+'" name="detail_'+count+'"><option></option></select></td><td><input type="text" class="span2 tran2" style="text-align:right;" value="0" name="weight_' + count + '" id="weight_' + count + '"></td><td><input type="text" class="span6 tran2" style="text-align:right;" name="comments_' + count + '" id="comments_' + count + '"></td><td><input type="file" class="span12 tran2" style="text-align:right;" name="image' + count + '" id="image_' + count + '"></td><td><i class="icon-trash tip del" id="' + count + '" title="Remove this Item" style="cursor:pointer;" data-placement="right"></i></td>');
newTr.prependTo("#dyTable");

count++;
an++;
$("form select").chosen({
    no_results_text: "<?php echo $this->lang->line('no_results_matched'); ?>",
    disable_search_threshold: 5,
    allow_single_deselect: true
});


},
close: function () {
    $('#name').val('');
}
})
;

})
;
$(".show_hide").slideDown('slow');

$('.show_hide').click(function () {
    $(".item_name").slideToggle();
});


//    $('#building_code').change(function () {
function loadDetails(obj) {
    var c = $(obj).find(":selected").val();
    var concern_id = obj.id;
    concern_id_div = $('#details_' + concern_id);
    var v = $("#vendor_code").val();

    $('#loading').show();
    $.ajax({
        type: "get",
        async: false,
        url: "index.php?module=inspection&view=getDetails",
        data: {
    <?php echo $this->security->get_csrf_token_name(); ?>:
    "<?php echo $this->security->get_csrf_hash() ?>", details_id:concern_id, category_code: c
}
,
dataType:"html",
    success
:
function (data) {
    if (data != "") {
        $('#details_' + concern_id).empty();
        $('#details_' + concern_id).html(data);
    } else {
        (concern_id_div).empty();
        var default_data = '<select name="details_code" class="select_search span4" id="details_code" data-placeholder="Select Details"></select>';
        $(concern_id_div).html(default_data);
    }
}
,
error: function () {
    bootbox.alert('<?php echo $this->lang->line('ajax_error'); ?>');
    $('#loading').hide();
}

})
;

$('#loading').hide();
}


</script>

<?php if ($message) {
    echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
} ?>

<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>

<?php $attrib = array('class' => 'form-horizontal', 'id' => 'addSale_form');
echo form_open("module=inspection&view=add_inspection", $attrib); ?>
<div class="control-group">
    <label class="control-label" for="date"><?php echo $this->lang->line("date"); ?></label>

    <div
        class="controls"> <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="span4" id="date" required="required" data-error="' . $this->lang->line("date") . ' ' . $this->lang->line("is_required") . '"'); ?></div>
</div>
<div class="control-group">
    <label class="control-label" for="reference_no"><?php echo $this->lang->line("inspection_code"); ?></label>

    <div
        class="controls"> <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $ref), 'class="span4 tip" id="reference_no" readonly="readonly" required="required" data-error="' . $this->lang->line("reference_no") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
</div>


<div class="control-group">
    <label class="control-label" id="customer_l"><?php echo $this->lang->line("customer"); ?></label>
<!--    <label class="control-label" id="customer_l">Vendor Name</label>-->

    <div class="controls">  <?php
        $cu[""] = "";
        foreach ($customers as $customer) {
            if ($customer->company == "-" || !$customer->company) {
                $cu[$customer->id] = $customer->name . " (P)";
            } else {
                $cu[$customer->id] = $customer->name . " (C)";
            }
        }
        echo form_dropdown('customer', $cu, (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="customer" class="span4" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("customer") . '" required="required" data-error="' . $this->lang->line("customer") . ' ' . $this->lang->line("is_required") . '"');
        ?> </div>
</div>

<div class="control-group">
    <label class="control-label" id="customer_l"><?php echo $this->lang->line("customer"); ?></label>
<!--    <label class="control-label" id="customer_l">Building Name</label>-->

    <div class="controls">  <?php
        $bu[""] = "";
        foreach ($buildingList as $building) {
            $bu[$building->building_code] = $building->building_code;
        }
        echo form_dropdown('building_code', $bu, (isset($_POST['building_code']) ? $_POST['building_code'] : ""), 'id="building_code" class="span4" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("customer") . '" required="required" data-error="' . $this->lang->line("customer") . ' ' . $this->lang->line("is_required") . '"');
        ?> </div>
</div>


<div class="control-group">
    <div class="controls">
        <div class="span4" id="drag">
            <div class="add_options clearfix" id="add_options">
                <div id="draggable"><?php echo $this->lang->line('draggable'); ?></div>
                <div class="fancy-tab-container">
                    <ul class="nav nav-tabs three-tabs fancy" id="byTab">
<!--                               id="select_by_codes">Apartment Code</a></li>-->
                        <li><a href="#by_codes"
                               id="select_by_codes"><?php echo $this->lang->line("product_code"); ?></a></li>
                        <li><a href="#by_name" id="select_by_name"><?php echo $this->lang->line("product_name"); ?></a>
<!--                        <li><a href="#by_name" id="select_by_name">Apartment Name</a>-->
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tab-bg"
                             id="by_codes"> <?php echo form_input('codes', '', 'class="input-block-level ttip" id="codes" data-placement="top" data-trigger="focus" placeholder="Code" title="' . $this->lang->line("au_pr_name_tip") . '"'); ?> </div>
                        <div class="tab-pane tab-bg active"
                             id="by_name"> <?php echo form_input('name', '', 'class="input-block-level ttip" id="name" data-placement="top" data-trigger="focus" placeholder="Name" title="' . $this->lang->line("au_pr_name_tip") . '"'); ?> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="control-group table-group">
    <label class="control-label table-label">Inspection Details</label>

    <div class="controls table-controls">
        <table id="dyTable" class="table items table-striped table-bordered table-condensed table-hover">
            <thead>
            <th class="span5"><?php echo $this->lang->line("product_name"); ?></th>
<!--            <th class="span6">Apartment Code</th>-->

            <?php if (DISCOUNT_OPTION == 2) {
                echo '<th class="span2">' . $this->lang->line("discount") . '</th>';
//                echo '<th class="span4">Category</th>';
            } ?>
            <?php if (TAX1) {
                echo '<th class="span2">' . $this->lang->line("tax_rate") . '</th>';
//                echo '<th class="span3">Concern</th>';
            } ?>
            <th class="span2"><?php echo $this->lang->line("quantity"); ?></th>
<!--            <th class="span12">Deficiency Details</th>-->
<!--            <th class="span2">--><?php //echo $this->lang->line("unit_price"); ?><!--</th>-->
            <th class="span2">Weight</th>
            <th class="span12">Status <br/>Comments</th>
            <th class="span12">Image</th>
            <th style="width: 20px;"><i class="icon-trash" style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<div class="row-fluid">
    <div class="span7">

        <div class="control-group">
            <label class="control-label"><?php echo $this->lang->line("note"); ?></label>

            <div class="controls fancy-tab-container">

                <ul class="nav nav-tabs two-tabs fancy" id="noteTab">
                    <li class="active"><a href="#internal"><?php echo $this->lang->line('internal_note'); ?></a></li>
                    <li><a href="#onquote"></a></li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="internal">
                        <?php echo form_textarea('internal_note', (isset($_POST['internal_note']) ? $_POST['internal_note'] : ""), 'class="input-block-level" id="internal_note" style="margin-top: 10px; height: 100px;"'); ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="tab-pane" id="onquote">
                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="input-block-level" id="note" style="margin-top: 10px; height: 100px;"'); ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

    </div>
    <div class="span5">

        <div class="control-group inverse" style="margin-bottom:5px; cursor: default;">
            <label class="control-label" style="cursor: default;"><?php echo $this->lang->line("total_weight"); ?></label>

            <div
                class="controls"> <?php echo form_input('total', '0', 'class="input-block-level text-right" id="total" disabled'); ?>
            </div>
        </div>
    </div>
</div>

<div class="control-group">
    <div
        class="controls"><?php echo form_submit('submit', $this->lang->line("submit"), 'class="btn btn-primary" style="padding: 6px 15px;"'); ?></div>
</div>
<?php echo form_close(); ?> 
