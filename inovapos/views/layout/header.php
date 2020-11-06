<!DOCTYPE html>
<html>
	<head>
        <link rel="shortcut icon" href="<?php echo $base_img; ?>/cart-icon.png"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Inovapos <?php echo 'v.'.$versi; ?> <?php echo (!empty($judulweb)) ? $judulweb : ''; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $base_css;?>/reset.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo $base_css;?>/grid.css" media="screen" />
        <?php $grosir = $this->outlet_model->outlet_ambil($this->session->userdata('outlet_kd'))->row()->outlet_grosir; ?>
        <?php 
        if($grosir=='1')
        { 
            echo '<link rel="stylesheet" type="text/css" href="'.$base_css.'/styles_grosir.css" media="screen" />';
            echo '<link type="text/css" rel="stylesheet" href="'.$base_css.'/menu_grosir.css" />';
        } 
        else 
        { 
            echo '<link rel="stylesheet" type="text/css" href="'.$base_css.'/styles.css" media="screen" />';
            echo '<link type="text/css" rel="stylesheet" href="'.$base_css.'/menu.css" />';
        } 
        ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $base_css ?>/demo_table_jui.css" />
    	<link type="text/css" rel="stylesheet" href="<?php echo $base_css ?>/jquery-ui-1.8.17.custom.css" />
    	<link type="text/css" rel="stylesheet" href="<?php echo $base_css ?>/jquery.dataTables.css" />
    	<!--<link type="text/css" rel="stylesheet" href="<?php echo $base_css ?>/flexigrid.css" />-->
        <link type="text/css" rel="stylesheet" href="<?php echo $base_css ?>/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
        <style type="text/css">
            a { text-decoration: none;}
        </style>
    	<script src="<?php echo $base_js ?>/jquery-1.7.2.min.js"></script>
    	<script src="<?php echo $base_js ?>/jquery-ui-1.8.17.custom.min.js"></script>
    	<script src="<?php echo $base_js ?>/jquery.dataTables.min.js"></script>
    	<script src="<?php echo $base_js ?>/jquery.prettyPhoto.js"></script>
    	<script src="<?php echo $base_js ?>/jquery.jstepper.js"></script>
    	<script src="<?php echo $base_js ?>/jquery.keyz.js"></script>
    	<!--<script src="<?php echo $base_js ?>/flexigrid.js"></script>-->
    	<script src="<?php echo $base_js ?>/custom.js"></script>
        <script src="<?php echo $base_js ?>/autoNumeric-1.7.4.js"></script>
	</head>
	<body>
        <div id="header">
            <div id="header-status">
                <div class="container_12">
                    <div class="grid_8" style="vertical-align: middle;">
                       <table style="height:27px;">
                            <tr>
                                <td style="vertical-align: bottom;">
                                    <strong>USER : <font color="yellow"><?php echo $this->session->userdata('user_nm'); ?></font> | SHIFT : <font color="lightgreen"><?php echo $this->session->userdata('shift') ?></font> | OUTLET : <font color="white"><?php echo $this->session->userdata('outlet_nm') ?></font></strong>
                                </td>
                            </tr>
                       </table>
                    </div>
                    <div class="grid_4">
                        <a href="<?php echo base_url(); ?>index.php/login/logout" id="logout">Logout</a>
                    </div>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div id="header-main">
                <div class="container_12">
                    <div class="grid_12">
                        <div id="logo">
                            <div style="float: right;margin-top:25px"><?php echo 'v.'.$versi; ?></div>
                            <ul id="nav">
                                <?php
                                $menubesar          = $this->app_model->menu('besar');
                                for($i=1;$i<=count($menubesar);$i++)
                                {
                                    if($menubesar[$i]['nama']=="Transaksi" && $tutup_shift)
                                    {
                                        //nothing;
                                    }
                                    elseif($menubesar[$i]['nama']=="Stok" && $tutup_shift)
                                    {
                                        //nothing;
                                    }
                                    else
                                    {
                                        if(count($menubesar[$i]['detail'])==0)
                                        {
										    if($menubesar[$i]['nama']=='Pengguna')
                                            {
                                                if($this->session->userdata('user_group')=='System')
                                                {
											       echo '<li><a href="'.$menubesar[$i]['link'].'">'.$menubesar[$i]['nama'].'</a></li>';
                                                }
                                            }
                                            else
                                            {
                                                echo '<li><a href="'.$menubesar[$i]['link'].'" '.$menubesar[$i]['extra'].'>'.$menubesar[$i]['nama'].'</a></li>';
                                            }
                                        }
                                        else
                                        {
                                            echo '<li><a href="'.$menubesar[$i]['link'].'" '.$menubesar[$i]['extra'].'>'.$menubesar[$i]['nama'].'</a>';
                                            echo '<ul>';
                                            for($j=1;$j<=count($menubesar[$i]['detail']);$j++)
                                            {
												if($menubesar[$i]['nama']=='Stok' && $this->session->userdata('user_group')=='Kasir')
												{
													
												}
                                                else
												{
													echo '<li><a href="'.$menubesar[$i]['detail'][$j]['link'].'" '.$menubesar[$i]['detail'][$j]['extra'].'>'.$menubesar[$i]['detail'][$j]['nama'].'</a></li>';
												}
                                            }
                                            echo '</ul>';
                                            echo '</li>';
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
            <div style="clear: both;"></div>
            <div id="subnav">
                <div class="container_12">
                    <div class="grid_12">
                        <ul>
                            <?php
                            $menubesar          = $this->app_model->menu('kecil');
                            for($i=1;$i<=count($menubesar);$i++)
                            {
                                if($menubesar[$i]['nama']=="Teks Promosi" && $this->session->userdata('user_group')=="Kasir")
                                {
                                    //nothing;
                                }
                                else
                                {
                                    echo '<li><a href="'.$menubesar[$i]['link'].'">'.$menubesar[$i]['nama'].'</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
		<div class="container_12">