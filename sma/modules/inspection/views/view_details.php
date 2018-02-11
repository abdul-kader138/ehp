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
<!--        <img src="--><?php //echo base_url() . 'assets/img/' . LOGO2; ?><!--" alt="--><?php //echo SITE_NAME; ?><!--">-->
        <h4 style="color: #000000;">CITY OF NEW YORK HUMAN RESOURCES ADMINISTRATION</h4>
        <h4 style="color: #000000;"><u>Inspection Outcome Report</u></h4>
        <p style="color: #000000;">Facility Name : <?php echo $inspection[0]->building_name ?></p>
        <p style="color: #000000;">Facility Address : <?php echo $inspection[0]->location ?></p>
    </div>
    <div class="row-fluid">
        <div class="span6">
            <p style="text-align:left;color: #000000;"><?php echo $this->lang->line("inspection_date"); ?>
                : <?php echo $rows[0]->date; ?></p>
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
            <th style="padding-left:5px;"><?php echo $this->lang->line("no"); ?></th>
            <th style="padding-left:5px;"><?php echo $this->lang->line("apartment_code"); ?></th>
            <th style="padding-left:5px;width:300px; ">Deficiency</th>
            <th style="padding-left:5px;">Deficiency <br/>Concern</th>
            <th style="padding-left:5px;">Weight</th>
            <th style="padding-left:5px;width:300px; ">Status and Comments</th>
            <th style="text-align:middle; width:400px;  padding-right:10px;">Corrective Action Plan</th>
        </tr>

        </thead>

        <tbody>

        <?php
        $r = 1;
        foreach ($rows as $row): ?>
            <tr>
                <td style="text-align:center; width:40px; vertical-align:middle;"><?php echo $r; ?></td>
                <td style="vertical-align:middle;"><?php echo $row->apartment_code; ?></td>
                <td style="text-align:middle;width:150px; padding-right:10px;"><?php echo $row->details_name; ?></td>
                <td style="text-align:middle;width:50px; padding-right:10px;"><?php echo $row->concern_id; ?></td>
                <td style="text-align:middle;width:20px; padding-right:10px;"><?php echo $row->weight; ?></td>
                <td style="text-align:middle;width:150px; padding-right:10px;"><?php echo $row->comments_id; ?></td>
                <td style="text-align:middle;width:400px; padding-right:10px;"></td>
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

        <tr style="font-size: small;">
            <th style="padding-left:5px;">&nbsp;&nbsp;&nbsp;</th>
            <th style="padding-left:5px;font-size: small;">Weighted <br/>Deficiencies</th>
            <?php
            foreach ($concern_weights as $concern_weight): ?>
                <th style="padding-left:5px;font-size: small;"><?php echo $concern_weight->concern_id; ?> </th>
            <?php endforeach; ?>
            <th style="padding-left:5px; font-size: small;">Total<br/>Deficiencies</th>
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
    <br/>
    <div class="row-fluid">
        <div class="span12" style="font-size: small;">
            <p style="font-size: small;"><b>Total Number Of Inspection Area Inspected
                    : <?php echo $inspection[0]->inspected_area; ?></b></p>
        </div>
    </div>

    <br/>
    <div class="row-fluid">
        <div class="span12" style="font-size: small;">
            <p style="font-size: small;"><b>Weighted Average Deficiencies Per Inspected Area with Deficiencies
                    : <?php echo number_format(((($grandTotal / $total)+ ($grandTotal / $inspection[0]->inspected_area)))/2, 2, '.', ''); ?></b></p>
        </div>
    </div>

    <br/>

    <div class="row-fluid">
        <div class="span5" style="font-size: small;">
            <p style="font-size: small;"><b>Note:<?php echo html_entity_decode($inspection[0]->note); ?>
            </b></p>
        </div>
    </div>
    <br/>

    <div class="row-fluid">
        <div class="span6" style="font-size: small;">
            <p style="font-size: small;"><b>Outcome:
                    <?php
                    if (($grandTotal / $total) == 0)
                        echo "Very Good";
                    if (($grandTotal / $total) >= 0.1 && ($grandTotal / $total) <= 3.01)
                        echo "Good";
                    if (($grandTotal / $total) > 3.01 && ($grandTotal / $total) <= 5.01)
                        echo "Satisfactory";
                    if (($grandTotal / $total) > 5.01 && ($grandTotal / $total) <= 10.0)
                        echo "Unsatisfactory";
                    if (($grandTotal / $total) > 10)
                        echo "Unacceptable";
                    ?>
                </b></p>
        </div>
        <div class="span6" style="font-size: small;">
            <p style="font-size: small"><b>Corrective Action plan Due Date: </b></p>
        </div>
    </div>
    <br/>

    <p>&nbsp;</p>
    <table style="border: 1px solid;" width="30%">
        <tr style="font-size: small;"><td style="text-align: center" colspan="2"><b>Rating</b></td></tr>
        <tr style="border: 1px solid;font-size: small;;">
            <td style="border: 1px solid; text-align: center;"><b>Very Good</b></td>
            <td style="border: 1px solid;font-size: small; text-align: center;"><b>0</b></td>
        </tr>
        <tr style="border: 1px solid;font-size: small;">
            <td style="border: 1px solid; font-size: small;text-align: center;"><b>Good</b></td>
            <td style="border: 1px solid;font-size: small; text-align: center;"><b>0.01-3.0</b></td>
        </tr>
        <tr style="border: 1px solid; text-align: center;font-size: small;">
            <td style="border: 1px solid; font-size: small;text-align: center;"><b>Satisfactory</b></td>
            <td style="border: 1px solid; font-size: small;text-align: center;"><b>3.01-5.0</b></td>
        </tr>

        <tr style="border: 1px solid;font-size: small;">
            <td style="border: 1px solid; font-size: small;text-align: center;"><b>Unsatisfactory</b></td>
            <td style="border: 1px solid; font-size: small;text-align: center;"><b>5.01-10.0</b></td>
        </tr>
        <tr style="border: 1px solid;font-size: small;">
            <td style="border: 1px solid; font-size: small;text-align: center;"><b>Unacceptable</b></td>
            <td style="border: 1px solid;font-size: small; text-align: center;"><b> >10</b></td>
        </tr>
    </table>


    <p>&nbsp;</p>
    <table width="100%">
        <tr>
            <td style="width:23%; text-align:center">
                <div style="float:left; margin:5px 15px">
                    <p>&nbsp;</p>
                    <p style="text-transform: capitalize;"><?php echo $inspection[0]->created_by ? $inspection[0]->created_by  : '--'; ?> </p>
                    <p>&nbsp;</p>

                    <p style="border-top: 1px solid #000;">Inspection Conducted By</p>
                </div>
            </td>

            <td style="width:23%; text-align:center">
                <div style="float:left; margin:5px 15px">
                    <p>&nbsp;</p>
                    <p style="text-transform: capitalize;"> <?php echo $inv->app_name ? $inv->app_name : '--'; ?> </p>
                    <p>&nbsp;</p>

                    <p style="border-top: 1px solid #000;">Report Reviwed By</p>
                </div>
            </td>


            <td style="width:23%; text-align:center">

                <div style="float:left; margin:5px 15px">
                    <p>&nbsp;</p>
                    <p style="text-transform: capitalize;"> <?php echo $inv->app_name ? $inv->app_name : '--'; ?> </p>
                    <p>&nbsp;</p>

                    <p style="border-top: 1px solid #000;">Housing Director</p>
                </div>

            </td>

        </tr>
    </table>

</div>
</body>
</html>