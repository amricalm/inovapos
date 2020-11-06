<!DOCTYPE html>
<html>
	<head>
        <link rel="shortcut icon" href="<?php echo $base_img; ?>/cart-icon.png"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Inovapos <?php echo (!empty($judulweb)) ? $judulweb : ''; ?></title>
        <link rel="stylesheet" type="text/css" href="<?php echo $base_css;?>/reset.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo $base_css;?>/grid.css" media="screen" >
        <link type="text/css" rel="stylesheet" href="<?php echo $base_css ?>/demo_table_jui.css" />
    	<link type="text/css" rel="stylesheet" href="<?php echo $base_css ?>/jquery-ui-1.8.17.custom.css" />
    	<link type="text/css" rel="stylesheet" href="<?php echo $base_css ?>/jquery.dataTables.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo $base_css ?>/prettyPhoto.css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="<?php echo $base_css;?>/styles.css" media="screen" />
        <style type="text/css">
            a { text-decoration: none;}
        </style>
    	<script src="<?php echo $base_js ?>/jquery-1.7.2.min.js"></script>
    	<script src="<?php echo $base_js ?>/jquery-ui-1.8.17.custom.min.js"></script>
    	<script src="<?php echo $base_js ?>/jquery.dataTables.min.js"></script>
    	<script src="<?php echo $base_js ?>/jquery.prettyPhoto.js"></script>
    	<script src="<?php echo $base_js ?>/jquery.keyz.js"></script>
    	<script src="<?php echo $base_js ?>/jquery.jstepper.js"></script>
    	<script src="<?php echo $base_js ?>/jquery.quicksearch.js"></script>
    	<script src="<?php echo $base_js ?>/custom.js"></script>
        <script src="<?php echo $base_js ?>/autoNumeric-1.7.4.js"></script>
	</head>
	<body <?php echo (substr($halaman,0,9) == 'list_imei') ? 'onbeforeunload="keluargituloh()"' : ''; ?>>
		<div class="container_12">