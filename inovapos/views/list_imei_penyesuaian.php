<style type="text/css">
a { text-decoration:underline; }
</style>
<div style="clear: both"></div>
<div class="grid_12">
    <div class="module">
    	<h2><span>Daftar Imei</span></h2>
        <div class="module-table-body">
            <table id="myTable" class="tablesorter">
                <tr>
                    <th style="width:2%;text-align:center;">#</th>
                    <th>IMEI</th>
                    <th style="width:2%;"><a href="javascript:tambah_baris()" title="Tambah Baris"><img src="<?php echo $base_img; ?>/plus-icon.png" /></a></th>
                </tr>
                <?php
                    echo form_hidden('kdbarang',$this->uri->segment(4));
                    echo form_hidden('kdgroup',$this->barang_model->get($this->uri->segment(4),'','','','','')->row()->barang_group);
                    $seq = 1;
                    if($data->num_rows() > 0)
                    {
                        foreach($data->result() as $rowbarang)
                        {
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq; ?></td>
                        <td><?php echo form_input('imei'.$seq,$rowbarang->imei_no,'id="imei'.$seq.'" class="imei" '); ?><img src="" id="gambar<?php echo $seq; ?>" /></td>
                        <td><img src="<?php echo $base_img.'/remove-icon.png' ?>" style="cursor: pointer;" onclick="hapus_baris('<?php echo $seq ?>')" /></td>
                    </tr>
                    <?php 
                        $seq++;
                        }
                    }
                    else
                    {
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq;?></td>
                        <td><?php echo form_input('imei'.$seq,'','id="imei'.$seq.'" class="imei" '); ?><img src="" id="gambar<?php echo $seq; ?>" /></td>
                        <td><img src="<?php echo $base_img.'/remove-icon.png' ?>" style="cursor: pointer;" onclick="hapus_baris('<?php echo $seq ?>')" /></td>
                    </tr>
                    <?php
                    }
                ?>
            </table>
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
         <div style="padding: 5px;text-align:center;text-decoration: none;"><a href="" id="keluars" class="submit-green" id="keluar">Keluar</a></div>
    </div> <!-- End .module -->
    <?php echo $this->pagination->create_links(); ?>
</div>
<script type="text/javascript">
//$(window).unload( function () { alert("Bye now!"); } );
$(document).ready(function()
{
    $('.imei').jkey('return',function(key) {
        simpan_baris($(this).val(),$(this));
    });


    
    $('#keluars').on('click',function(){
        window.close();
    })

});
function keluargituloh()
{
    var group = $('#kdgroup').val();
    if(group=='10' || group=='60' || group=='70')
    { 
        window.opener.$('#gbr<?php echo $this->uri->segment(4); ?>').attr('src','<?php echo $base_img; ?>/Email.png');
    }
    else
    {
        window.opener.$('#gbr<?php echo $this->uri->segment(4); ?>').attr('src','<?php echo $base_img; ?>/save.png')
    }
    window.opener.segar();
}
function tambah_baris()
{
	var tbl = $('#myTable');
    var grup = $('kdgroup').val();
	var lastRow = tbl.find("tr").length;
    var tdno = "<td>"+lastRow+"</td>";
    var tdkd = '<td><input type="text" name="imei'+lastRow+'" value="" id="imei'+lastRow+'" class="imei" /><img src="" id="gambar'+lastRow+'" /></td>';
    var tdnm = '<td><img src="<?php echo $base_img; ?>/remove-icon.png" onclick="hapus_baris('+"'"+lastRow+"'"+')" /></td>';
    var lastRowsebelum = parseInt(lastRow) - 1;
    if($('#imei'+lastRowsebelum).val()=="")
    {
        alert("Isi Imei sebelumnya!");
    }
    else
    {
        tbl.children().append("<tr>"+tdno+tdkd+tdnm+"</tr>");
        $('#imei'+lastRow).focus();        
        $('.imei').jkey('return',function(key) {
            simpan_baris($(this).val(),$(this),grup);
        });
    }
}
function simpan_baris(imei,sumber,grup)
{   
    var no = $('#myTable').find("tr").length;
    no = no - 1;
    var kdbarang = $('#kdbarang').val();
    $.post(
        "<?php echo base_url().'index.php/barang/simpan_stok_penyesuaian_imei' ?>",
        {stok_kdbarang:kdbarang,stok_imei:imei,stok_kdgrup:grup},
        function(data)
        {
            var rinci = data.split('#');
            if(rinci[0]=='S')
            {
                if(rinci[1]!='Imei Ganda')
                {
                    sumber.attr('disabled','disabled');
                    $('#gambar'+no).attr('src','<?php echo $base_img; ?>/notification-tick.gif');
                    tambah_baris();
                    no = no + 1;
                    $('#imei'+no).focus();
                } 
                else
                {
                    alert('Imei Ganda!');
                }
            }
            else
            {                   
                alert(data);
            }
        }
    );
}

function hapus_baris(no)
{        
    var grup = $('#kdgroup').val();
    var kdbarang = $('#kdbarang').val();
    var imei = $('#imei'+no).val();
    //alert(imei);
    $.post(
        '<?php echo base_url().'index.php/barang/hapus_penyesuaian_imei' ?>',
        {stok_kdbarang:kdbarang,stok_imei:imei,stok_kdgrup:grup},
        function(data)
        {
            //alert('<?php echo base_url().'index.php/barang/hapus_penyesuaian_imei' ?>');
            //alert(data);
            var rinci = data.split('#');
            if(rinci[0]=='S')
            {
                $('#imei'+no).parents("tr").remove();
            }
            else
            {                   
                alert(data);
            }
        }
    );
}

</script>