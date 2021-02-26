<style type="text/css">
 #myTable tr td input{ border:none; width:95%;font-size:12px;background:none; }
 textarea.barangnm { border:none; width:95%; font-family:Arial;font-size:12px; height:50px; background:none; }
 .kiri { text-align:left; }
 .kanan { text-align:right; }
 .tengah { text-align:center; }
 td.atas { vertical-align:top; }
 td.bawah { vertical-align:bottom; }
</style>
<div id="hasil"></div>
<form action="<?php echo base_url() ?>index.php/kasir/simpan_elektrik/" method="POST" name="frmKasir" id="frmKasir">
    <div class="grid_12">
        <div class="float-right">
            <label style="color: red;font-size:15px; font-weight: bold">
                Kasir : <?php echo $this->session->userdata('user_nm'); ?>&nbsp;|&nbsp;
                <?php echo $this->adntgl->tgl_panjang($this->session->userdata('tanggal')); ?>&nbsp;|&nbsp;
                <span id="clock"></span>
            </label>
        </div>
        <p>
            <label>Total </label>
            <input type="text" name="total" value="0" id="total" class="input-long angka" style="font-size:80px;text-align:right;width:100%" />
        </p>
        </script>
        <div class="module">
        	<h2><span>Daftar Barang</span></h2>
            <div class="module-table-body" style="min-height: 250px;">

        <div class="scroll-pane horizontal-only" style="border:1px solid #999999;vertical-align:top">
            <p style="height: 250%;margin-top: 0px">
                <table id="myTable" class="tablesorter">
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:15%">Kode Barang</th>
                        <th style="width:34%">Nama Barang</th>
                        <th style="width:5%">Qty</th>
                        <th style="width:13%;text-align:right;">Harga</th>
                        <th style="width:13%;text-align:right;">Jumlah</th>
                        <th style="width:5%"></th>
                    </tr>
                </table>
            </p>
        </div>
                <div style="clear: both"></div>
             </div> <!-- End .module-table-body -->
        </div> <!-- End .module -->
        <div class="grid_12">       
                <table style="width:100%;padding:0">
                    <tr>
                        <td>NO. HP</td>
                        <td>
                            <input type="text" name="imei" id="imei" class="input-long" />
                        </td>
                        <td>Kode Barang</td>
                        <td>
                            <input type="text" name="barang_kd" id="barang_kd" class="input-long" />
                            <input type="hidden" name="barang_grup" id="barang_grup" class="input-long" />
                        </td>
                        <td>Nama Barang</td>
                        <td><input type="text" name="barang_nm" id="barang_nm" class="input-long" disabled="disabled" /></td>
                        <td>HPP</td>
                        <td><input type="text" name="hpokok" id="hpokok" class="input-long" disabled="disabled" /></td>
                    </tr>
                    <tr>    
                        <td>Harga</td>
                        <td><input type="text" name="barang_harga" id="barang_harga" class="input-long" disabled="disabled" /></td>
                        <td>Qty</td>
                        <td><input type="text" name="qty" id="qty" class="qty input-long " /></td>
                        <!--<td>Diskon</td>
                        <td><input type="text" name="diskon" id="diskon" class="qty100 input-long " />&nbsp;(%)</td>
                        <td><input type="checkbox" id="cbxPelanggan" name="cbxPelanggan" /></td>
                        <td>Pelanggan</td>
                        <td>Kode Pelanggan</td>
                        <td><input type="text" name="kdPelanggan" id="kdPelanggan" disabled="disabled" class="input-long " /></td>-->
                        <td colspan="4"><!--<input type="hidden" name="hpokok" id="hpokok" />--></td>
                    </tr>
                </table>
            <span class="notification none renggang">
                Tekan <span style="color: red;">F2</span> untuk Lookup Tabel.
                Tekan tanda <span style="color: red;">panah ke atas atau ke bawah</span>, untuk mengalihkan kursor.
                Tekan <span style="color: red;">F12</span> untuk membayar. 
                <?php $grosir = $this->outlet_model->outlet_ambil($this->session->userdata('outlet_kd'))->row()->outlet_grosir; ?>
                <?php if($grosir=='1') { ?>
                Tekan <span style="color: red;">F10</span> untuk cetak.
                <?php } ?>
            </span>
            <div style="text-align:center;position:fixed;right:0px;bottom:0px;width:100%;">
                <input type="button" value="Bayar" id="ButtonBayar" name="ButtonBayar" class="submit-green"/>
            </div>
        </div>
    </div>
    <!-- Dialog Pelanggan 
    <div id="pelanggan" title="Pilihan Pelanggan!" style="text-align: center;vertical-align:center;">
        <table>
            <tr>
                <td>
                    <?php echo form_radio('pelanggan','0',true,'id="pelanggan0"').' Non Pelanggan'; ?>
                    <?php echo form_radio('pelanggan','1',true,'id="pelanggan1"').' Pelanggan'; ?>
                </td>
            </tr>
        </table>
    </div>-->
    <!-- Dialog Proses -->
    <div id="proses" title="Sedang Proses, Mohon Tunggu!" style="text-align: center;vertical-align:center;">
        <img src="<?php echo $base_img.'/ajax-loader.gif'; ?>" />
    </div>
    <!-- Dialog Kasir -->
    <div id="form-bayar-elektrik" title="Kasir : <?php echo $this->session->userdata('user_nm') ?> | <?php echo $this->adntgl->tgl_panjang(date('Y-m-d')); ?>">
        <table style="width: 700px;">
            <tr style="line-height:25px;">
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Pembayaran </div>
                    <div style="float: right;"><?php echo form_radio('term','Tunai',true,'id="term_tunai"').'&nbsp;Tunai&nbsp;'.form_radio('term','Kredit',false,'id="term_kredit"').'&nbsp;Kredit&nbsp;'.form_radio('term','Debit',false,'id="term_debit" ').'&nbsp;Debit&nbsp;'; ?></div>
                </td>
                <td style="width: 50%;">&nbsp;</td>
            </tr>
            <tr style="line-height:25px;">
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Nomor Kartu</div>
                    <div style="float: right;"><?php echo form_input('nomor_kartu','','style="padding:3px;text-align:right;font-size:20px" class="" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="nomor_kartu" '); ?></div>
                </td>
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Jumlah Belanja</div>
                    <div style="float: right;"><input type="text" class="angka" id="jmh_belanja" name="jmh_belanja" style="padding:3px;text-align:right;font-size:20px" disabled="disabled"/></div>
                </td>
            </tr>
            <tr style="line-height:25px;">
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Jumlah</div>
                    <div style="float: right;"><?php echo form_input('jmh_dk','','style="padding:3px;text-align:right;font-size:20px" class="angka" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="jmh_dk" '); ?></div>
                </td>
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Total Belanja</div>
                    <div style="float: right;"><input type="text" class="angka" id="bayar_total" name="bayar_total" style="padding:3px;text-align:right;font-size:20px;" disabled="disabled"/></div>
                </td>
            </tr>
            <tr style="line-height:25px;">
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Biaya Kartu </div>
                    <div style="float: right;"><?php echo form_input('biaya_kartu','','style="padding:3px;text-align:right;font-size:20px" class="angka" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="biaya_kartu" '); ?></div>
                </td>
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Tunai</div>
                    <div style="float: right;"><input type="input" class="angka" id="bayar_bayar" name="bayar_bayar" style="padding:3px;text-align:right;font-size:20px"/></div>
                </td>
            </tr>
            <tr style="line-height:25px;">
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Total D/K</div>
                    <div style="float: right;"><?php echo form_input('total_dk','','style="padding:3px;text-align:right;font-size:20px" class="angka" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="total_dk" '); ?></div>
                </td>
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Kembali</div>
                    <div style="float: right;"><input type="input" class="angka" id="bayar_kembali" name="bayar_kembali" style="padding:3px;text-align:right;font-size:20px"/></div>
                </td>
            </tr>
        </table>
    </div>
</form>
<script type="text/javascript">

var base_url = '<?php echo base_url() ?>';
<?php
    echo ($cd=='1') ? 'tampil_cd(" "," ");' : '';
?>
$('#bayar_kembali').attr('disabled','disabled');
$(document).jkey('f12,f10',function(key){
    var total = $('#total').val();
    if(total!='' && total!='0')
    {
        if(key=='f12')
        {
            frmbayar();
            $('#bayar_bayar').focus();
        } 
        else if(key=='f10')
        {
            printScreen();
        }
    }
    else
    {
        alert("Barang masih kosong!");
    }
});
$('#total').val('0');
$('#imei').focus();
$('#ButtonBayar').live('click',function(){
    var total = $('#total').val();
    if(total!='' && total!='0')
    {
        frmbayar();
        $('#bayar_bayar').focus();
    }
    else
    {
        alert("Barang masih kosong!");
    }
})
$('#barang_harga').attr('disabled','disabled');
$('#imei').jkey('return,down,tab',function(key){
    if(key=='return' || key=='tab')
    {
        if($(this).val()!='')
        {
            $('#barang_kd').focus();
            //lihat_imei($(this).val());
        }
    } 
    else
    {
        $('#barang_kd').focus();
    }
});
$('#barang_kd').jkey('f2,return,down,tab,up,z',function(key){
    if(key=='return' || key=='down' || key=='tab')
    {
        if($(this).val()!='')
        {
            lihat_barang($(this).val());
        }
    } 
    else if(key=='f2')
    {
        var tujuan = base_url+"index.php/barang/list_barang_elektrik";
        window.open(tujuan,"barang","scrollbars=yes,width=660,height=400");
    }
    else if(key=='up')
    {
        $('#imei').focus();
    }
    else if(key=='z')
    {
        $(this).val('');
        $('#barang_nm').val('');
        $('#barang_stok').val('');
        $('#barang_harga').val('');
        $(this).focus();
    }
});
$('#barang_kd').live('focus',function(){
    var nmbrg = $('#barang_nm').val();
    if(nmbrg!='')
    {
        $(this).attr('readonly','readonly');
    }
    else
    {
        $(this).removeAttr('readonly');
    } 
});
$('#qty').jkey('return,down,tab,up',function(key){
    if(key=='return' || key=='down' || key=='tab')
    {
        var stok = $('#barang_stok').val();
        var qty = $(this).val();
        if($(this).val()!='')
        {
            var kd = $('#barang_kd').val();
            var nm = $('#barang_nm').val();
            var hg = convert_to_string($('#barang_harga').val());
            var qt = $('#qty').val();
            var imei = $('#imei').val();
            var hpokok = $('#hpokok').val();
            addRow(kd,nm,hg,qt,imei,hpokok);
        }
        else
        {
            alert("Tidak boleh kosong!");
            $(this).focus();
        }
    } 
    else
    {
        $('#barang_kd').focus();
    }
});
$('#bayar_bayar').on('keyup',function(){
     var val = convert_to_string($(this).val());
     $(this).val(convert_to_numeric(val));
     hitung_kembalian();
});
$('#bayar_bayar').jkey('return,f12,f10',function(){
     var kembali = convert_to_string($('#bayar_kembali').val());
     if(parseFloat(kembali) >= 0)
     {
         $("#proses").dialog("open");
        SimpanKasirBaru();
     }
     else
     {
        alert("Bayar tidak boleh kurang!");
        $('#bayar_bayar').focus();
     }
});
$('#term_kredit').on('change',function()
{
    hitung_biaya_kartu();
    $('#nomor_kartu').focus();
});
$('#term_tunai').on('change',function(){
    hitung_biaya_kartu();
    $('#bayar_bayar').focus();
});
$('#term_debit').on('change',function()
{
    hitung_biaya_kartu();
    $('#nomor_kartu').focus();
});
$('#nomor_kartu').jkey('return,down',function(){
    $('#jmh_dk').focus(); 
});
$('#jmh_dk').jkey('return,down',function(){
    $('#bayar_bayar').focus(); 
});
$('#jmh_dk').on('keyup',function(){
    var val = convert_to_string($(this).val());
    $(this).val(convert_to_numeric(val));
    hitung_biaya_kartu();
    hitung_kembalian(); 
});
$('img[id^=hapus]').live('click',function(){
    $(this).parents("tr").remove();
    hitung_total();
    $('#imei').focus();
});
function hitung_kembalian()
{
//    var tot = convert_to_string($('#bayar_total').val());
//    var byr = ($('#bayar_bayar').val()=='') ? 0 : convert_to_string($('#bayar_bayar').val());
//    var kembali = parseFloat(byr) - parseFloat(tot) /*- parseFloat(biaya_kartu)*/;
//    $('#bayar_kembali').val(convert_to_numeric(kembali));
    var tot = convert_to_string($('#bayar_total').val());
    var byr = ($('#bayar_bayar').val()=='') ? 0 : convert_to_string($('#bayar_bayar').val());
    var jmh_dk = ($('#jmh_dk').val()=='') ? 0 : convert_to_string($('#jmh_dk').val());
    var biaya_kartu = ($('#biaya_kartu').val()=='') ? 0 : convert_to_string($('#biaya_kartu').val());
    var total_dk = parseFloat(jmh_dk) + parseFloat(biaya_kartu);
    $('#total_dk').val(convert_to_numeric(total_dk));
    var kembali = (parseFloat(byr)+parseFloat(jmh_dk)) - parseFloat(tot) /*- parseFloat(biaya_kartu)*/;
    $('#bayar_kembali').val(convert_to_numeric(kembali));
}
function lihat_barang(kd)
{
    var tujuan  = '<?php echo base_url() ?>index.php/barang/lihat_barang_elektrik/'+kd+'/';
    $.ajax({
      url: tujuan,
      success: function(data) {
        pisahin = data.split("#");
        if(pisahin[0]=='E')
        {
            alert("Kode Barang tidak diketahui/Barang Kosong!");
        }
        else
        {
            var kd = pisahin[1];
            var nm = '';
            if($('#imei').val()!='')
            {
                nm = pisahin[2]+'\n#'+$('#imei').val();
            }
            else
            {
               nm = pisahin[2];
            }
            var harga = pisahin[4];
            var hpp = pisahin[3];
            var diskon = 0;
            var imei = $('#imei').val();
            $('#barang_kd').val(kd);
            $('#barang_nm').val(nm);
            $('#barang_harga').val(convert_to_string(harga));
            $('#hpokok').val(convert_to_string(hpp));
            if(imei == '')
            {
                alert('Isi terlebih dahulu No. HP!');
                $('#imei').focus();   
            }
            else
            {
                $('#qty').focus();
            }
        }
      }
    });
}
function addRow(kd,nm,harga,qty,imei,hpp) 
{    
    qty = parseFloat(qty);
    harga = parseFloat(harga);
    if(ngecekbarang(kd,imei)=='')
    {
        var hasil = false;
        if(qty > 0 && harga > 0)
        {
            if(imei=='')
            {
                alert("No. HP tidak boleh kosong!");
                $('#imei').focus();
            }
            else
            {
            	var tbl = $('#myTable');
            	var lastRow = tbl.find("tr").length;
                var tblno = '<td class="atas">'+lastRow+'</td>';
            	var tblkd = '<td class="atas"><input id="kd'+lastRow+'" value="'+kd+'" disabled="disabled"/></td>';
            	var tblnm = '<td class="atas"><textarea class="barangnm" disabled="disabled" id="nm'+lastRow+'">'+nm+'</textarea></td>';
            	var tblqt = '<td class="atas"><input id="qty'+lastRow+'" class="kanan" value="'+qty+'" disabled="disabled"/></td>';
            	var tblhg = '<td class="atas"><input id="hrg'+lastRow+'" class="kanan" value="'+convert_to_numeric(harga)+'" disabled="disabled"/></td>';
                var jumlah = (qty*harga);
                var tbljm = '<td class="atas"><input id="jmh'+lastRow+'" class="kanan" value="'+convert_to_numeric(jumlah)+'" disabled="disabled"/></td>';
                var hpps = '<input type="input" style="border:none;color:white;" name="harga_pokok'+lastRow+'" id="harga_pokok'+lastRow+'" value="'+hpp+'"/>';
                var gambarhapus = '<td class="atas">'+hpps+'<img id="hapus'+lastRow+'" class="delete'+lastRow+'" src="'+base_url+'inovapos_asset/img/bin.gif" style="margin:0;padding:0;cursor:pointer" /></td>';
            	tbl.children().append("<tr>"+tblno+tblkd+tblnm+tblqt+tblhg+tbljm+gambarhapus+"</tr>");
                <?php
                echo ($cd=='1') ? 'tampil_cd(nm,jumlah);' : '';
                ?>
                hitung_total();
                kosongin();
                hasil = true;
            }
        }
        else
        {
        	alert("Tidak boleh kosong!");
            $('#qty').focus();
        }
        return hasil;
    }
    else
    {
        alert("Barang sudah masuk!");
        kosongin();
        $('#imei').focus();
    }
}
function ngecekbarang(lkd,limei)
{
	var tbl = $('#myTable');
	var lastRow = tbl.find("tr").length;
    var status = "";
	var test = "";
    if(lastRow > 1)
    {
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
//            var id = AoA[i][0];
//            if(lkd==id)
//            {
//                status = 'Barang sudah masuk!';
//            }
//        }
    }
    return status;
}
function hitung_total()
{
	var tbl = $('#myTable');
	var lastRow = tbl.find("tr").length;
    var jumlah_total = 0;
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
        for(i=1;i<AoA.length;i++)
        {
            var jmhsatuan = convert_to_string(AoA[i][4]);
            jumlah_total += parseFloat(jmhsatuan);
        }
    }
    $('#total').val(convert_to_numeric(jumlah_total));
}
function kosongin()
{
    $('#imei').val("");
    $('#barang_kd').val("");
    $('#barang_nm').val("");
    $('#barang_harga').val("");
    $('#hpokok').val('');
    
    $('#qty').val("");
    $('#bayar_bayar').val('0');
    $('#bayar_kembali').val('0');
    
    $('#imei').focus();
}
function printScreen()
{
    var baseURL   = '<?php echo base_url() ?>';
    var AoA = $('#myTable tr').map(function()
                {
                    return [
                        $('td',this).map(function(){
                            return $(this).children().val();           
                        }).get()
                    ];
                }).get(); 
    //alert(AoA);return;
    if(AoA.length == 1)
    {
        alert("isi dulu, baru print!");
    }
    else
    {
        $.post(
            baseURL + 'index.php/kasir/cetak_screen', 
            {data: AoA}, 
            function(res)
            {
                if(res!='')
                {
                    alert(res);
                }
            });
    }
}
function SimpanKasirBaru()
{
    var AoA = $('#myTable tr').map(function()
                {
                    return [
                        $('td',this).map(function(){
                            return $(this).children().val();           
                        }).get()
                    ];
                }).get(); 
                
    var sDK = $('input[name=term]:checked').val();
    var jmhDebet = 0; 
    jmhKredit = 0;    
    if(sDK.toUpperCase() == 'KREDIT')
    {
        jmhKredit =  convert_to_string($('#jmh_dk').val());
    }
    else if (sDK.toUpperCase() == 'DEBIT')
    {
        jmhDebet = convert_to_string($('#jmh_dk').val());
    }
    if((sDK.toUpperCase()=='KREDIT'||sDK.toUpperCase()=='DEBIT')&&($('#nomor_kartu').val()==''||$('#nomor_kartu').val()=='0'))
    {
        alert("Nomor Kartu tidak boleh kosong!");
    }
    else
    {
        var totalBelanja    = convert_to_string($('#bayar_total').val());
        var biayaKartu      = ($('#biaya_kartu').val()=='') ? 0 : convert_to_string($('#biaya_kartu').val());
        var jmhTunai        = parseFloat(totalBelanja) + parseFloat(biayaKartu) - parseFloat(jmhKredit) - parseFloat(jmhDebet); 
        var hasil           = totalBelanja + '-' + biayaKartu + '-' + jmhDebet + '-' + jmhKredit;
        var jmh_bayar       = $('#bayar_bayar').val();
        var data = {
            kd_term         : 'KASIR',
            jmh_belanja     : convert_to_string($('#jmh_belanja').val()),
            diskon_p        : 0,
            diskon_nominal  : 0,
            total_belanja   : totalBelanja,
            biaya_kirim     : 0,
            lunas           : 0,
            nomor_kartu     : $('#nomor_kartu').val(),
            dk              : $('input[name=term]:checked').val(),
            jmh_tunai       : jmhTunai,
            jmh_debet       : jmhDebet,
            jmh_kredit      : jmhKredit,
            biaya_kartu     : biayaKartu,
            jmh_uang        : convert_to_string($('#bayar_bayar').val()),
            jmh_kembali     : convert_to_string($('#bayar_kembali').val()),
    
            rows            : AoA
        };
        
        var json = JSON.stringify(data);         
        //alert(json);return;             
        $.post(
            base_url+'index.php/kasir/simpan_elektrik',
            {data:json},
            function(res)
            {
                //alert(res);return;
                var statusfaktur = res.split('#');
                var status = statusfaktur[0];
                if(status=='E')
                {
                    $("#proses").dialog("close");
                    alert(statusfaktur[1]);
                }
                else
                {
                    print_faktur_elektrik(statusfaktur[1]);
                }
            }
        )
        .error(function(){ 
            alert("Ada Error di Transaction.\nHubungi Admin/Bagian IT!.");
            window.location = window.location; 
        });
    }
    return false; 
}
function print_faktur_elektrik(nofaktur)
{
    $.post(base_url + 'index.php/kasir/cetak_elektrik_dari_faktur/'+nofaktur+'/kasir',function(data)
    {
        //alert(data);return;
        window.location=window.location;
    });
}
function frmbayar()
{    
    var total = $('#total').val();
    $('#bayar_total').val(total);
    $('#jmh_belanja').val(total);
    $('#bayar_bayar').val('0');
    var kembali = 0-parseFloat(convert_to_string(total));
    $('#bayar_kembali').val(kembali);
    $('#bayar_bayar').focus();
    $("#form-bayar-elektrik").dialog("open");
    <?php
        echo ($cd=='1') ? 'tampil_cd("TOTAL",convert_to_string($("#jmh_belanja").val()));' : '';
    ?>
    
}
function tampil_cd(nmbarang,harga)
{
    var data        = { nm : nmbarang, hrg : harga};
    var json        = JSON.stringify(data);
    $.post(
            base_url+'index.php/kasir/tampil_cd',
            {data:json},
            function(res)
            {
                if(res!='')
                {
                    alert(res);
                }
            }
        )
        .error(function(){ 
            alert("Ada Error di Tampil Costumer Display!\nHubungi Admin/Bagian IT!.");
            //window.location = window.location; 
        });
}
function hitung_biaya_kartu()
{
    $('#nomor_kartu').removeAttr('disabled');
    $('#jmh_dk').removeAttr('disabled');
    $('#biaya_kartu').removeAttr('disabled');
    $('#total_dk').val('0');
    if($('#term_debit').is(':checked'))
    {
        $('#biaya_kartu').val('0');
        hitung_kembalian();
    }
    else if($('#term_kredit').is(':checked'))
    {
        $('#biaya_kartu').attr('disabled','disabled');
        var tot = ($('#jmh_dk').val()==''||$('#jmh_dk').val()=='0') ? 0 : convert_to_string($('#jmh_dk').val());
        var biaya_kartu = (<?php echo ($this->app_model->system('biaya_kartu')=='') ? '0' : $this->app_model->system('biaya_kartu') ;  ?>/100)*parseFloat(tot);
        <?php //echo $this->db->last_query(); ?>
        var total_dk = (parseFloat(tot) + parseFloat(biaya_kartu));
        $('#biaya_kartu').val(convert_to_numeric(biaya_kartu));
        $('#total_dk').val(convert_to_numeric(total_dk));
        hitung_kembalian();
    }
    else if($('#term_tunai').is(':checked'))
    {
        $('#nomor_kartu').attr('disabled','disabled');
        $('#nomor_kartu').val('');
        $('#biaya_kartu').attr('disabled','disabled');
        $('#biaya_kartu').val('');
        $('#jmh_dk').attr('disabled','disabled');
        $('#jmh_dk').val('');
        $('#total_dk').val('');
        hitung_kembalian();
    }
}
</script>