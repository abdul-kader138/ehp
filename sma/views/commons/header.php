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
    if (!$this->ion_auth->in_group(array('owner', 'admin', 'verify', 'purchaser', 'approver', 'salesman', 'viewer'))) {
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
                    <li class="divider"></li>


                    <!--         abdul Kader       -->

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=rooms&amp;view=add"><?php echo $this->lang->line('new_room'); ?></a>
                    </li>
                    <li class="divider"></li>

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=level&amp;view=add"><?php echo $this->lang->line('new_level'); ?></a>
                    </li>
                    <li class="divider"></li>

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=buildings&amp;view=add"><?php echo $this->lang->line('new_buildings'); ?></a>
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


                </ul>
            </li>


            <!--        location Info-->

            <li class="dropdown"><a class="showSubMenus" href="#locationMenu"><i
                        class="icon-info-sign  icon-white"></i> <?php echo $this->lang->line('location_info'); ?> <b
                        class="caret"></b></a>
                <ul class="nav nav-tabs nav-stacked sub-menu" id="locationMenu">

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=rooms"><?php echo $this->lang->line('list_room'); ?></a>
                    </li>
                    <li class="divider"></li>

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=level"><?php echo $this->lang->line('list_level'); ?></a>
                    </li>
                    <li class="divider"></li>

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=buildings"><?php echo $this->lang->line('list_buildings'); ?></a>
                    </li>
                    <li class="divider"></li>

                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=buildings&amp;view=building_details"><?php echo $this->lang->line('list_level_buildings'); ?></a>
                    </li>
                    <li class="divider"></li>


                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=buildings&amp;view=building_allocation"><?php echo $this->lang->line('list_building_allocation'); ?></a>
                    </li>
                    <li class="divider"></li>
                </ul>
            </li>



            <!--        Client Intake-->

            <li class="dropdown"><a class="showSubMenus" href="#intakeMenu"><i
                        class="icon-check  icon-white"></i> <?php echo $this->lang->line('intake'); ?> <b
                        class="caret"></b></a>
                <ul class="nav nav-tabs nav-stacked sub-menu" id="intakeMenu">
                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=clients&amp;view=client_intake"><?php echo $this->lang->line('intake'); ?></a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php?module=clients&amp;view=intake_list"><?php echo $this->lang->line('list_intake'); ?></a>
                    </li>
                    <li class="divider"></li>
                </ul>
            </li>


        <?php } ?>

        <li class="dropdown"><a class="showSubMenus" href="#reportsMenu"><i
                    class="icon-align-justify  icon-white"></i> <?php echo $this->lang->line('reports'); ?> <b
                    class="caret"></b></a>
            <ul class="nav nav-tabs nav-stacked sub-menu" id="reportsMenu">

                <li>
                    <a href="<?php echo base_url(); ?>index.php?module=reports&view=building_facilities"><?php echo $this->lang->line('building_facilities_reports'); ?></a>
                </li>
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
                    <?php if ($this->ion_auth->in_group(array('purchaser', 'checker'))) { ?>

                    <?php } ?>

                <?php } ?>

                <?php if ($this->ion_auth->in_group('salesman')) { ?>
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
