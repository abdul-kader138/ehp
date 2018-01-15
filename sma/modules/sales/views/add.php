<link href="<?php echo $this->config->base_url(); ?>assets/css/datepicker.css" rel="stylesheet">
<style type="text/css">
    .table th { text-align:center; }
    .table td { vertical-align: middle; }
    .table td:last-child { text-align: center !important;}
    .select {
        min-height: 26px !important;  
        height: 26px !important;
        text-align:left !important;
        background: url(<?php echo $this->config->base_url(); ?>assets/img/darrow.png) no-repeat right transparent;
    }
</style>
<script src="<?php echo $this->config->base_url(); ?>assets/js/jquery-ui.js"></script>
<script src="<?php echo $this->config->base_url(); ?>assets/js/validation.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#byTab a, #noteTab a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
        //$('#byTab #select_by_code, #noteTab a:last').tab('show');
        //$('#byTab #select_by_codes, #noteTab a:last').tab('show');
        $('#byTab #select_by_name, #noteTab a:last').tab('show');
        $("#date").datepicker({
            format: "<?php echo JS_DATE; ?>",
            autoclose: true
        });
        $("#date").datepicker("setDate", new Date());
        $('form').form();


</script>

        <?php if ($message) {
            echo "<div class=\"alert alert-error\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>";
        } ?>

<h3 class="title"><?php echo $page_title; ?></h3>
<p><?php echo $this->lang->line("enter_info"); ?></p>

        <?php $attrib = array('class' => 'form-horizontal', 'id' => 'addSale_form');
        echo form_open("module=sales&view=add", $attrib); ?>
<div class="control-group">
    <label class="control-label" for="date"><?php echo $this->lang->line("date"); ?></label>
    <div class="controls"> <?php echo form_input($date, (isset($_POST['date']) ? $_POST['date'] : ""), 'class="span4" id="date" required="required" data-error="' . $this->lang->line("date") . ' ' . $this->lang->line("is_required") . '"'); ?></div>
</div>
<div class="control-group">
    <label class="control-label" for="reference_no"><?php echo $this->lang->line("reference_no"); ?></label>
    <div class="controls"> <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $rnumber), 'class="span4 tip" id="reference_no" required="required" data-error="' . $this->lang->line("reference_no") . ' ' . $this->lang->line("is_required") . '"'); ?> </div>
</div>
<div class="control-group">
    <label class="control-label" id="warehouse_l"><?php echo $this->lang->line("warehouse"); ?></label>
    <div class="controls">  <?php
        $wh[''] = '';
//        foreach ($warehouses as $warehouse) {
//            $wh[$warehouse->id] = $warehouse->name;
//        }
        echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ""), 'id="warehouse_s" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("warehouse") . '" required="required" data-error="' . $this->lang->line("warehouse") . ' ' . $this->lang->line("is_required") . '"');
        ?> </div>
</div>
<div class="control-group">
    <label class="control-label" id="biller_l"><?php echo $this->lang->line("biller"); ?></label>
    <div class="controls">  <?php
        $bl[""] = "";
//        foreach ($billers as $biller) {
//            $bl[$biller->id] = $biller->name;
//        }
        echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : ""), 'id="biller_s" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("biller") . '" required="required" data-error="' . $this->lang->line("biller") . ' ' . $this->lang->line("is_required") . '"');
        ?> </div>
</div>
<div class="control-group">
    <label class="control-label" id="customer_l"><?php echo $this->lang->line("customer"); ?></label>
    <div class="controls">  <?php
        $cu[""] = "";
//        foreach ($customers as $customer) {
//            if ($customer->company == "-" || !$customer->company) {
//                $cu[$customer->id] = $customer->name . " (P)";
//            } else {
//                $cu[$customer->id] = $customer->name . " (C)";
//            }
//        }
        echo form_dropdown('customer', $cu, (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="customer_s" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("customer") . '" required="required" data-error="' . $this->lang->line("customer") . ' ' . $this->lang->line("is_required") . '"');
        ?> </div>
</div>
<?php if (TAX2) { ?>
    <div class="control-group">
        <label class="control-label" id="tax2_l"><?php echo $this->lang->line("tax2"); ?></label>
        <div class="controls">  <?php
    $tr[""] = "";
//    foreach ($tax_rates as $tax) {
//        $tr[$tax->id] = $tax->name;
//    }
    echo form_dropdown('tax2', $tr, (isset($_POST['tax2']) ? $_POST['tax2'] : ""), 'id="tax2_s" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("tax2") . '" required="required" data-error="' . $this->lang->line("tax2") . ' ' . $this->lang->line("is_required") . '"');
    ?> </div>
    </div>
<?php } ?>
<?php if (DISCOUNT_OPTION == 1) { ?>
    <div class="control-group">
        <label class="control-label" id="discount_l"><?php echo $this->lang->line("discount"); ?></label>
        <div class="controls">  <?php
    $ds[""] = "";
//    foreach ($discounts as $discount) {
//        $ds[$discount->id] = $discount->name;
//    }
    echo form_dropdown('inv_discount', $ds, (isset($_POST['inv_discount']) ? $_POST['inv_discount'] : ""), 'id="discount_s" data-placeholder="' . $this->lang->line("select") . ' ' . $this->lang->line("discount") . '" required="required" data-error="' . $this->lang->line("discount") . ' ' . $this->lang->line("is_required") . '"');
    ?> </div>
    </div>
<?php } ?>

<div class="control-group">
    <div class="controls">
        <div class="span4" id="drag">
            <div class="add_options clearfix" id="add_options">
                <div id="draggable"><?php echo $this->lang->line('draggable'); ?></div>
                <div class="fancy-tab-container">
                    <ul class="nav nav-tabs three-tabs fancy" id="byTab">
                        <li class="active"><a href="#by_code" id="select_by_code"><?php echo $this->lang->line("barcode_scanner"); ?></a></li>
                        <li><a href="#by_codes" id="select_by_codes"><?php echo $this->lang->line("product_code"); ?></a></li>
                        <li><a href="#by_name" id="select_by_name"><?php echo $this->lang->line("product_name"); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tab-bg" id="by_code" > <?php echo form_input('code', '', 'class="input-block-level ttip" id="code" data-placement="top" data-trigger="focus" placeholder="' . $this->lang->line("barcode_scanner") . '" title="' . $this->lang->line("use_barcode_scanner_tip") . '"'); ?> </div>
                        <div class="tab-pane tab-bg" id="by_codes" > <?php echo form_input('codes', '', 'class="input-block-level ttip" id="codes" data-placement="top" data-trigger="focus" placeholder="' . $this->lang->line("product_code") . '" title="' . $this->lang->line("au_pr_name_tip") . '"'); ?> </div>
                        <div class="tab-pane tab-bg active" id="by_name"> <?php echo form_input('name', '', 'class="input-block-level ttip" id="name" data-placement="top" data-trigger="focus" placeholder="' . $this->lang->line("product_name") . '" title="' . $this->lang->line("au_pr_name_tip") . '"'); ?> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="control-group table-group">
    <label class="control-label table-label"><?php echo $this->lang->line("invoice_items"); ?></label>
    <div class="controls table-controls">
        <table id="dyTable" class="table items table-striped table-bordered table-condensed table-hover">
            <thead>
            <th class="span5"><?php echo $this->lang->line("product_name") . " (" . $this->lang->line("product_code") . ")"; ?></th>
                        <?php if (PRODUCT_SERIAL) {
                            echo '<th class="span2">' . $this->lang->line("serial_no") . '</th>';
                        } ?>
<?php if (DISCOUNT_OPTION == 2) {
    echo '<th class="span2">' . $this->lang->line("discount") . '</th>';
} ?>
<?php if (TAX1) {
    echo '<th class="span2">' . $this->lang->line("tax_rate") . '</th>';
} ?>
            <th class="span2"><?php echo $this->lang->line("quantity"); ?></th>
            <th class="span2"><?php echo $this->lang->line("unit_price"); ?></th>
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
                    <li><a href="#onquote"><?php echo $this->lang->line('on_invoice_note'); ?></a></li>
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
            <label class="control-label" style="cursor: default;"><?php echo $this->lang->line("total"); ?></label>
            <div class="controls"> <?php echo form_input('dummy_sales', '', 'class="input-block-level text-right" id="total" disabled'); ?>
            </div>
        </div> 
    <?php if (DISCOUNT_OPTION == 1 || DISCOUNT_OPTION == 2) { ?>
            <div class="control-group inverse" style="margin-bottom:5px;">
                <label class="control-label" style="cursor: default;"><?php echo $this->lang->line("discount"); ?></label>
                <div class="controls"> <?php echo form_input('dummy_ds', '', 'class="input-block-level text-right" id="tds" disabled'); ?>
                </div>
            </div> 
<?php } if (TAX1) { ?>
            <div class="control-group inverse" style="margin-bottom:5px;">
                <label class="control-label" style="cursor: default;"><?php echo $this->lang->line("product_tax"); ?></label>
                <div class="controls"> <?php echo form_input('dummy_tax1', '', 'class="input-block-level text-right" id="ttax1" disabled'); ?>
                </div>
            </div> 
<?php } if (TAX2) { ?>
            <div class="control-group inverse" style="margin-bottom:5px;">
                <label class="control-label" style="cursor: default;"><?php echo $this->lang->line("invoice_tax"); ?></label>
                <div class="controls"> <?php echo form_input('dummy_tax2', '', 'class="input-block-level text-right" id="ttax2" disabled'); ?>
                </div>
            </div> 
<?php } ?>
        <div class="control-group" style="margin-bottom:5px;">
            <label class="control-label" for="shipping"><?php echo $this->lang->line("shipping"); ?></label>
            <div class="controls"> <?php echo form_input('shipping', '', 'class="input-block-level text-right" id="shipping"'); ?>
            </div>
        </div> 
		
		<div class="control-group" style="margin-bottom:5px;">
            <label class="control-label" for="shipping">Previous Due Amount</label>
            <div class="controls"> <?php echo form_input('due', '0.00', 'class="input-block-level text-right" id="due" disabled'); ?>
            </div>
        </div> 
		
        <div class="control-group inverse" style="margin-bottom:5px;">
            <label class="control-label" style="cursor: default;"><?php echo $this->lang->line("total_payable"); ?></label>
            <div class="controls"> <?php echo form_input('dummy_total', '', 'class="input-block-level text-right" style="font-weight: bold;" id="gtotal" disabled'); ?>
            </div>
        </div> 
         
		 <div class="control-group" style="margin-bottom:5px;">
            <label class="control-label" for="shipping">Paid Amount</label>
            <div class="controls"> <?php echo form_input('paid', '', 'class="input-block-level text-right" id="paid"'); ?>
            </div>
        </div> 

    </div>
</div>

<div class="control-group"><div class="controls"><?php echo form_submit('submit', $this->lang->line("submit"), 'class="btn btn-primary" style="padding: 6px 15px;"'); ?></div></div>
<?php echo form_close(); ?> 
