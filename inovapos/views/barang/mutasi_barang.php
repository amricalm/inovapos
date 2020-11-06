<div class="grid_12">
    <h1><ins>Mutasi Barang</ins></h1>
    <hr />
    <form action="<?php echo base_url() ?>index.php/sinkronisasi/simpan_mutasi" method="POST" onsubmit="return valid()" id="frmopname">
        <table width="100%">
            <!--<tr>
                <td width="15%">No Faktur</td>
                <td><?php echo form_input('no_faktur','','id="no_faktur" class="input-short"'); ?></td>
            </tr>-->
            <tr>
                <td width="15%">Gudang/Outlet Tujuan</td>
                <td>
                    <?php 
                        $gudangoutlet = array(''=>'');
                        foreach($outlet->result() as $rowoutlet)
                        {
                            $gudangoutlet[$rowoutlet->outlet_kd]   =  $rowoutlet->outlet_kd. '&nbsp;-&nbsp;'.$rowoutlet->outlet_nm.'';
                        }
                        echo form_dropdown('outlet',$gudangoutlet,'','id="outlet" class="input-short"') 
                    ?>
                </td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td><?php echo form_input('tgl',$this->session->userdata('tanggal').' '.date('H:i:s'),'id="tgl" class="tgl input-short"'); ?></td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Keterangan</td>
                <td><?php echo form_textarea('keterangan','','id="keterangan" class="input-short" style="height:35px"'); ?></td>
            </tr>
        </table>
        <div class="module">
        	<h2><span>Daftar Barang</span></h2>
            <div class="module-table-body">
                <table id="myTable" class="tablesorter">
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:20%">Kode Barang</th>
                        <th style="width:40%">Nama Barang</th>
                        <th style="width: 10%;">Stok</th>
                        <th style="width:15%">Qty</th>
                        <th style="text-align: center;vertical-align:middle;width:2%;"><a href="javascript:tambah_baris()" title="Tambah Baris"><img src="<?php echo $base_img; ?>/plus-icon.png" /></a></th>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">1</td>
                        <td style="vertical-align: top;"><?php echo form_input('barang_kd[]','','id="barang_kd1" class="barang_kd input-short" style="width:80%"'); ?> <a href="javascript:lookup('barang_kd1')"><img src="<?php echo $base_img; ?>/notification-information.gif" title="Klik disini untuk lookup Tabel Master Barang" /></a></td>
                        <td style="vertical-align: top;"><?php echo form_input('barang_nm1','','id="barang_nm1" class="input-short" style="width:95%" disabled="disabled"'); ?><input type="hidden" name="barang_imei1" id="barang_imei1" value="" /></td>
                        <td style="vertical-align: top;"><?php echo form_input('barang_stok1','','id="barang_stok1" class="input-short" style="width:95%;text-align:right" disabled="disabled"'); ?></td>
                        <td style="vertical-align: top;"><?php echo form_input('qty1','','id="qty1" class="input-short" style="width:95%;text-align:right"'); ?></td>
                        <td style="text-align: center;vertical-align:middle"><!--<a href="javascript:hapus_baris('1')" title="Hapus Baris"><img src="<?php echo $base_img; ?>/remove-icon.png" /></a>--></td>
                    </tr>
                </table>
                <div class="table-apply" style="padding: 10px;">
                    <div>
                        <input class="submit-green" type="submit" value="Simpan" />
                        <input class="submit-gray" type="reset" value="Reset" />
                    </div>
                </div>
                </form>
                <div style="clear: both"></div>
             </div> <!-- End .module-table-body -->
        </div> <!-- End .module -->
    <?php echo $this->pagination->create_links(); ?>
    </form>
</div>
<script type="text/javascript">
function lookup(id)
{
    var aidi = id.substring(9);
    mywindow = window.open("<?php echo base_url() ?>index.php/barang/list_barang/"+aidi,"test","scrollbars=yes,width=650,height=400");
}
function tambah_baris()
{
    var error = '';
    var AoA = $('#myTable tr').map(
            function()
            {
                return [
                    $('td',this).map(
                    function()
                    {
                        return $(this).children().val();          
                    }).get()];
            }).get(); 
    for(i=1;i<AoA.length;i++)
    {
        if(AoA[i][3]=='0' || AoA[i][3]=='')
        {
            error += "Qty tidak boleh kosong!\n";
        }
        else
        {
            if(parseFloat(AoA[i][3]) > parseFloat(AoA[i][2]))
            {
                error += "Stok Kurang!\n";
            }
        }  
    }
    if(error!='')
    {
        alert(error);
    }
    else
    {
    	var tbl = $('#myTable');
    	var lastRow = tbl.find("tr").length;
        var tblno = lastRow;
        var tdno = "<td>"+lastRow+"</td>";
        var tdkd = '<td style="vertical-align: top;"><input type="text" name="barang_kd[]" value="" id="barang_kd'+lastRow+'" class="barang_kd input-short" style="width:80%" /> <a href="javascript:lookup('+"'barang_kd"+lastRow+"'"+')"><img src="<?php echo $base_img; ?>/notification-information.gif" title="Klik disini untuk lookup Tabel Master Barang" /></a></td>';
        var tdnm = '<td style="vertical-align: top;"><input type="text" name="barang_nm'+lastRow+'" value="" id="barang_nm'+lastRow+'" class="input-short" style="width:95%" disabled="disabled" /><input type="hidden" name="barang_imei'+lastRow+'"'+' id="barang_imei'+lastRow + '"' +' value="" /></td>';
        var tdstk = '<td style="vertical-align: top;"><input type="text" name="barang_stok'+lastRow+'" id="barang_stok'+lastRow+'" class="input-short" style="width:95%;text-align:right" value="" disabled="disabled"/></td>';
        var tdqty = '<td style="vertical-align: top;"><input type="text" name="qty'+lastRow+'" value="" id="qty'+lastRow+'" class="input-short" style="width:95%;text-align:right" /></td>';
        var tdtbl = '<td style="text-align: center;vertical-align:middle"><a href="javascript:hapus_baris('+"'"+lastRow+"'"+')" title="Hapus Baris"><img src="<?php echo base_url(); ?>/inovapos_asset/img/remove-icon.png" /></a></td>';
        tbl.children().append("<tr>"+tdno+tdkd+tdnm+tdstk+tdqty+tdtbl+"</tr>");
    }
    //return;
//    var sebelumnya = lastRow-1;
//    var kdbarang = $('#barang_kd'+sebelumnya).val();
//    var stoksebelumnya = parseInt($('#barang_stok'+sebelumnya).val());
//    var qtysebelumnya = parseInt($('#qty'+sebelumnya).val());
//    if(stoksebelumnya < qtysebelumnya)
//    {
//        alert("Stok Kurang");
//        $('#qty'+sebelumnya).focus();
//    }
//    else
//    {
//        if(kdbarang!='')
//        {
//            var tblno = lastRow;
//            var tdno = "<td>"+lastRow+"</td>";
//            var tdkd = '<td style="vertical-align: top;"><input type="text" name="barang_kd[]" value="" id="barang_kd'+lastRow+'" class="barang_kd input-short" style="width:80%" /> <a href="javascript:lookup('+"'barang_kd"+lastRow+"'"+')"><img src="<?php echo $base_img; ?>/notification-information.gif" title="Klik disini untuk lookup Tabel Master Barang" /></a></td>';
//            var tdnm = '<td style="vertical-align: top;"><input type="text" name="barang_nm'+lastRow+'" value="" id="barang_nm'+lastRow+'" class="input-short" style="width:95%" disabled="disabled" /><input type="hidden" name="barang_imei'+lastRow+'"'+' id="barang_imei'+lastRow + '"' +' value="" /></td>';
//            var tdstk = '<td style="vertical-align: top;"><input type="text" name="barang_stok'+lastRow+'" id="barang_stok'+lastRow+'" class="input-short" style="width:95%;text-align:right" value="" disabled="disabled"/></td>';
//            var tdqty = '<td style="vertical-align: top;"><input type="text" name="qty'+lastRow+'" value="" id="qty'+lastRow+'" class="input-short" style="width:95%;text-align:right" /></td>';
//            var tdtbl = '<td style="text-align: center;vertical-align:middle"><a href="javascript:hapus_baris('+"'"+lastRow+"'"+')" title="Hapus Baris"><img src="http://localhost/inovapos/inovapos_asset/img/remove-icon.png" /></a></td>';
//            tbl.children().append("<tr>"+tdno+tdkd+tdnm+tdstk+tdqty+tdtbl+"</tr>");
//        }
//    }
}
function cek_barang(kd)
{
	var tbl = $('#myTable');
	var lastRow = tbl.find("tr").length;
    var status = "";
	var test = "";
    if(lastRow > 1)
    {
        var AoA = $('#myTable tr').map(function()
                {
                    return [
                        $('td',this).map(function(){
                            return $(this).children().val();           
                        }).get()
                    ];
                }).get(); 
        //alert(AoA[1][1]);
        for(i=1;i<AoA.length;i++)
        {
            var id = AoA[i][0];
            if(kd==id)
            {
                status = 'Barang sudah masuk!';
            }
        }
    }
    return status;
}
function hapus_baris(no)
{        
    $('#barang_kd'+no).parents("tr").remove();
}
function valid()
{
    var hasil = false;
    var test = '';
    var kurang = '';
    var nofaktur = $('#no_faktur').val();
    var gudang = $('#outlet').val();
    if(nofaktur=='' || gudang=='')
    {
        alert('No Faktur dan Gudang tidak boleh kosong!');
        hasil = false;
    }
    else
    {
        var tbl = $('#myTable');
        var lastRow = tbl.find("tr").length;        
        var AoA = $('#myTable tr').map(function()
                {
                    return [
                        $('td',this).map(function(){
                            return $(this).children().val();           
                        }).get()
                    ];
                }).get(); 
        //alert(AoA[1][1]);
        for(i=1;i<AoA.length;i++)
        {
            var test = parseFloat(AoA[i][3]);
            var stok = parseFloat(AoA[i][2]);
            if(parseFloat(test) > parseFloat(stok))
            {
                kurang = 'Ada barang yang stoknya kurang!';
            }
        }
//        for(var i=0;i<lastRow;i++)
//        {
//            test = parseInt($('#qty'+i).val());
//            stok = parseInt($('#barang_stok'+i).val());
//            if(test > stok)
//            {
//                kurang = 'Ada barang yang stoknya kurang!';
//            }
//        }
        if(test=='' || test=='0')
        {
            alert('Barang tidak boleh kosong!');
            hasil = false;
        }
        else
        {
            if(kurang!='')
            {
                alert(kurang);
                hasil = false;
            }
            else
            {
                hasil = true;
            }
        }
    }
    return hasil;
}
function iclose()
{
    $.prettyPhoto.close();
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}
function cek_stok(no)
{
    stok = parseInt($('#barang_stok'+no).val());
    quantity = parseInt($('#qty'+no).val());
    if(stok < quantity)
    {
        alert("Stok Kurang!");
    }
}
</script>