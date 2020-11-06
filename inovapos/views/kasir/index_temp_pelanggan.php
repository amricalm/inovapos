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
<form action="<?php echo base_url() ?>index.php/kasir/simpan/" method="POST" name="frmKasir" id="frmKasir">
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
                        <th style="width:13%;text-align:right;">Diskon</th>
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
                        <td>IMEI</td>
                        <td>
                            <input type="text" name="imei" id="imei" class="input-long" />
                        </td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td>Kode Barang</td>
                        <td>
                            <input type="text" name="barang_kd" id="barang_kd" class="input-long" />
                            <input type="hidden" name="barang_grup" id="barang_grup" class="input-long" />
                        </td>
                        <td>Nama Barang</td>
                        <td><input type="text" name="barang_nm" id="barang_nm" class="input-long" disabled="disabled" /></td>
                        <td>Stok</td>
                        <td><input type="text" name="barang_stok" id="barang_stok" class="input-long" disabled="disabled" /></td>
                        <td>Harga</td>
                        <td><input type="text" name="barang_harga" id="barang_harga" class="input-long" disabled="disabled" /></td>
                    </tr>
                    <tr>
                        <td>Qty</td>
                        <td><input type="text" name="qty" id="qty" class="qty1000 input-long " /></td>
                        <td>Diskon</td>
                        <td><input type="text" name="diskon" id="diskon" class="qty100 input-long " />&nbsp;(%)</td>
                        <!--<td><input type="checkbox" id="cbxPelanggan" name="cbxPelanggan" /></td>
                        <td>Pelanggan</td>
                        <td>Kode Pelanggan</td>
                        <td><input type="text" name="kdPelanggan" id="kdPelanggan" disabled="disabled" class="input-long " /></td>-->
                        <td colspan="4"></td>
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
    <div id="form-bayar" title="Kasir : <?php echo $this->session->userdata('user_nm') ?> | <?php echo $this->adntgl->tgl_panjang(date('Y-m-d')); ?>">
        <table style="width: 700px;">
            <tr style="line-height:25px;">
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;"><?php echo form_checkbox('cbxpelanggan',1,false,'id="cbxpelanggan"'); ?> Pelanggan</div>
                    <div style="float: right;"><input type="text" class="" id="idpelanggan" name="idpelanggan" style="padding:5px;text-align:right;font-size:20px" disabled="disabled"/></div>
                </td>
                <td style="width: 50%;"><div id="datapelanggan"></div>&nbsp;</td>                
            </tr>            
            <tr style="line-height:25px;">
                <td colspan="2"><hr /></td>
            </tr>
            <tr style="line-height:25px;">
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Pembayaran </div>
                    <div style="float: right;"><?php echo form_radio('term','Tunai',true,'id="term_tunai"').'&nbsp;Tunai&nbsp;'.form_radio('term','Kredit',false,'id="term_kredit"').'&nbsp;Kredit&nbsp;'.form_radio('term','Debit',false,'id="term_debit" ').'&nbsp;Debit&nbsp;'.form_radio('term','Leasing',false,'id="term_leasing" ').'&nbsp;Leasing&nbsp;'; ?></div>
                </td>
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Jumlah Belanja</div>
                    <div style="float: right;"><input type="text" class="angka" id="jmh_belanja" name="jmh_belanja" style="padding:3px;text-align:right;font-size:20px" disabled="disabled"/></div>
                </td>            
            </tr>    
            <tr style="line-height:25px;">
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;"><span id="label-nomor-kartu">Nomor Kartu</span></div>
                    <div style="float: right;"><?php echo form_input('nomor_kartu','','style="padding:5px;text-align:right;font-size:20px" class="" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="nomor_kartu" '); ?></div>
                </td>
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Diskon (%)</div>
                    <div style="float: right;"><input type="input" class="qty100 angka" id="bayar_diskon" name="bayar_diskon" style="padding:5px;text-align:right;font-size:20px;width:50px;"/><input type="input" class="angka" id="bayar_diskon_display" style="padding:5px;text-align:right;font-size:20px;width:140px;" disabled="disabled"/></div>
                </td>            
            </tr> 
            <tr style="line-height:25px;">
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Jumlah</div>
                    <div style="float: right;"><?php echo form_input('jmh_dk','','style="padding:5px;text-align:right;font-size:20px" class="angka" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="jmh_dk" '); ?></div>
                </td>
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;"><strong>Total Belanja</strong></div>
                    <div style="float: right;"><input type="text" class="angka" id="bayar_total" name="bayar_total" style="padding:5px;text-align:right;font-size:20px;" disabled="disabled"/></div>
                </td>            
            </tr>
            <tr style="line-height:25px;">
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;"><span id="label-biaya-kartu">Biaya Kartu</span></div>
                    <div style="float: right;"><?php echo form_input('biaya_kartu','','style="padding:5px;text-align:right;font-size:20px" class="angka" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="biaya_kartu" '); ?></div>
                </td>
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Tunai</div>
                    <div style="float: right;"><input type="input" class="angka" id="bayar_bayar" name="bayar_bayar" style="padding:5px;text-align:right;font-size:20px"/></div>
                </td>            
            </tr>
            <tr style="line-height:25px;">
                <td style="width: 50%;">
                    
                    <div style="float:left;padding:5px;">Total D/K</div>
                    <div style="float: right;"><?php echo form_input('total_dk','','style="padding:5px;text-align:right;font-size:20px" class="angka" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="total_dk" '); ?></div>
                </td>
                <td style="width: 50%;">
                    <div style="float:left;padding:5px;">Kembali</div>
                    <div style="float: right;"><input type="input" class="angka" id="bayar_kembali" name="bayar_kembali" style="padding:5px;text-align:right;font-size:20px"/></div>
                </td>            
            </tr>
        </table>
    </div>
</form>
<script type="text/javascript">
/**
 * $(document).ready(function() {
 *     $('#pelanggan').dialog({
 *         autoOpen : true,
 *         resizable : false,
 *         modal : true,
 *         height : 300,
 *         width : 300
 *     });
 * });
 */
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
//$('#barang_kd').attr('readonly','readonly');
$('#barang_harga').attr('disabled','disabled');
$('#imei').jkey('return,down,tab',function(key){
    if(key=='return' || key=='tab')
    {
        if($(this).val()!='')
        {
            lihat_imei($(this).val());
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
        var tujuan = base_url+"index.php/barang/list_barang_temp";
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
        if(parseFloat(stok)<parseFloat(qty))
        {
            alert("Stok tidak mencukupi!");
            $(this).val(stok);
        }
        else
        {
            if($(this).val()!='' && $(this).val()!='0')
            {
                $('#diskon').focus();
            }
        }
    } 
    else
    {
        $('#barang_kd').focus();
    }
});
$('#diskon').jkey('return,down,tab,up',function(key){
    if(key=='return' || key=='down' || key=='tab')
    {
        var kd = $('#barang_kd').val();
        var nm = $('#barang_nm').val();
        var st = $('#barang_stok').val();
        var hg = convert_to_string($('#barang_harga').val());
        var qt = $('#qty').val();
        var ds = $('#diskon').val();
        addRow(kd,nm,hg,st,ds,qt);
    } 
    else
    {
        $('#barang_kd').focus();
    }
});
$('#bayar_diskon').on('keyup',function(){
    var persendiskon = ($(this).val()=='') ? '0' : convert_to_string($(this).val());
    var totalbelanja = convert_to_string($('#jmh_belanja').val());
    //alert(parseFloat(persendiskon));
    if(parseFloat(persendiskon) > 100)
    {
        alert("Diskon tidak boleh lebih dari 100%!");
        $(this).val('100');
    }
    else if(parseFloat(persendiskon) < 0)
    {
        alert("Diskon tidak boleh minus!");
        $(this).val('0');
    }
    else if(parseFloat(persendiskon) >= 0)
    {
        diskon(persendiskon,totalbelanja);
        hitung_kembalian();
    }
});
$('#bayar_diskon').jkey('down,return',function(){
    $('#bayar_bayar').focus();
});
$('#bayar_bayar').on('keyup',function(){
     var val = convert_to_string($(this).val());
     $(this).val(convert_to_numeric(val));
     
     if($('#term_leasing').is(':checked'))
     {
        var jmh = adn_cnum($('#jmh_belanja').val())- adn_cnum($(this).val())
        $('#jmh_dk').val(convert_to_numeric(jmh));
     }
     
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
$(':radio[name="term"]').on('change',function(){
    
    $('#jmh_dk').attr('disabled',false);
    hitung_biaya_kartu();
    if($(this).attr('id')=='term_tunai')
    {
        $('#bayar_bayar').focus();
    }
    else if($(this).attr('id')=='term_leasing')
    {
        $('#jmh_dk').attr('disabled',true);    
        $('#nomor_kartu').focus();
    }
    else
    {
        $('#nomor_kartu').focus();
    }
});

$('#biaya_kartu').on('keyup',function(){

    var jmh_dk = ($('#jmh_dk').val()=='') ? 0 : convert_to_string($('#jmh_dk').val());
    var biaya_kartu = ($('#biaya_kartu').val()=='') ? 0 : convert_to_string($('#biaya_kartu').val());
    var total_dk = parseFloat(jmh_dk) + parseFloat(biaya_kartu);
    $('#total_dk').val(total_dk);
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
$('#cbxpelanggan').live('click',function(){
    if($(this).is(':checked'))
    {
        $('#idpelanggan').removeAttr('disabled');   
    } 
    else
    {
        $('#idpelanggan').attr('disabled','disabled');
        $('#idpelanggan').val('');
    }
});
$('#idpelanggan').on('blur',function(){
    var id = ($(this).val()=='')?'0':$(this).val();
    if(id!='0')
    {
        var tujuan  = '<?php echo base_url() ?>index.php/pelanggan/cek_pelanggan/'+id+'/';
        $.ajax({
          url: tujuan,
          success: function(data) {
                //alert(data);
                pisahin = data.split("#");
                if(pisahin[0]=='0')
                {
                    alert("Kode Member/Pelanggan tidak diketahui!");
                    $('#idpelanggan').focus();
                }
                else
                {
                    var tampil = 'Nama : '+pisahin[1]+'<br/>Kategori : '+pisahin[2];
                    $('#datapelanggan').html(tampil);
                    $('#bayar_diskon').val(pisahin[3]);
                    var totalbelanja = convert_to_string($('#jmh_belanja').val());
                    diskon(pisahin[3],totalbelanja);
                    hitung_kembalian();
                    $('#bayar_bayar').focus();
                }
          }
        });
      }
      else
      {
        $('#idpelanggan').focus();
        alert("Silahkan Isi terlebih dahulu Kode Member/Pelanggan!");
      }
});
function diskon(persendiskon,totalbelanja)
{ 
    var diskon = (parseFloat(convert_to_float(persendiskon))/100)*parseFloat(totalbelanja);
    var bayartotal = parseFloat(totalbelanja) - diskon.toFixed(0);
    $('#bayar_diskon_display').val(convert_to_numeric(diskon.toFixed(0)));
    $('#bayar_total').val(convert_to_numeric(bayartotal));
}
function hitung_kembalian()
{
    var tot = convert_to_string($('#bayar_total').val());
    var byr = ($('#bayar_bayar').val()=='') ? 0 : convert_to_string($('#bayar_bayar').val());
    var jmh_dk = ($('#jmh_dk').val()=='') ? 0 : convert_to_string($('#jmh_dk').val());
    var biaya_kartu = ($('#biaya_kartu').val()=='') ? 0 : convert_to_string($('#biaya_kartu').val());
    var total_dk = parseFloat(jmh_dk) + parseFloat(biaya_kartu);
    $('#total_dk').val(convert_to_numeric(total_dk));
    var kembali = (parseFloat(byr)+parseFloat(jmh_dk)) - parseFloat(tot) /*- parseFloat(biaya_kartu)*/;
    $('#bayar_kembali').val(convert_to_numeric(kembali));
}
function hitung_biaya_kartu()
{
    $('#label-biaya-kartu').text('Biaya Kartu');//Default
    $('#label-nomor-kartu').text('Nomor Kartu');//Default
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
    else if($('#term_leasing').is(':checked'))
    {   
        $('#label-nomor-kartu').text('Referensi');
        $('#label-biaya-kartu').text('Biaya Admin');
        $('#biaya_kartu').val('0');
        var jmh_dk = adn_cnum($('#bayar_total').val()) -  adn_cnum($('#bayar_bayar').val());
        $('#jmh_dk').val(convert_to_numeric(jmh_dk));
        hitung_kembalian();
    }
}
function lihat_imei(id)
{
    var tujuan  = '<?php echo base_url() ?>index.php/barang/lihat_imei/'+id+'/';
    $.ajax({
      url: tujuan,
      success: function(data) {
        pisahin = data.split("#");
        if(pisahin[0]=='E')
        {
            alert("Kode Imei tidak diketahui/Barang Kosong!");
        }
        else
        {
            lihat_barang(pisahin[2]);
        }
      }
    });
}
function lihat_barang(kd)
{
    var tujuan  = '<?php echo base_url() ?>index.php/barang/lihat_barang/'+kd+'/';
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
            var stok = pisahin[3];
            var harga = pisahin[4];
            var diskon = 0;
            //var imei = new Array()
            //imei[0] = $('#imei').val();
            var imei = $('#imei').val();
            $('#barang_kd').val(kd);
            $('#barang_nm').val(nm);
            $('#barang_stok').val(stok);
            $('#barang_harga').val(convert_to_string(harga));
            //alert(imei.length);return;
            if(imei == '')
            {
                if(pisahin[5]=='10'||pisahin[5]=='60'||pisahin[5]=='70')
                {
                    alert('Tekan F2 untuk memilih IMEI');
                    $('#barang_kd').focus();   
                }
                else
                {
                    $('#qty').focus();
                } 
            }
            else
            {
                //$('#qty').focus();
                addRow(kd,nm,harga,stok,diskon,1,imei);
            }
        }
      }
    });
}
function addRow(kd,nm,harga,stok,diskon,qty,imei) 
{    
    if(ngecekbarang(kd,imei)=='')
    {
        var hasil = false;
        if(qty > 0 && harga > 0)
        {
            if(parseInt(qty) <= parseInt(stok))
            {
                if(nm.substr(0,2)=="HP" && imei=='')
                {
                    alert("Grup HP, imei tidak boleh kosong!");
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
                    if(diskon=='') diskon = 0; 
                    var jmhdiskon = (diskon==0) ? 0 : (parseFloat(diskon)/100)*parseFloat(convert_to_string(harga));
                	var tblds = '<td class="atas"><input id="dsk'+lastRow+'" class="kanan" value="'+convert_to_numeric(jmhdiskon)+'" disabled="disabled"/></td>';
                    var jumlah = (parseFloat(qty)*parseFloat(harga))-parseFloat(jmhdiskon);
                    var tbljm = '<td class="atas"><input id="jmh'+lastRow+'" class="kanan" value="'+convert_to_numeric(jumlah)+'" disabled="disabled"/></td>';
                    var gambarhapus = '<td class="tengah"><img id="hapus'+lastRow+'" class="delete'+lastRow+'" src="'+base_url+'inovapos_asset/img/bin.gif" style="margin:0;padding:0;cursor:pointer" /></td>';
                	tbl.children().append("<tr>"+tblno+tblkd+tblnm+tblqt+tblhg+tblds+tbljm+gambarhapus+"</tr>");
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
                alert("Stok tidak mencukupi!");
            }
        }
        else
        {
        	alert("Tidak boleh kosong!");
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
            var id = AoA[i][0];
            if(lkd==id)
            {
                status = 'Barang sudah masuk!';
            }
        }
//        for (i=1; i<lastRow; i++)
//        {
//            var kd = $('#kd'+i).val();
//            if(lkd==kd)
//            {
//                status = 'Barang sudah masuk!';
//            }
//            /* --- >>> ini validasi memakai IMEI, bukan Kode Barang <<< --- */
//            var imei = $('#nm'+i).val()
//            for(j=0;j<limei.length;j++)
//            {
//                //alert(imei.search(limei[j]));
//                if(imei.search(limei[j])!=-1)
//                {
//                    status = 'IMEI sudah masuk!';
//                } 
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
            var jmhsatuan = convert_to_string(AoA[i][5]);
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
    $('#barang_stok').val("");
    $('#barang_harga').val("");
    
    $('#qty').val("");
    $('#diskon').val("");
    
    
    $('#nomor_kartu').val('');
    $('#biaya_kartu').val('0');
    $('#bayar_diskon').val('0');
    $('#bayar_diskon_display').val('0');
    $('#bayar_total').val('0');
    $('#bayar_bayar').val('0');
    $('#bayar_kembali').val('0');
    
    $('#imei').focus();
}
function hapusBaris(no)
{
    $('.delete'+no).parents("tr").remove();
    hitung_total();
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
    console.log('Simpan...');
    var AoA = $('#myTable tr').map(function()
                {
                    return [
                        $('td',this).map(function(){
                            return $(this).children().val();           
                        }).get()
                    ];
                }).get(); 

    var jmhDebet = 0;
    var sDK = $('input[name=term]:checked').val();
    var iLeasing = 0;
                 
    if (sDK.toUpperCase()=='LEASING') iLeasing = 1; 
    jmhKredit = 0;
    
    if(sDK.toUpperCase() == 'KREDIT')
    {
        jmhKredit =  adn_cnum($('#jmh_dk').val());
    }
    else if (sDK.toUpperCase() == 'DEBIT' || sDK.toUpperCase() == 'LEASING')
    {
        jmhDebet = adn_cnum($('#jmh_dk').val());
    }

    var totalBelanja = convert_to_string($('#bayar_total').val());
    var biayaKartu = ($('#biaya_kartu').val()=='') ? 0 : convert_to_string($('#biaya_kartu').val());
    var jmhTunai = parseFloat(totalBelanja) + parseFloat(biayaKartu) - parseFloat(jmhKredit) - parseFloat(jmhDebet); 
    var hasil = totalBelanja + '-' + biayaKartu + '-' + jmhDebet + '-' + jmhKredit;
    var jmh_bayar = $('#bayar_bayar').val();

    var data = {
        kd_term         : 'KASIR',
        jmh_belanja     : convert_to_string($('#jmh_belanja').val()),
        diskon_p        : convert_to_string($('#bayar_diskon').val()),
        diskon_nominal  : convert_to_string($('#bayar_diskon_display').val()),
        total_belanja   : totalBelanja,
        biaya_kirim     : 0,
        lunas           : 0,
        kd_pelanggan    : ($('#idpelanggan').val()!=''&&$('#idpelanggan').val()!='0')?$('#idpelanggan').val():0,
        nomor_kartu     : $('#nomor_kartu').val(),
        dk              : sDK,
        jmh_tunai       : jmhTunai,
        jmh_debet       : jmhDebet,
        jmh_kredit      : jmhKredit,
        biaya_kartu     : biayaKartu,
        jmh_uang        : jmh_bayar,
        jmh_kembali     : kembali,
        leasing         : iLeasing,
        
        rows            : AoA
    };
    console.log(data);
    var json = JSON.stringify(data);         
            
    $.post(
        base_url+'index.php/kasir/simpan_temp_pelanggan',
        {data:json},
        function(res)
        {
            var statusfaktur = res.split('#');
            var status = statusfaktur[0];
            if(status=='E')
            {
                $("#proses").dialog("close");
                alert(statusfaktur[1]);
            }
            else
            {
                print_faktur(statusfaktur[1]);
            }
        }
    )
    .error(function(){ 
        alert("Ada Error di Transaction.\nHubungi Admin/Bagian IT!.");
        window.location = window.location; 
    });
    
    return false; 
}
function frmbayar()
{    
    var total = $('#total').val();
    $('#bayar_total').val(total);
    $('#jmh_belanja').val(total);
    $('#jmh_dk').val('0');
    $('#bayar_bayar').val('0');
    $('#bayar_diskon').val('0');
    $('#bayar_diskon_display').val('0');
    var kembali = 0-parseFloat(convert_to_string(total));
    $('#bayar_kembali').val(kembali);
    $('#bayar_bayar').focus();
    $("#form-bayar").dialog("open");
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
</script>