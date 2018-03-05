<!doctype html>
<html>
<head>
    <meta charset="utf-8">
<!--    <title>--><?php //echo $page_title . " " . $this->lang->line("no"); ?><!--</title>-->
<!--    <title></title>-->
    <style type="text/css" media="all">
        body {
            text-align: center;
            color: #000;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        #wrapper {
            width: 600px;
            margin: 0 auto;
            top: -50px;
        }

        /*#wrapper {margin: 0 auto; top:-50px; }*/
        #wrapper img {
            max-width: 250px;
            width: auto;
        }

        h3 {
            margin: 5px 0;
        }

        .left {
            width: 50%;
            float: left;
            text-align: left;
            margin-bottom: 3px;
        }

        .right {
            width: 50%;
            float: right;
            text-align: right;
            margin-bottom: 3px;
        }

        .table, .totals {
            width: 80%;
            margin: 10px 0;
        }

        .table th {
            border-bottom: 1px solid #000;
        }

        .table td {
            padding: 1px;
        }

        .totals td {
            width: 24%;
            padding: 0;
        }

        .table td:nth-child(2) {
            overflow: hidden;
        }

        @media print {
            #buttons {
                display: none;
            }

            #wrapper {
                max-width: 800px;
                width: 100%;
                margin: 0 auto;
                font-size: 9px;
                margin-top:: -100px;
            }

            #wrapper img {
                max-width: 250px;
                width: 80%;
            }
        }


    </style>
</head>

<body>
<div id="wrapper">
    <h3>CITY OF NEW YORK HUMAN RESOURCES ADMINISTRATION</h3>

    <h3><?php echo $c_name;?></h3>
    <br/>
    <?php
    echo "<span class=\"left\">Building Code: " . $rows[0]->building_code . "</span>";
    echo "<span class=\"right\">Billing Period: " . $sDate . " To " . $eDate . "</span>";
    echo "<span class=\"left\">Building Name: " . $rows[0]->building_name . "</span>";
    echo "<span class=\"right\">Facility Rate: " . $rows[0]->room_rent . "</span>";
    echo "<span class=\"left\">Building Location: " . $rows[0]->location . "</span>";
    echo "<span class=\"right\">Printing Date: " . date("d-m-Y h:i:sa") . "</span>";
    ?>
    <div style="clear:both;"></div>
        <table class="table table-bordered table-hover table-striped"  width="100%" cellspacing="0" border="1px solid">
            <thead>
            <tr>
                <th style="text-align:center; width:30px;"> <?php echo $this->lang->line("#"); ?></th>
                <th style="text-align:center; width:200px;"> Apartment Name</th>
                <th style="text-align:center; width:350px;">Client Name</th>
                <th style="text-align:center; width:150px;">SSN</th>
                <th style="text-align:center; width:350px; ">Move In Date</th>
                <th style="text-align:center; width:350px; ">Move Out Date</th>
                <th style="text-align:right; width:65px;"> Total Days</th>
                <th style="text-align:right; width:300px;"> Amount($)</th>
            </tr>
            </thead>
            <tbody>
            <?php $r = 1;
            $totalCount = 0;
            $getBuy = 0;


                var_dump($eDate);
            foreach ($rows as $row):


                if ($row->move_in_date) {
                    $now = time(); // or your date as well
                    $m_date = strtotime($row->move_in_date);

                    if ($row->move_out_date) {
                        $o_date = strtotime($row->move_out_date);
                        $datediff = $o_date - $m_date;
                    } else {
                        $o_date=strtotime($eDate);
                        $datediff = ($o_date - $m_date);
                    }
                    $days = round($datediff / (60 * 60 * 24));
                } else {
                    $days = null;
                }

                if($row->name) $name=$row->name;
                else $name="OFFLINE";

                ?>
                <tr>
                    <td style="text-align:center; width:30px;">  <?php echo $r; ?></td>
                    <td style="text-align:left; width:200px;">   <?php echo $row->room_name ?></td>
                    <td style="text-align:left; width:350px;">  <?php echo $name; ?></td>
                    <td style="text-align:left; width:150px;">  <?php echo $row->ssn; ?></td>
                    <td style="text-align:left; width:350px; ">  <?php echo $row->move_in_date; ?></td>
                    <td style="text-align:left; width:350px; ">  <?php echo $row->move_out_date; ?></td>
                    <td style="text-align:right; width:65px;">  <?php echo $days; ?></td>
                    <td style="text-align:right; width:300px;">  <?php echo $this->ion_auth->formatMoney($row->room_rent * $days); ?></td>
                </tr>
<?php
                $totalCount = $totalCount + ($row->room_rent * $days);
                $r++;
            endforeach;
            ?>
            </tbody>
        </table>

        <table class="totals" cellspacing="0" border="0">
            <tbody>
            <tr>
                <td style="text-align:left;">Total Apartment</td>
                <td style="text-align:right; padding-right:1.5%; border-right: 1px solid #999;font-weight:bold;"><?php echo $r; ?></td>
                <td style="text-align:left; padding-left:1.5%;">Total Amount</td>
                <td style="text-align:right;font-weight:bold;"><?php echo $this->ion_auth->formatMoney($totalCount); ?></td>
            </tr>
            <tr>
            </tbody>
        </table>
        <table width="100%">
            <tr>
                <td style="width:23%; text-align:center">
                    <div style="float:left; margin:5px 15px">
                        <p>&nbsp;</p>

                        <p style="text-transform: capitalize;">

                        <p style="border-top: 1px solid #000;">Reviewed By</p>
                    </div>
                </td>

                <td style="width:23%; text-align:center">
                    <div style="float:left; margin:5px 15px">
                        <p>&nbsp;</p>

                        <p style="border-top: 1px solid #000;">Chief Financial Officer</p>
                    </div>
                </td>


                <td style="width:23%; text-align:center">

                    <div style="float:left; margin:5px 15px">
                        <p>&nbsp;</p>

                        <p style="border-top: 1px solid #000;">Program Manager</p>
                    </div>
                </td>

            </tr>
        </table>
    <div style="border-top:1px solid #000; padding-top:10px;"> </div>

    <div id="buttons" style="padding-top:10px; text-transform:uppercase;">
<!--        <span class="left"><a href="#"-->
<!--                              style="width:90%; display:block; font-size:12px; text-decoration: none; text-align:center; color:#000; background-color:#4FA950; border:2px solid #4FA950; padding: 10px 1px; font-weight:bold;"-->
<!--                              id="email">--><?php //echo $this->lang->line("email"); ?><!--</a></span>-->
        <span><button type="button" onClick="window.print();return false;"
                                    style="width:100%; cursor:pointer; font-size:12px; background-color:#FFA93C; color:#000; text-align: center; border:1px solid #FFA93C; padding: 10px 1px; font-weight:bold;">Print</button></span>

        <div style="clear:both;"></div>
        <div style="clear:both;"></div>
    </div>

</div>

</body>
</html>
