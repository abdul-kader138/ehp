<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $page_title . " &middot; " . SITE_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo base_url(); ?>assets/css/<?php echo THEME; ?>.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/select2.min.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script> var JS_DATE = '<?php echo JS_DATE; ?>';
        $(function () {
            $(".select_search").select2();
//            $('input.tip, select.tip').tooltip({placement: "right", trigger: "focus"});
//            $('.tip').tooltip();
//            $(".chzn-select").on("liszt:showing_dropdown", function () {
//                $(this).parents("div").css("overflow", "visible")
//            });
//            $(".chzn-select").on("liszt:hiding_dropdown", function () {
//                $(this).parents("div").css("overflow", "")
//            });
//            <?php //if(THEME=='rtl'){ ?>//$(".chzn-container, form select").addClass('chzn-rtl');
//            <?php //} ?>//$("form select").chosen({
//                no_results_text: "<?php //echo $this->lang->line('no_results_matched'); ?>//",
//                disable_search_threshold: 5,
//                allow_single_deselect: true
//            });
            $('#note').redactor({
                buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'image', 'video', 'link', '|', 'html'],
                formattingTags: ['p', 'pre', 'h3', 'h4'],
                imageUpload: '<?php echo site_url('module=home&view=image_upload'); ?>',
                imageUploadErrorCallback: function (json) {
                    bootbox.alert(json.error)
                },
                minHeight: 100
            });
            $('#internal_note').redactor({
                buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'image', 'video', 'link', '|', 'html'],
                formattingTags: ['p', 'pre', 'h3', 'h4'],
                imageUpload: '<?php echo site_url('module=home&view=image_upload'); ?>',
                imageUploadErrorCallback: function (json) {
                    bootbox.alert(json.error)
                },
                minHeight: 100,
                placeholder: '<?php echo $this->lang->line('internal_note'); ?>'
            });
            $('.redactor_toolbar a').tooltip({container: 'body'});
            $('.showSubMenus').click(function () {
                var sub_menu = $(this).attr('href');
                $('.sub-menu').slideUp('fast');
                $('.menu').find("b").removeClass('caret-up').addClass('caret');
                if ($(sub_menu).is(":hidden")) {
                    $(sub_menu).slideDown("slow");
                    $(this).find("b").removeClass('caret').addClass('caret-up')
                } else {
                    $(sub_menu).slideUp();
                    $(this).find("b").removeClass('caret-up').addClass('caret')
                }
                return false
            });
            $('.menu-collapse').click(function () {
                $('#col_1').slideToggle()
            })
        });
        $(window).resize(function () {
            if ($(document).width() > 980) {
                $('#col_1').show()
            }
        });
    </script>
    <!--[if lt IE 9]>
    <script src="<?php echo base_url(); ?>assets/js/html5shiv.js"></script>
    <![endif]-->
    <?php
    if (!$this->ion_auth->in_group(array('owner', 'admin','verify','purchaser','approver','salesman','viewer'))) {
        echo '<style>table tfoot { display: none !important; }</style>';
    }
    ?>
</head>

<body>
<div id="wrap">
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span
                class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
        <button type="button" class="btn btn-navbar menu-collapse"><span class="icon-bar"></span> <span
                class="icon-bar"></span> <span class="icon-bar"></span></button>
        <a class="brand" href="<?php echo base_url(); ?>"><img
                src="<?php echo base_url(); ?>assets/img/<?php echo LOGO; ?>" alt="<?php echo SITE_NAME; ?>"/></a>
        <ul class="hidden-desktop nav pull-right">
            <li><a class="hdate"> <?php echo date('l, F d, Y'); ?> </a></li>
        </ul>
        <div class="nav-collapse collapse">
            <ul class="nav pull-right">
                <li class="dropdown"><a href="#" class="dropdown-toggle"
                                        data-toggle="dropdown">Hi, <?php echo FIRST_NAME; ?>! <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo base_url(); ?>index.php?module=auth&amp;view=change_password"><?php echo $this->lang->line('change_password'); ?></a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php?module=auth&amp;view=logout"><?php echo $this->lang->line('logout'); ?></a>
                        </li>
                    </ul>
                </li>
                <li class="divider-vertical"></li>
            </ul>
            <ul class="nav pull-right">
                <li class="visible-desktop"><a class="hdate"><?php echo date('l, j F Y'); ?></a></li>
                <li><a href="index.php?module=home"><i
                            class="icon-home icon-white"></i> <?php echo $this->lang->line('home'); ?></a></li>
                <li><a href="index.php?module=calendar"><i
                            class="icon-calendar icon-white"></i>  <?php echo $this->lang->line('calendar'); ?></a></li>
                <?php if (UP_EVENTS) { ?>
                    <li class="dropdown dropdown-big"><a class="dropdown-toggle" href="#" data-toggle="dropdown"> <i
                                class="icon-list icon-white"></i> <?php echo $this->lang->line('upcoming_events'); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <?php echo UP_EVENTS; ?>
                        </ul>
                    </li>
                <?php } ?>

                <?php if ($this->ion_auth->in_group(array('owner', 'admin'))) { ?>
                    <li id="eann"><a href="#myModal" role="button" data-toggle="modal"><i
                                class="icon-edit icon-white"></i>  <?php echo $this->lang->line('edit_ann'); ?></a></li>
                <?php } ?>
                <li class="divider-vertical"></li>
            </ul>
        </div>
    </div>
</div>
<div id="col_1">

<!--  Start Owner & Admin permission section-->

<div id="mainmanu">
<?php if ($this->ion_auth->in_group(array('owner', 'admin'))) { ?>
    <ul class="menu nav nav-tabs nav-stacked">
<!--    <li class="dropdown"><a class="showSubMenus" href="#productsMenu"><i-->
<!--                class="icon-barcode icon-white"></i> --><?php //echo $this->lang->line('products'); ?><!-- <b-->
<!--                class="caret"></b></a>-->
<!--        <ul class="nav nav-tabs nav-stacked sub-menu" id="productsMenu">-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=products">--><?php //echo $this->lang->line('list_products'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=products&amp;view=add">--><?php //echo $this->lang->line('new_product'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=products&amp;view=sheet">--><?php //echo $this->lang->line('print_barcodes'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=products&amp;view=labels">--><?php //echo $this->lang->line('print_labels'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=products&amp;view=upload_csv">--><?php //echo $this->lang->line('add_by_csv'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=products&amp;view=update_price">--><?php //echo $this->lang->line('update_price'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=products&amp;view=update_warehouse_qty">--><?php //echo $this->lang->line('update_warehouse_qty'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=products&amp;view=damage_products">--><?php //echo $this->lang->line('damage_products'); ?><!--</a>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </li>-->
<!---->


<!--    <li class="dropdown"><a class="showSubMenus" href="#transfersMenu"><i-->
<!--                class="icon-random icon-white"></i> Inventories <b class="caret"></b></a>-->
<!--        <ul class="nav nav-tabs nav-stacked sub-menu" id="transfersMenu">-->
<!--            <li><a href="--><?php //echo base_url(); ?><!--index.php?module=reports&view=custom_products">Current-->
<!--                    Stock</a></li>-->
<!--            <li><a href="--><?php //echo base_url(); ?><!--index.php?module=reports&view=opening_stock">Opening &-->
<!--                    Closing Stock</a></li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=transfers">--><?php //echo $this->lang->line('list_transfers'); ?><!--</a>-->
<!--            </li>-->

<!---->
<!---->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=transfers&amp;view=add">--><?php //echo $this->lang->line('transfer_products'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=transfers&amp;view=transfer_csv">--><?php //echo $this->lang->line('transfer_products_by_csv'); ?><!--</a>-->
<!--            </li>-->
<!--            <li><a href="--><?php //echo base_url(); ?><!--index.php?module=products&view=view_adjustment">Adjustment-->
<!--                    Quantity</a></li>-->
<!--            <li><a href="--><?php //echo base_url(); ?><!--index.php?module=inventories&view=add_quantity">Count-->
<!--                    Quantity</a></li>-->
<!--            <li><a href="--><?php //echo base_url(); ?><!--index.php?module=reports&view=quantity_variance">Count-->
<!--                    Variance Quantity</a></li>-->
<!--        </ul>-->
<!--    </li>-->


<!--    <li class="dropdown"><a class="showSubMenus" href="#purchasesMenu"><i-->
<!--                class="icon-star icon-white"></i> --><?php //echo $this->lang->line('purchases'); ?><!-- <b-->
<!--                class="caret"></b></a>-->
<!--        <ul class="nav nav-tabs nav-stacked sub-menu" id="purchasesMenu">-->
<!--            <li><a href="--><?php //echo base_url(); ?><!--index.php?module=inventories">List Purchases-->
<!--                    Requisition</a></li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=inventories&amp;view=add">--><?php //echo $this->lang->line('add_purchase'); ?><!--</a>-->
<!--            </li>-->
<!--            <li><a href="--><?php //echo base_url(); ?><!--index.php?module=inventories&view=po_content">Purchases-->
<!--                    Order</a></li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=inventories&amp;view=csv_inventory">--><?php //echo $this->lang->line('csv_inventory'); ?><!--</a>-->
<!--            </li>-->
<!--            <li><a href="--><?php //echo base_url(); ?><!--index.php?module=inventories&amp;view=mrr_list">List Of-->
<!--                    MRR</a></li>-->
<!--        </ul>-->
<!--    </li>-->

<!--    <li class="dropdown"><a class="showSubMenus" href="#salesMenu"><i-->
<!--                class="icon-heart  icon-white"></i> --><?php //echo $this->lang->line('sales'); ?><!-- <b-->
<!--                class="caret"></b></a>-->
<!--        <ul class="nav nav-tabs nav-stacked sub-menu" id="salesMenu">-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=sales">--><?php //echo $this->lang->line('list_invoices'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=sales&amp;view=add">--><?php //echo $this->lang->line('add_invoice'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=sales&amp;view=deliveries">--><?php //echo $this->lang->line('list_deliveries'); ?><!--</a>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </li>-->

<!--    <li class="dropdown"><a class="showSubMenus" href="#quotesMenu"><i-->
<!--                class="icon-exclamation-sign icon-white"></i> --><?php //echo $this->lang->line('quotes'); ?><!-- <b-->
<!--                class="caret"></b></a>-->
<!--        <ul class="nav nav-tabs nav-stacked sub-menu" id="quotesMenu">-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=quotes">--><?php //echo $this->lang->line('list_quotes'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=quotes&amp;view=add">--><?php //echo $this->lang->line('add_quote'); ?><!--</a>-->
<!--            </li>-->
<!--        </ul>-->

<!--    </li>-->
    <li class="dropdown"><a class="showSubMenus" href="#peopleMenu"><i
                class="icon-user  icon-white"></i> <?php echo $this->lang->line('people'); ?> <b
                class="caret"></b></a>
        <ul class="nav nav-tabs nav-stacked sub-menu" id="peopleMenu">
            <?php if ($this->ion_auth->in_group('owner')) { ?>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=auth&amp;view=users"><?php echo $this->lang->line('list_users'); ?></a>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=auth&amp;view=create_user"><?php echo $this->lang->line('new_user'); ?></a>
                </li>
                <li class="divider"></li>
            <?php } ?>

<!--            need to omit later-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=billers">--><?php //echo $this->lang->line('list_billers'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=billers&amp;view=add">--><?php //echo $this->lang->line('new_biller'); ?><!--</a>-->
<!--            </li>-->
<!--            <li class="divider"></li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=suppliers">--><?php //echo $this->lang->line('list_suppliers'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=suppliers&amp;view=add">--><?php //echo $this->lang->line('new_supplier'); ?><!--</a>-->
<!--            </li>-->



            <!--            need to omit later-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=suppliers&amp;view=add_by_csv">--><?php //echo $this->lang->line('add_suppliers_by_csv'); ?><!--</a>-->
<!--            </li>-->
<!--            <li class="divider"></li>-->
<!---->
<!---->
            <li>
                <a href="<?php echo base_url(); ?>index.php?module=customers"><?php echo $this->lang->line('vendor'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php?module=customers&amp;view=add"><?php echo $this->lang->line('new_vendor'); ?></a>
            </li>
            <li class="divider"></li>

            <li>
                <a href="<?php echo base_url(); ?>index.php?module=clients&amp;view=types"><?php echo $this->lang->line('list_type'); ?></a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="<?php echo base_url(); ?>index.php?module=clients&amp;view=add_type"><?php echo $this->lang->line('new_type'); ?></a>
            </li>
            <li class="divider"></li>

            <li>
                <a href="<?php echo base_url(); ?>index.php?module=clients"><?php echo $this->lang->line('clients'); ?></a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="<?php echo base_url(); ?>index.php?module=clients&amp;view=add"><?php echo $this->lang->line('new_client'); ?></a>
            </li>
            <li class="divider"></li>
            <li>
                <a href="<?php echo base_url(); ?>index.php?module=clients&amp;view=client_intake"><?php echo $this->lang->line('intake'); ?></a>
            </li>
            <li class="divider"></li>
            <!--            need to omit later-->

<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=customers&amp;view=add_by_csv">--><?php //echo $this->lang->line('add_customers_by_csv'); ?><!--</a>-->
<!--            </li>-->
        </ul>
    </li>
    <?php if ($this->ion_auth->in_group('owner')) { ?>
        <li class="dropdown"><a class="showSubMenus" href="#settingsMenu"><i
                    class="icon-cog  icon-white"></i> <?php echo $this->lang->line('settings'); ?> <b
                    class="caret"></b></a>
            <ul class="nav nav-tabs nav-stacked sub-menu" id="settingsMenu">
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=system_setting"><?php echo $this->lang->line('system_setting'); ?></a>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=change_logo"><?php echo $this->lang->line('chnage_logo'); ?></a>
                </li>
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=settings&amp;view=upload_biller_logo">--><?php //echo $this->lang->line('upload_biller_logo'); ?><!--</a>-->
<!--                </li>-->
                <li class="divider"></li>

<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=shelfs">--><?php //echo $this->lang->line('list_shelfs'); ?><!--</a>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=shelfs&amp;view=add">--><?php //echo $this->lang->line('new_shelf'); ?><!--</a>-->
<!--                </li>-->
<!--                <li class="divider"></li>-->




<!--         abdul Kader       -->

                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=rooms"><?php echo $this->lang->line('list_room'); ?></a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=rooms&amp;view=add"><?php echo $this->lang->line('new_room'); ?></a>
                </li>
                <li class="divider"></li>

                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=level"><?php echo $this->lang->line('list_level'); ?></a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=level&amp;view=add"><?php echo $this->lang->line('new_level'); ?></a>
                </li>
                <li class="divider"></li>

                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=buildings"><?php echo $this->lang->line('list_buildings'); ?></a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=buildings&amp;view=add"><?php echo $this->lang->line('new_buildings'); ?></a>
                </li>
                <li class="divider"></li>


                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=buildings&amp;view=building_details"><?php echo $this->lang->line('list_level_buildings'); ?></a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=buildings&amp;view=add_building_details"><?php echo $this->lang->line('new_level_buildings'); ?></a>
                </li>
                <li class="divider"></li>

                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=buildings&amp;view=add_building_allocation"><?php echo $this->lang->line('building_allocation'); ?></a>
                </li>
                <li class="divider"></li>

                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=buildings&amp;view=building_allocation"><?php echo $this->lang->line('list_building_allocation'); ?></a>
                </li>
                <li class="divider"></li>

<!--   -->



<!---->
<!--                <li><a href="--><?php //echo base_url(); ?><!--index.php?module=shelfs&view=racks">Rack List</a></li>-->
<!--                <li><a href="--><?php //echo base_url(); ?><!--index.php?module=shelfs&view=add_rack">Add New-->
<!--                        Rack</a></li>-->
<!--                <li class="divider"></li>-->
<!---->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=categories">--><?php //echo $this->lang->line('list_categories'); ?><!--</a>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=categories&amp;view=add">--><?php //echo $this->lang->line('new_category'); ?><!--</a>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=categories&view=subcategories">--><?php //echo $this->lang->line('list_subcategories'); ?><!--</a>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=categories&amp;view=add_subcategory">--><?php //echo $this->lang->line('new_subcategory'); ?><!--</a>-->
<!--                </li>-->
<!---->
<!---->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=categories&amp;view=subsubcategories">List-->
<!--                        Of Sub Subcategory</a></li>-->
<!---->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=categories&amp;view=add_subsubcategory">Add-->
<!--                        Sub Subcategory</a></li>-->
<!---->
<!---->
<!--                <li class="divider"></li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=settings&amp;view=warehouses">--><?php //echo $this->lang->line('warehouses'); ?><!--</a>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=settings&amp;view=add_warehouse">--><?php //echo $this->lang->line('add_warehouse'); ?><!--</a>-->
<!--                </li>-->
<!--                <li class="divider"></li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=settings&amp;view=tax_rates">--><?php //echo $this->lang->line('tax_rates'); ?><!--</a>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=settings&amp;view=add_tax_rate">--><?php //echo $this->lang->line('add_tax_rate'); ?><!--</a>-->
<!--                </li>-->
<!--                <li class="divider"></li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=settings&amp;view=discounts">--><?php //echo $this->lang->line('discounts'); ?><!--</a>-->
<!--                </li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=settings&amp;view=add_discount">--><?php //echo $this->lang->line('add_discount'); ?><!--</a>-->
<!--                </li>-->
<!--                <li class="divider"></li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=settings&amp;view=package">--><?php //echo $this->lang->line('packages_list'); ?><!--</a>-->
<!--                </li>-->
<!--                <li class="divider"></li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=settings&amp;view=add_pck">--><?php //echo $this->lang->line('add_packages'); ?><!--</a>-->
<!--                </li>-->
<!--                <li class="divider"></li>-->
<!--                <li>-->
<!--                    <a href="--><?php //echo base_url(); ?><!--index.php?module=settings&amp;view=backup_database">--><?php //echo $this->lang->line('backup_database'); ?><!--</a>-->
<!--                </li>-->
<!---->

            </ul>
        </li>
    <?php } ?>

    <li class="dropdown"><a class="showSubMenus" href="#reportsMenu"><i
                class="icon-tasks  icon-white"></i> <?php echo $this->lang->line('reports'); ?> <b
                class="caret"></b></a>
        <ul class="nav nav-tabs nav-stacked sub-menu" id="reportsMenu">
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=reports&view=overview">--><?php //echo $this->lang->line('overview_chart'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=reports&view=warehouse_stock">--><?php //echo $this->lang->line('warehouse_stock_value'); ?><!--</a>-->
<!--            </li>-->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=reports&view=products">--><?php //echo $this->lang->line('product_alerts'); ?><!--</a>-->
<!--            </li>-->
<!---->
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=reports&view=purchases">--><?php //echo $this->lang->line('purchase_report'); ?><!--</a>-->
<!--            </li>-->
            <li>
                <a href="<?php echo base_url(); ?>index.php?module=reports&view=daily_sales"><?php echo $this->lang->line('daily_sales'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php?module=reports&view=monthly_sales"><?php echo $this->lang->line('monthly_sales'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php?module=reports&view=sales"><?php echo $this->lang->line('sales_report'); ?></a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>index.php?module=reports&view=customer_sales">Sales Report (Customer Wise)</a>
            </li>
<!--            <li>-->
<!--                <a href="--><?php //echo base_url(); ?><!--index.php?module=reports&view=pos_sales_report">Sales Report (POS)</a>-->
<!--            </li>-->
        </ul>
    </li>
    </ul>


<?php } ?>

<!--  End Owner & Admin permission section-->


<!--  Start salesman,purchaser,approver,checker,verify permission section-->

<?php if ($this->ion_auth->in_group(array('salesman', 'purchaser', 'approver', 'checker', 'verify'))) { ?>
    <ul class="menu nav nav-tabs nav-stacked">
        <li class="dropdown"><a class="showSubMenus" href="#userMenu"><i
                    class="icon-tasks  icon-white"></i> <?php echo $this->lang->line('menus'); ?> <b
                    class="caret"></b></a>
            <ul class="nav nav-tabs nav-stacked sub-menu" id="userMenu" style="display:block;">
                <?php if ($this->ion_auth->in_group(array('purchaser', 'approver', 'checker', 'verify'))) { ?>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=products"><?php echo $this->lang->line('list_products'); ?></a>
                    </li>
                    <?php if ($this->ion_auth->in_group(array('purchaser', 'checker'))) { ?>

                        <li>
                            <a href="<?php echo base_url(); ?>index.php?module=customers"><?php echo $this->lang->line('list_customers'); ?></a>
                        </li>
                        <li><a href="<?php echo base_url(); ?>index.php?module=inventories">List Purchases
                                Requisition</a></li>
                        <li>
                        <li>
                            <a href="<?php echo base_url(); ?>index.php?module=inventories&amp;view=add"><?php echo $this->lang->line('add_purchase'); ?></a>
                        </li>
                    <?php } ?>

                    <li><a href="<?php echo base_url(); ?>index.php?module=inventories&view=po_content">Purchases
                            Order</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php?module=inventories&amp;view=mrr_list">List
                            Of MRR</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=custom_products">Current
                            Stock Report</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&view=opening_stock">Opening
                            & Closing Stock Report</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&amp;view=quantity_variance">Count
                            Variance Quantity Report</a></li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=reports&view=sales"><?php echo $this->lang->line('sales_report'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=reports&view=customer_sales">Sales Report (Customer Wise)</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=reports&view=daily_sales"><?php echo $this->lang->line('daily_sales'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=reports&view=purchases"><?php echo $this->lang->line('purchase_report'); ?></a>
                    </li>


                <?php } ?>

                <?php if ($this->ion_auth->in_group('salesman')) { ?>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=products"><?php echo $this->lang->line('list_products'); ?></a>
                    </li>
                    <li><a href="<?php echo base_url(); ?>index.php?module=inventories">List Purchases
                            Requisition</a></li>
                    <li>

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=inventories&amp;view=add"><?php echo $this->lang->line('add_purchase'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=sales"><?php echo $this->lang->line('list_invoices'); ?></a>
                    </li>

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=customers"><?php echo $this->lang->line('list_customers'); ?></a>
                    </li>

                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&amp;view=custom_products">Current
                            Stock Report</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&amp;view=opening_stock">Opening
                            & Closing Stock Report</a></li>
                    <li><a href="<?php echo base_url(); ?>index.php?module=reports&amp;view=quantity_variance">Count
                            Variance Quantity Report</a></li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=reports&view=sales"><?php echo $this->lang->line('sales_report'); ?></a>
                    </li>

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=reports&view=daily_sales"><?php echo $this->lang->line('daily_sales'); ?></a>
                    </li>

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=reports&view=customer_sales">Sales Report (Customer Wise)</a>
                    </li>

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=reports&view=purchases"><?php echo $this->lang->line('purchase_report'); ?></a>
                    </li>
                <?php } ?>
            </ul>
        </li>
    </ul>
<?php
}

//<!--  End salesman,purchaser,approver,checker,verify permission section-->


//start view section

if ($this->ion_auth->in_group('viewer')) {
    ?>
    <ul class="menu nav nav-tabs nav-stacked">
        <li class="dropdown"><a class="showSubMenus" href="#userMenu"><i
                    class="icon-tasks  icon-white"></i> <?php echo $this->lang->line('menus'); ?> <b
                    class="caret"></b></a>
            <ul class="nav nav-tabs nav-stacked sub-menu" id="userMenu" style="display:block;">
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=products"><?php echo $this->lang->line('list_products'); ?></a>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=sales">
                        <?php echo $this->lang->line('list_invoices'); ?></a>
                </li>
                <li><a href="<?php echo base_url(); ?>index.php?module=reports&amp;view=custom_products">Current
                        Stock Report</a></li>
                <li><a href="<?php echo base_url(); ?>index.php?module=reports&amp;view=opening_stock">Opening
                        & Closing Stock Report</a></li>
                <li><a href="<?php echo base_url(); ?>index.php?module=reports&amp;view=quantity_variance">Count
                        Variance Quantity Report</a></li>
                <li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=reports&view=sales"><?php echo $this->lang->line('sales_report'); ?></a>
                </li>

                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=reports&view=purchases"><?php echo $this->lang->line('purchase_report'); ?></a>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=settings&amp;view=backup_database"><?php echo $this->lang->line('backup_database'); ?></a>
                </li>
            </ul>
        </li>
    </ul>
<?php } ?>


<!--//End viewer section-->

</div>
</div>
<div id="contenitore_col_2">
    <div id="col_2">
        <div class="main-content">
            <div class="row-fluid">
