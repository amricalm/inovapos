
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
                        <td><input type="text" name="barang_kd" id="barang_kd" class="input-long" /></td>
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
                        <td><input type="text" name="diskon" id="diskon" class="qty100 input-long " /></td>
                        <td colspan="2"></td>
                        <td colspan="2"></td>
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
        </div>
    </div>
    <!-- Dialog Proses -->
    <div id="proses" title="Sedang Proses, Mohon Tunggu!" style="text-align: center;vertical-align:center;">
        <img src="<?php echo $base_img.'/ajax-loader.gif'; ?>" />
    </div>
    <!-- Dialog Kasir -->
    <div id="form-bayar" style="height:800px;" title="Kasir : <?php echo $this->session->userdata('user_nm') ?> | <?php echo $this->adntgl->tgl_panjang(date('Y-m-d')); ?>">
        <table>
            <tr>
                <td style="width: 200px;padding:5px">Jumlah Belanja</td>
                <td><input type="text" class="angka" id="jmh_belanja" name="jmh_belanja" style="padding:5px;text-align:right;font-size:20px" disabled="disabled"/></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 200px;padding:5px">Diskon (%)</td>
                <td><input type="input" class="angka" id="bayar_diskon" name="bayar_diskon" style="padding:5px;text-align:right;font-size:20px;width:50px;"/><input type="input" class="angka" id="bayar_diskon_display" style="padding:5px;text-align:right;font-size:20px;width:140px;" disabled="disabled"/></td>
                <td></td>
            </tr>
            
            <tr>
                <td style="width: 200px;padding:5px;font-weight:bold">Total Belanja</td>
                <td><input type="text" class="angka" id="bayar_total" name="bayar_total" style="padding:5px;text-align:right;font-size:20px;" disabled="disabled"/></td>
                <td></td>
            </tr>
            <tr>
                <td colspan="3"><hr /></td>
            </tr>
            <tr>
                <td style="width: 200px;padding:5px">Pembayaran </td>
                <td><?php echo form_radio('term','Tunai',true,'id="term_tunai"').'&nbsp;Tunai&nbsp;'.form_radio('term','Kredit',false,'id="term_kredit"').'&nbsp;Kreditx&nbsp;'.form_radio('term','Debit',false,'id="term_debit" ').'&nbsp;Debit&nbsp;'.form_radio('term','Leasing',false,'id="term_leasing" ').'&nbsp;Leasing&nbsp;'; ?></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 200px;padding:5px">Nomor Kartu </td>
                <td><?php echo form_input('nomor_kartu','','style="padding:5px;text-align:right;font-size:20px" class="" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="nomor_kartu" '); ?></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 200px;padding:5px">Jumlah </td>
                <td><?php echo form_input('jmh_dk','','style="padding:5px;text-align:right;font-size:20px" class="angka" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="jmh_dk" '); ?></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 200px;padding:5px">Biaya Kartu </td>
                <td><?php echo form_input('biaya_kartu','','style="padding:5px;text-align:right;font-size:20px" class="angka" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="biaya_kartu" '); ?></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 200px;padding:5px">Tunai</td>
                <td><input type="input" class="angka" id="bayar_bayar" name="bayar_bayar" style="padding:5px;text-align:right;font-size:20px"/></td>
                <td></td>
            </tr>
            
            <tr>
                <td style="width: 200px;padding:5px">Kembali</td>
                <td><input type="input" class="angka" id="bayar_kembali" name="bayar_kembali" style="padding:5px;text-align:right;font-size:20px"/></td>
                <td></td>
            </tr>
        </table>
    </div>
</form>
<script type="text/javascript">

var baseURL = '<?php echo base_url() ?>';    
$(document).ready(function(){
    function hitung_kembali()
    {
        var tot = convert_to_string($('#bayar_total').val());
        var byr = convert_to_string($('#bayar_bayar').val());
        var jmh_dk = convert_to_string($('#jmh_dk').val());
        var biaya_kartu = convert_to_string($('#biaya_kartu').val());
        var  kembali = (parseFloat(byr)+parseFloat(jmh_dk)) - parseFloat(tot) - parseFloat(biaya_kartu);
        $('#bayar_kembali').val(convert_to_numeric(kembali));
    }
    $('#jmh_dk').val('0');
    $('#bayar_bayar').val('');
    $('input.angka').autoNumeric({aSep: '.',aDec: ',', mDec: '0',vMin: '-999999999'}); 
    $('#barang_harga')
        .autoNumeric({aSep: '.',aDec: ',', mDec: '0'})
        .css('text-align','right');
    $('#barang_stok')
        .autoNumeric({aSep: '.',aDec: ',', mDec: '0'})
        .css('text-align','right');
    $('#term_debit').on('change',function()
    {
        $('#jmh_dk').removeAttr('disabled');
        $('#nomor_kartu').removeAttr('disabled');
        $('#biaya_kartu').val('0');
        $('#jmh_dk').val('0');
        $('#biaya_kartu').removeAttr('disabled');
    });
    $('#term_kredit').on('change',function()
    {
        $('#jmh_dk').removeAttr('disabled');
        $('#nomor_kartu').removeAttr('disabled');
        $('#biaya_kartu').attr('disabled','disabled');
        //$('#biaya_kartu').removeAttr('disabled');
    });
    $('#jmh_dk').on('keyup',function()
    {
        var term = $('input[name=term]:checked').val();
        if(term=="Debit")
        {
            hitung_kembali();
        }
        else
        {
            var dk = convert_to_string($('#jmh_dk').val());
            var biaya = (3 * parseFloat(dk) / 100);
            dk = parseFloat(dk) + parseFloat(biaya);
            $('#biaya_kartu').val(convert_to_numeric(biaya));
        }
        
    });
    $('#jmh_dk').on('change',function()
    {
        var term = $('input[name=term]:checked').val();
        if(term=="Debit")
        {
            hitung_kembali();
        }
        else
        {
            var dk = convert_to_string($('#jmh_dk').val());
            var biaya = (3 * dk / 100);
            dk = parseFloat(dk) + parseFloat(biaya);
            $(this).val(convert_to_numeric(dk));
        }
    });
    
    $('#term_tunai').on('change',function()
    {
        $('#jmh_dk').val('0');
        $('#jmh_dk').attr('disabled', 'disabled');
        $('#biaya_kartu').attr('disabled', 'disabled');
        $('#nomor_kartu').attr('disabled', 'disabled');
        $('#nomor_kartu').val('0');
        $('#biaya_kartu').val('0');
        hitung_kembali();
    });
    $('#bayar_bayar').on('keyup', hitung_kembali);
    $('#bayar_diskon').on('keyup',function()
    {
        var jmhBelanja = convert_to_string($('#jmh_belanja').val());
        var diskon = convert_to_string($('#bayar_diskon').val());
        var diskonNilai = (parseFloat(diskon)/100)*parseFloat(jmhBelanja);
        var jmhTagihan  = parseFloat(jmhBelanja) - parseFloat(diskonNilai);
        $('#bayar_diskon_display').val(convert_to_numeric(diskonNilai));
        $('#bayar_total').val(convert_to_numeric(jmhTagihan));
        
        hitung_kembali();
    });
    
    $('#bayar_bayar').jkey('return',function(key){
        kembali = convert_to_string($('#bayar_kembali').val());
        if(parseFloat(kembali) >= 0)
        {
            $("#proses").dialog("open");
            simpanKasir();
        }
        else
        {
            alert("Bayar tidak boleh kurang!");
            $(this).focus();
        }
    });
 });//end document.ready

$('#imei').focus();
$('#imei').jkey('down,return,f10',function(key){
    if(key=='return')
    {
        var id = $('#imei').val();
        if(id=='')
        {
            alert("Imei Tidak boleh Kosong!");
        }
        else
        {
            lihat_imei(id);
        }
    }
//    else if(key=='f12')
//    {
//        if($('#total').val()=='0' || $('#total').val()=='')
//        {
//            alert("Barang masih kosong!");
//        }
//        else
//        {
//            var tot = $('#total').autoNumericGet();
//            $('#bayar_total').autoNumericSet(tot);
//            $('#jmh_belanja').autoNumericSet(tot);
//            $('#jmh_dk').val('0');
//            
//            $('#bayar_bayar').focus();
//            $("#form-bayar" ).dialog("open");
//        }
//    }
//    else if(key=='f10')
//    {
//        print_screen();
//    }
    else
    {
        $('#barang_kd').focus();
    }
});
<?php if($grosir=='1') {  ?>
$(document).jkey('f10,f12',function(key){
    if(key=='f10')
    {
        print_screen();
    }
    else if(key=='f12')
    {
        if($('#total').val()=='0' || $('#total').val()=='')
        {
            alert("Barang masih kosong!");
        }
        else
        {
            var tot = convert_to_string($('#total').val());
            $('#bayar_total').val(convert_to_numeric(tot));
            $('#jmh_belanja').val(convert_to_numeric(tot));
            $('#bayar_bayar').val('0');
            $('#jmh_dk').val('0');
            $('#bayar_diskon').val('0');
            $('#bayar_diskon_display').val('0');
            $('#bayar_kembali').val(0-parseFloat(tot));
            $('#bayar_bayar').focus();
            $("#form-bayar" ).dialog("open");
        }
    }
});
<?php } else { ?>
$(document).jkey('f12',function(){
    if($('#total').val()=='0' || $('#total').val()=='')
    {
        alert("Barang masih kosong!");
    }
    else
    {
        var tot = convert_to_string($('#total').val());
        $('#bayar_total').val(convert_to_numeric(tot));
        $('#jmh_belanja').val(convert_to_numeric(tot));
        $('#bayar_bayar').val('0');
        $('#jmh_dk').val('0');
        $('#bayar_diskon').val('0');
        $('#bayar_diskon_display').val('0');
        $('#bayar_kembali').val(0-parseFloat(tot));
        $('#bayar_bayar').focus();
        $("#form-bayar" ).dialog("open");
    }
});
<?php } ?>
$('#barang_kd').jkey('f2,return,up,tab',function(key){
    if(key=='f2')
    {
        window.open("<?php echo base_url() ?>index.php/barang/list_barang","test","scrollbars=yes,width=650,height=400");
    }
    else if(key=='up')
    {
        $('#imei').focus();
    }
    else if(key=='tab')
    {
        if($('#barang_kd').val()!='')
        {
            lihat_barang($('#barang_kd').val());
        }
    }
    else if(key=='return')
    {
        if($('#barang_kd').val()!='')
        {
            lihat_barang($('#barang_kd').val());
        }
    }
    else
    {
        //nothing
    }
});
$('#qty').jkey('return,up,down',function(key){
    if(key=='return' || key=='down')
    {
        $('#diskon').focus();
    }
    else
    {
        $('#barang_kd').focus();
    }
});
$('#diskon').jkey('return,up',function(key){
    if(key=='return')
    {
        kd = $('#barang_kd').val();
        nm = $('#barang_nm').val();
        hg = convert_to_string($('#barang_harga').val());
        dk = $('#diskon').val();
        qy = $('#qty').val();
        im = $('#imei').val();
        jumlah = parseFloat(hg) * parseInt(qy);
        diskon = (dk/100) * jumlah;
        if(addRow(kd,nm,im,hg,dk,qy))
        {
            tambahin(jumlah-diskon);
        }
        kosongin();
    }
    else
    {
        $('#barang_kd').focus();
    }
});

//Key Pembayaran
$('#bayar_diskon').jkey('down,return',function(key){
    $('#bayar_bayar').focus();      
});
function addRow(kd,nm,imei,harga,diskon,qty) {
    
    if(ngecekbarang('',imei)=='')
    {
        var hasil = false;
        if(qty > 0 && harga > 0)
        {
            stok = $('#barang_stok').val();
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
                    var tblno = lastRow;
                	var tblkodebarang = kd+'<input type="hidden" id="displaytr_kd'+lastRow+'" value="'+kd+'">';
                	var tblnamabarang = (imei!="") ? nm+", IMEI: "+imei+'<input type="hidden" id='+'"display_imei'+lastRow+'" value="'+imei+'">' : nm;
                	var tblqty = qty;
                	var tblharga = convert_to_numeric(harga);
                	var tbldiskon = (diskon=='') ? 0 : diskon;
                    tbldiskon = tbldiskon;
                    var tdiskon = (parseFloat(tbldiskon)/100)* parseFloat(harga);
                    var tbljumlah = (parseInt(qty)*parseFloat(harga))-parseFloat(tdiskon);
                    tbljumlah = convert_to_string(tbljumlah) ;
                    var gambarhapus = '<img class="delete'+lastRow+'" src="<?php echo base_url() ?>inovapos_asset/img/bin.gif" style="margin:0;padding:0;cursor:pointer" onclick="delRow('+lastRow+')" />';
                	tbl.children().append("<tr><td>"+tblno+"</td><td>"+tblkodebarang+"</td><td>"+tblnamabarang+"</td><td style="+'"text-align:center;"'+">"+tblqty+"</td><td class='harga' style="+'"text-align:right;"'+">"+tblharga+"</td><td style="+'"text-align:right;"'+">"+tbldiskon+"</td><td style="+'"text-align:right;"'+">"+tbljumlah+"</td><td style="+'"text-align:center;"'+">"+gambarhapus+"</td></tr>");
                	//tbl.children('tbody').children().append("<tr><td>"+tblno+"</td><td>"+tblkodebarang+"</td><td>"+tblnamabarang+"</td><td style="+'"text-align:center;"'+">"+tblqty+"</td><td class='harga' style="+'"text-align:right;"'+">"+tblharga+"</td><td style="+'"text-align:center;"'+">"+tbldiskon+"</td><td style="+'"text-align:right;"'+">"+tbljumlah+"</td><td style="+'"text-align:center;"'+">"+gambarhapus+"</td></tr>");
                    
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
    
function delRow(no)
{
    //$('img.delete').click(function(){
//        var x=$(this).parent().parent();
//        console.log(x);

        //var harga = $('#tr_jmh'+no).val();
        //kurang(harga);
        var kurang = $('.delete'+no).parents("tr").children("td").eq(6).text();
        kurangin(kurang);
        $('.delete'+no).parents("tr").remove();
//        //alert($('#tr_kd'+no).val());
//        var jmhtot = parseInt(tot) - parseInt(nilai);   
//        $('#total').val(jmhtot);
    //});
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
                $('#barang_kd').val(pisahin[1]);
                $('#barang_nm').val(pisahin[2]);
                $('#barang_stok').val(pisahin[3]);
                $('#barang_harga').val(convert_to_numeric(pisahin[4]));
                if($('#imei').val()=='')
                {
                   $('#qty').focus(); 
                }
                else
                {
					var tbl = $('#display_imei1').val();
                    //alert(pisahin[1]);
					//alert(ngecekbarang(pisahin[1],$('#imei').val()));
                    //if(ngecekbarang(pisahin[1],$('#imei').val())=="")
                    //{
                        addRow(pisahin[1],pisahin[2],$('#imei').val(),pisahin[4],"",1);
                        tambahin(pisahin[4]);
                        kosongin();
                   // }
                    //else
                    //{
                    //    alert("Barang sudah masuk!");
                    //    kosongin();
                    //}
                }
            }
          }
        });
    }
    
function tambahin(nilai)
{
    var tot = convert_to_string($('#total').val());
    var jmhtot = parseInt(nilai) + parseInt(tot);
    $('#total').val(convert_to_numeric(jmhtot)); 

}
function kurangin(nilai)
{
    var tot = convert_to_string($('#total').val());
    var jmhtot = parseFloat(tot) - parseFloat(convert_to_string((nilai)));   
    $('#total').val(convert_to_numeric(jmhtot); 
    $('#imei').focus();
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
function ngecekbarang(lkd,limei)
{
	var tbl = $('#myTable');
	var lastRow = tbl.find("tr").length;
    var status = "";
	var test = "";
    if(lastRow > 1)
    {
        for (i=1; i<lastRow; i++)
        {
            nilai = ($('#display_imei'+i).val()!="") ? $('#display_imei'+i).val() : '';
            status += (String(nilai)!=String(limei)) ? "" : "E";
			test += status;
            nilaikd = ($('#displaytr_kd'+i).val()!="") ? $('#displaytr_kd'+i).val() : '';
            status += (String(nilaikd)!=String(lkd)) ? "" : "E";
			test += "#"+String(nilai)+"."+String(limei)+"."+String(nilaikd)+"."+String(lkd)+"."+String(status);
        }
    }
    return status;
}
function adnAngka2Str(nStr)
{
     nStr += '';
     x = nStr.split(',');
     x1 = x[0];
     x2 = x.length > 1 ? ',' + x[1] : '';
     var rgx = /(\d+)(\d{3})/;
     while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + '.' + '$2');
     }
     return x1 + x2;
}
function adnStr2Angka(Str)
{
     var bil =0;
     bil= Str.replace(/\./g, '');
     return bil;
}
 
</script>