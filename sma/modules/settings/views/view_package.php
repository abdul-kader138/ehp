<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $page_title." ".$this->lang->line("no")." ".$pck->id; ?></title>
    <link rel="shortcut icon" href="<?php echo $this->config->base_url(); ?>assets/img/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo $this->config->base_url(); ?>assets/css/<?php echo THEME; ?>.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/js/jquery.js"></script>
    <style type="text/css">
        html, body { height: 100%; /* font-family: "Segoe UI", Candara, "Bitstream Vera Sans", "DejaVu Sans", "Bitstream Vera Sans", "Trebuchet MS", Verdana, "Verdana Ref", sans-serif; */ }
        #wrap { padding: 20px; }
        .table th { text-align:center; }
    </style>
</head>

<body>
<div id="wrap">
    <div class="row-fluid text-center" style="margin-bottom:20px;">
        <img src="<?php echo base_url().'assets/img/'.LOGO2; ?>" alt="<?php echo SITE_NAME; ?>">
    </div>
    <div class="row-fluid">


        <div class="span6">

            <h3 class="inv"><?php echo "Item List"; ?></h3>
        </div>
        <div style="clear: both;"></div>
    </div>
    <p>&nbsp;</p>
    <table class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">

        <thead>

        <tr>
            <th><?php echo $this->lang->line("no"); ?></th>
            <th><?php echo $this->lang->line("package_name"); ?></th>
            <th><?php echo $this->lang->line("product_name"); ?> (<?php echo $this->lang->line("product_code"); ?>)</th>
            <th style="padding-right:20px;"><?php echo $this->lang->line("unit_price"); ?></th>
            <th><?php echo $this->lang->line("quantity"); ?></th>
        </tr>

        </thead>

        <tbody>

        <?php $grandTotal=0; $taxTotal=0;$r = 1; foreach ($pck as $row):?>
            <tr>
                <td style="text-align:center; width:40px; vertical-align:middle;"><?php echo $r; ?></td>
                <td style="width: 100px; text-align:center; vertical-align:middle;"><?php echo $row->package_name; ?></td>
                <td style="vertical-align:middle;"><?php echo $row->product_name." (".$row->product_code.")"; ?></td>
                <td style="text-align:right; width:100px; padding-right:10px;"><?php echo $row->product_um; ?></td>
                <td style="text-align:right; width:100px; padding-right:10px;"><?php echo $row->product_qty; ?></td>
            </tr>
            <?php
            $r++;
            $grandTotal=($grandTotal+($row->product_qty));
        endforeach;
        ?>

            <tr><td colspan="4" style="text-align:right; padding-right:10px;"><?php echo $this->lang->line("total_qty"); ?></td><td style="text-align:right; padding-right:10px;"><?php echo $grandTotal; ?></td></tr>
        <tr><td colspan="4" style="text-align:right; padding-right:10px; font-weight:bold;"><?php echo $this->lang->line("grand_total_qty"); ?> </td><td style="text-align:right; padding-right:10px; font-weight:bold;"><?php echo ($grandTotal+$taxTotal); ?></td></tr>

        </tbody>

    </table>
    <div style="clear: both;"></div>

    <div style="clear: both;"></div>

    <div class="row-fluid">
        <div class="span5">
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p style="border-bottom: 1px solid #666;">&nbsp;</p>
            <p><?php echo $this->lang->line("signature")." &amp; ".$this->lang->line("stamp"); ; ?></p>
        </div>
    </div>

</div>
</body>
</html>