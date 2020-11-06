<div class="grid_12">
    <div class="module">
	<h2 style="text-align: center;"><span>Absen Karyawan</span></h2>
        <form class="form" action="<?php echo base_url(); ?>index.php/karyawan/absen_simpan" method="post">
            <table class="tablesorter">
                <tr>          
                    <td style="text-align:center;">

                        N I K<br/>
                        <input name="nik" id="nik" style="width: 310px;" type="text"/><br/>
                        Password<br/>
                        <input name="pwd" id="pwd" style="width: 310px;" type="password"/><br/>
                <!--Shift : <?php echo form_radio('shift',1,true).'&nbsp;Satu&nbsp;'.form_radio('shift',2,false).'&nbsp;Dua&nbsp;'; ?><br />-->

<!--                        <input class="submit" id="simpan" value="Masuk" type="button"/><br/>-->
                        <div style="height:10px;"></div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;"><input class="submit-green" type="button" id="simpan" value="Masuk" /></td>
                </tr>
            </table
        </form>
    </div> <!-- End .module -->
</div>
<script type="text/javascript">
$(document).ready(function(){
    document.getElementById('nik').focus();
    
    $("#simpan").click(function(){
        $.post("<?php echo base_url(); ?>index.php/karyawan/absen_simpan",
        {
          nik:$('#nik').val(),
          pwd:$('#pwd').val()
        },
        function(data,status){
            alert(data);
        });
      });

    });
    
</script>