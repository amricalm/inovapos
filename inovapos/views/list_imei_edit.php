<style type="text/css">
a { text-decoration:underline; }
</style>
<div style="clear: both"></div>
<div class="grid_12">
    <div class="module">
    	<h2><span>Daftar Barang</span></h2>
    	<!--<form action="#" onsubmit="return tambahbaris()">-->
            <div style="padding: 5px;">
                <input type="text" name="tambahimei" id="tambahimei" class="input-medium" />
                <input type="button" value="Tambah" id="tomboltambah" class="submit-green" />
            </div>
        <!--</form>-->
        <div class="module-table-body">
            <table id="myTable" class="tablesorter">
                <tr>
                    <th style="width:2%;text-align:center;">#</th>
                    <th>IMEI</th>
                    <th style="width:2%;"><!--<a href="javascript:tambah_baris()" title="Tambah Baris"><img src="<?php echo $base_img; ?>/plus-icon.png" /></a>--></th>
                </tr>
                <?php
                    echo form_hidden('saldo_barang',$this->uri->segment(4)).form_hidden('saldo_tgl',$this->uri->segment(3));
                    $seq = 1;
                    if($data->num_rows() > 0)
                    {
                        foreach($data->result() as $rowbarang)
                        {
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq; ?></td>
                        <td><?php echo form_input('imei'.$seq,$rowbarang->imei_no,'id="imei'.$seq.'" class="imei" disabled="disabled"  '); ?><img src="" id="gambar<?php echo $seq; ?>" /></td>
                        <td><img src="<?php echo $base_img.'/remove-icon.png' ?>" id="hapus_baris<?php echo $seq; ?>" /></td>
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
                        <td><?php echo form_input('imei'.$seq,'','id="imei'.$seq.'" class="imei" disabled="disabled" '); ?><img src="" id="gambar<?php echo $seq; ?>" /></td>
                        <td><img src="<?php echo $base_img.'/remove-icon.png' ?>" id="hapus_baris1" /></td>
                    </tr>
                    <?php
                    }
                ?>
            </table>
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
         <div style="padding: 5px;text-align:center;text-decoration: none;"><a href="" class="submit-green" id="keluar">Keluar</a></div>
    </div> <!-- End .module -->
    <?php echo $this->pagination->create_links(); ?>
</div>
<script type="text/javascript">
$(document).ready(function()
{
    $('#tambahimei').focus();
});
$(document).jkey('esc',function(){
    window.close();
});
$('#keluar').live('click',function(){
    window.close(); 
});
function keluargituloh()
{
    var barang = $('#saldo_barang').val();
    var tgl = $('#saldo_tgl').val();
    var elemen = '<?php echo $this->uri->segment(5) ?>';
    var shift = '<?php echo $this->uri->segment(6) ?>';
    var jmhimei =  0;
    var AoA = $('#myTable tr').map(function()
            {
                return [
                    $('td',this).map(function(){
                        return $(this).children().val();           
                    }).get()
                ];
            }).get(); 
    for(i=1;i<AoA.length;i++)
    {
        if(AoA[i][0]!='')
        { 
            jmhimei++;
        }   
    }
    window.opener.simpan(tgl,barang,jmhimei,elemen,shift);
}
$('img[id^=hapus]').live('click',function(){     
    var kdbarang = $('#saldo_barang').val();
    var tgl = $('#saldo_tgl').val();
    var id = $(this).attr('id');
    var posisi = id.substr(11,id.length);
    var imei = $('#imei'+posisi).val();
    $.post(
        '<?php echo base_url().'index.php/barang/hapus_imei' ?>',
        {saldo_tgl:tgl,saldo_barang:kdbarang,saldo_imei:imei},
        function(data)
        {
            var rinci = data.split('#');
            if(rinci[0]=='S')
            {
                $('#imei'+posisi).parents("tr").remove();
            }
            else
            {                   
                alert(data);
            }
        }
    );
});
$('#tomboltambah').live('click',function(){
    tmbhimei();
});
$('#tambahimei').jkey('return',function(){
    tmbhimei();
})
function tmbhimei()
{
    var imei = $('#tambahimei').val();
    if(imei=='')
    {
        alert("IMEI tidak boleh kosong!");
    }
    else
    {
        if(cekdouble(imei))
        {
        	var tbl = $('#myTable');
        	var lastRow = tbl.find("tr").length;
            var tdno = "<td>"+lastRow+"</td>";
            var tdkd = '<td><input type="text" name="imei'+lastRow+'" value="'+imei+'" id="imei'+lastRow+'" class="imei" disabled="disabled" /></td>';
            var tdnm = '<td><img src="<?php echo $base_img; ?>/remove-icon.png" id="hapus_baris" /></td>';
            tbl.children().append("<tr>"+tdno+tdkd+tdnm+"</tr>");
            var kdbarang = $('#saldo_barang').val();
            var tgl = $('#saldo_tgl').val();
            $.post(
                "<?php echo base_url().'index.php/barang/simpan_stok_imei' ?>",
                {saldo_tgl:tgl,saldo_barang:kdbarang,saldo_imei:imei},
                function(data)
                {
                    var rinci = data.split('#');
                    if(rinci[0]=='S')
                    {
                        if(rinci[1]!='Imei Ganda')
                        {
                            //window.close();
                        } 
                        else
                        {
                           alert('Imei Ganda!');
                        }
                    }
                    else
                    {                   
                        alert(rinci[1]);
                    }
                }
            );
            $('#tambahimei').val('');
            $('#tambahimei').focus();
        }
        else
        {
            $('#tambahimei').val('');
            $('#tambahimei').focus();
        }
    }
}
function cekdouble(imei)
{
    var error = '';
    var AoA = $('#myTable tr').map(function()
            {
                return [
                    $('td',this).map(function(){
                        return $(this).children().val();           
                    }).get()
                ];
            }).get(); 
    for(i=1;i<AoA.length;i++)
    {
        if(imei.trim() == AoA[i][0].trim())
        {
            error += '1'
        }
    }
    if(error=='')
    {
        return true;
    }
    else
    {
        alert("IMEI tidak boleh ada yang sama!");
        return false;
    }
}
//function hapus_baris(no)
//{        
//    var kdbarang = $('#saldo_barang').val();
//    var tgl = $('#saldo_tgl').val();
//    var imei = $('#imei'+no).val();
//    $.post(
//        '<?php echo base_url().'index.php/barang/hapus_imei' ?>',
//        {saldo_tgl:tgl,saldo_barang:kdbarang,saldo_imei:imei},
//        function(data)
//        {
//            var rinci = data.split('#');
//            if(rinci[0]=='S')
//            {
//                $('#imei'+no).parents("tr").remove();
//            }
//            else
//            {                   
//                alert(data);
//            }
//        }
//    );
//}
//$(document).ready(function()
//{    
//    $('.imei').jkey('return',function(key) {
//        simpan_baris($(this).val(),$(this));
//    });
//    $('.imei').focus();
//    function keluar()
//    {
//        var barang = '<?php echo $this->uri->segment(4); ?>';
//        var tgl = '<?php echo $this->uri->segment(3) ?>';
//        var elemen = '<?php echo $this->uri->segment(5) ?>';
//        var shift = '<?php echo $this->uri->segment(6) ?>';
//        var jmhimei = 0;
//        var imei = '';
//        $('.imei').each(function() {
//
//            if($(this).val()=='')
//            {
//                
//            }
//            else
//            {
//                jmhimei++;
//            }
//        });
//        var AoA = $('#myTable tr').map(function()
//                {
//                    return [
//                        $('td',this).map(function(){
//                            return $(this).children().val();           
//                        }).get()
//                    ];
//                }).get(); 
//        for(i=1;i<AoA.length;i++)
//        {
//            simpan_baris(AoA[1],'');
//        }
//        window.opener.simpan(tgl,barang,jmhimei,elemen,shift);
//        window.close();
//    }
//
//    window.onbeforeunload = keluar;
//    $('#keluar').on('click',function(){
//        keluar();
//    })
//
//});
//
//function tambah_baris()
//{
//	var tbl = $('#myTable');
//	var lastRow = tbl.find("tr").length;
//    var tdno = "<td>"+lastRow+"</td>";
//    var tdkd = '<td><input type="text" name="imei'+lastRow+'" value="" id="imei'+lastRow+'" class="imei" /><img src="" id="gambar'+lastRow+'" /></td>';
//    var tdnm = '<td><img src="<?php echo $base_img; ?>/remove-icon.png" onclick="hapus_baris('+"'"+lastRow+"'"+')" /></td>';
//    tbl.children().append("<tr>"+tdno+tdkd+tdnm+"</tr>");
//
//    $('.imei').jkey('return',function(key) {
//        //$(this).attr('disabled','disabled');
//        simpan_baris($(this).val(),$(this));
//
//    });
//}
//function simpan_baris(imei,sumber)
//{  
//    var no = $('#myTable').find("tr").length;
//    no=no-1;
//    var kdbarang = $('#saldo_barang').val();
//    var tgl = $('#saldo_tgl').val();
//    
//    $.post(
//        "<?php echo base_url().'index.php/barang/simpan_stok_imei' ?>",
//        {saldo_tgl:tgl,saldo_barang:kdbarang,saldo_imei:imei},
//        function(data)
//        {
//            var rinci = data.split('#');
//            if(rinci[0]=='S')
//            {
//                if(rinci[1]!='Imei Ganda')
//                {
//                    sumber.attr('disabled','disabled');
//                    $('#gambar'+no).attr('src','<?php echo $base_img; ?>/notification-tick.gif');
//                    tambah_baris();
//                    no=no+1;
//                    $('#imei'+no).focus();
//                } 
//                else
//                {
//                    alert('Imei Ganda!');
//                }
//            }
//            else
//            {                   
//                alert(data);
//            }
//        }
//    );
//}
//

//
</script>