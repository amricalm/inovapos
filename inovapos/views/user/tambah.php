<div class="module">
     <h2><span>Form Barang</span></h2>
     <div class="module-body">
        <form action="<?php echo base_url() ?>index.php/user/user_exec/tambah" method="POST" name="frmuser" id="frmuser" onsubmit="validasi()">     
            <p>
                <label>Kode User (akan digunakan ketika login, tidak boleh ada spasi)</label>
                <input type="text" name="user_kd" id="user_kd" class="input-long"/>
            </p>
            
            <p>
                <label>Nama User</label>
                <input type="text" name="user_nm" id="user_nm" class="input-long"/>
            </p>
             <p>
                <label>Group</label>
                <?php
                    $arraygrup = array(''=>'');
                    foreach($group->result() as $rowgrup)
                    {
                        $arraygrup[trim($rowgrup->group_kd)] = trim($rowgrup->group_nm);
                    }            
                    echo form_dropdown('user_group',$arraygrup,'','id="user_group" class="input-long"');
                ?>
            </p>   
            <p>
                <label>Password</label>
                <input type="password" name="user_password" data-typetoggle='#show-password' id="user_password" class="input-long" /><br />
                <small><input type="checkbox" id="show-password" /> Lihat Password</small>
            </p>
            <fieldset>
                <input class="submit-green" type="submit" value="Simpan" /> 
                <input class="submit-gray" type="reset" value="Batal" />
            </fieldset>
        </form>
     </div> <!-- End .module-body -->
</div>  <!-- End .module -->
<script type="text/javascript" src="<?php echo $base_js; ?>/jquery.showpassword.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){			
	$('#user_password').showPassword();
});
function validasi()
{
    var kd = $('#user_kd').val();
    var nm = $('#user_nm').val();
    var grup = $('#user_group').val();
    var pw = $('#user_password').val();
    if(kd=='' || nm=='' || pw=='' || grup=='')
    {
        alert("Silahkan isi dengan lengkap!");
        return false
    }
    else
    {
        return true;
    }
}
</script>