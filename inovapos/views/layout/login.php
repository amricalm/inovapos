<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>
      Inovapos | Login
  </title>
  <link rel="shortcut icon" href="<?php echo $base_img; ?>/cart-icon.png">
  <link href="<?php echo $base_css; ?>/login/css.css" rel="stylesheet" type="text/css">
  <link href="<?php echo $base_css; ?>/login/login.css" media="all" rel="stylesheet" type="text/css">
  <script type="text/javascript">
  function setfks()
  {
    document.getElementById('username').focus();
  }
  $('#username').focus();
  </script>
</head>
<body onload="setfks()">
	<div class="container">
		<div class="header">
			<h1 class="welcome">Login ke inovapos v.<?php echo $versi; ?></h1>
		</div>
		<form class="form" action="<?php echo base_url(); ?>index.php/login/submit" method="post">
		Pengguna<br/>
		<input name="username" id="username" style="width: 310px;" type="text" autocomplete="off"/><br/>
		Password<br/>
		<input name="password" style="width: 310px;" type="password"/><br/>
        <!--Shift : <?php echo form_radio('shift',1,true).'&nbsp;Satu&nbsp;'.form_radio('shift',2,false).'&nbsp;Dua&nbsp;'; ?><br />-->
		<input class="submit" value="Masuk" type="submit"/><br/>
		<div style="height:10px;"></div>
		</form>
	</div>

</body>
</html>