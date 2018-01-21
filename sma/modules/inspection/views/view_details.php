<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo $page_title; ?></title>
    <link rel="shortcut icon" href="<?php echo $this->config->base_url(); ?>assets/img/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo $this->config->base_url(); ?>assets/css/<?php echo THEME; ?>.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo $this->config->base_url(); ?>assets/js/jquery.js"></script>
    <style type="text/css">
        html, body {
            height: 100%; /* font-family: "Segoe UI", Candara, "Bitstream Vera Sans", "DejaVu Sans", "Bitstream Vera Sans", "Trebuchet MS", Verdana, "Verdana Ref", sans-serif; */
        }

        #wrap {
            padding: 20px;
        }

        .table th {
            text-align: center;
        }
    </style>
</head>

<body>
<div id="wrap">
    <div class="row-fluid text-center" style="margin-bottom:20px;">
        <img src="<?php echo base_url() . 'assets/img/' . LOGO2; ?>" alt="<?php echo SITE_NAME; ?>">
    </div>
    <div class="row-fluid">
        <div class="span6">
            <h3><?php echo $customer->name; ?></h3>

            <p style="text-align: left"> <?php echo $customer->address . ",<br />" . $customer->city . ", " . $customer->postal_code . ", " . $customer->city . ",<br />" . $customer->state; ?></p>

            <p style="text-align: left"><?php echo $this->lang->line("tel") . ": " . $customer->phone . "<br />" . $this->lang->line("email") . ": " . $customer->email; ?></p>
        </div>

        <div class="span6">
            <p style="font-weight:bold; text-align:right"><?php echo $this->lang->line("inspection_code"); ?>
                : <?php echo $rows[0]->inspection_code; ?></p>

            <p style="text-align:right"><?php echo $this->lang->line("inspection_date"); ?>
                : <?php echo $rows[0]->date; ?></p>

            <p style="text-align:right"><?php echo $this->lang->line("print_date"); ?>
                : <?php echo date('Y-m-d'); ?></p>
        </div>
        <div style="clear: both;"></div>
    </div>
    <p>&nbsp;</p>
    <?php
    $grandTotal = 0;
    $taxTotal = 0;
    ?>
    <table class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">

        <thead>

        <tr>
            <th><?php echo $this->lang->line("no"); ?></th>
            <th><?php echo $this->lang->line("vendor_code"); ?> </th>
            <th><?php echo $this->lang->line("building_code"); ?></th>
            <th style="padding-left:5px;"><?php echo $this->lang->line("apartment_code"); ?></th>
            <th style="padding-left:5px;">Deficiency <br/>Concern</th>
            <th style="padding-left:5px;">Deficiency <br/>Category</th>
            <th style="padding-left:5px;">Deficiency <br/>Details</th>
            <th style="padding-left:5px;">Comments</th>
            <th style="padding-left:5px;">Weight</th>
        </tr>

        </thead>

        <tbody>

        <?php
        $r = 1;
        foreach ($rows as $row): ?>
            <tr>
                <td style="text-align:center; width:40px; vertical-align:middle;"><?php echo $r; ?></td>
                <td style="vertical-align:middle;"><?php echo $row->name; ?></td>
                <td style="vertical-align:middle;"><?php echo $row->building_code; ?></td>
                <td style="vertical-align:middle;"><?php echo $row->apartment_code; ?></td>
                <td style="text-align:middle; width:50px; padding-right:10px;"><?php echo $row->concern_name; ?></td>
                <td style="text-align:middle; width:50px; padding-right:10px;"><?php echo $row->category_name; ?></td>
                <td style="text-align:middle; width:150px; padding-right:10px;"><?php echo $row->details_name; ?></td>
                <td style="text-align:middle; width:150px; padding-right:10px;"><?php echo $row->comments_id; ?></td>
                <td style="text-align:middle; width:20px; padding-right:10px;"><?php echo $row->weight; ?></td>
            </tr>
            <?php
            $r++;
            $grandTotal = ($grandTotal + $row->weight);
            $total++;
        endforeach;
        ?>
        </tbody>

    </table>

    <div class="row-fluid">
        <div class="span12">
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        </div>
    </div>

    <table class="table table-condensed" style="margin-bottom: 5px;">

        <thead>

        <tr>
            <th style="padding-left:5px;">&nbsp;&nbsp;&nbsp;</th>
            <th style="padding-left:5px;">Weighted <br/>Deficiencies</th>
            <?php
            foreach ($concern_weights as $concern_weight): ?>
                <th style="padding-left:5px;"><?php echo $concern_weight->concern_name; ?> </th>
            <?php endforeach; ?>
            <th style="padding-left:5px;">Total<br/>Deficiencies</th>
        </tr>

        </thead>

        <tbody>
        <tr>
            <td style="vertical-align:middle; text-align: center;">Current Deficiencies</td>
            <td style="vertical-align:middle;text-align: center;"> <?php echo $grandTotal; ?></td>

            <?php
            $r = 1;
            foreach ($concern_weights as $concern_weight): ?>
                <td style="vertical-align:middle;text-align: center;"><?php echo $concern_weight->count; ?></td>
            <?php
            endforeach;
            ?>

            <td style="vertical-align:middle;text-align: center;"><?php echo $total; ?></td>
        </tr>

        </tbody>

    </table>
    <div style="clear: both;"></div>

    <div class="row-fluid">
        <div class="span12">
            <br/>
            <br/>
            <p><b>Weighted Average Deficiencies Per Inspected Area with Deficiencies : <?php echo ($grandTotal/$total);?></b></p>
            <p><b>Outcome:
                    <?php
                    if(($grandTotal/$total) ==0 )
                    echo "Very Good";
                    if(($grandTotal/$total) >=0.1 &&  ($grandTotal/$total) <=3.0)
                        echo "Goc";
                    if(($grandTotal/$total) >3.0 &&  ($grandTotal/$total) <=5.0)
                        echo "Satisfactory";
                    if(($grandTotal/$total) >10)
                        echo "Unacceptable";
                    ?>
                </b></p>
            <br/>
        </div>
    </div>

</div>
</body>
</html>