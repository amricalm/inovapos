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
<!--                <table id="myTable" class="myTable">
            		<thead>
            			<tr>
                            <th style="width:2%;text-align:center;" class="th-no">#</th>
            				<th style="width:15%" class="th-kdbarang">Kode Barang</th>
            				<th style="width:34%" class="th-nmbarang">Nama Barang</th>
            				<th style="width:5%" class="th-qty">Qty</th>
            				<th style="width:13%" class="th-harga">Harga</th>
            				<th style="width:13%" class="th-diskon">Diskon</th>
            				<th style="width:13%" class="th-jumlah">Jumlah</th>
            				<th style="width:5%" class="th-tombol"></th>
            			</tr>
            		</thead>
            		<tbody>
            		</tbody>
            	</table>
-->
        <div class="scroll-pane horizontal-only" style="border:1px solid #999999;vertical-align:top">
            <p style="height: 250%;margin-top: 0px">
                <table id="myTable" class="tablesorter">
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:15%">Kode Barang</th>
                        <th style="width:34%">Nama Barang</th>
                        <th style="width:5%">Qty</th>
                        <th style="width:13%">Harga</th>
                        <th style="width:13%">Diskon</th>
                        <th style="width:13%">Jumlah</th>
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
                        <td><input type="text" name="barang_nm" id="barang_nm" class="input-long" /></td>
                        <td>Stok</td>
                        <td><input type="text" name="barang_stok" id="barang_stok" class="input-long" /></td>
                        <td>Harga</td>
                        <td><input type="text" name="barang_harga" id="barang_harga" class="input-long" /></td>
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
            <span class="notification none renggang">Tekan <span style="color: red;">F2</span> untuk Lookup Tabel.Tekan tanda <span style="color: red;">panah ke atas atau ke bawah</span>, untuk mengalihkan kursor.Tekan <span style="color: red;">F12</span> untuk membayar.</span>
        </div>
    </div>
    <input type="hidden" name="bayar-total" id="bayar-total" />
    <input type="hidden" name="bayar-term" id="bayar-term" />
    <input type="hidden" name="bayar-nomor-kartu" id="bayar-nomor-kartu" />
    <input type="hidden" name="bayar-jmh-dk" id="bayar-jmh-dk" />
    <input type="hidden" name="bayar-biaya-kartu" id="bayar-biaya-kartu" />
    <input type="hidden" name="bayar-jumlah-diskon" id="bayar-jumlah" />
    <input type="hidden" name="bayar-bayar" id="bayar-bayar" />
    <input type="hidden" name="bayar-diskon" id="bayar-diskon" />
    <input type="hidden" name="bayar-kembali" id="bayar-kembali" />
    <!-- Dialog Kasir -->
    <div id="form-bayar" title="Kasir : <?php echo $this->session->userdata('user_nm') ?> | <?php echo $this->adntgl->tgl_panjang(date('Y-m-d')); ?>">
        <table>
            <tr>
                <td style="width: 150px;padding:5px">Total </td>
                <td><input type="text" class="text ui-widget-content ui-corner-all angka" id="bayar_total" name="bayar_total" style="padding:5px;text-align:right;font-size:20px" disabled="disabled"/></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 150px;padding:5px">Pembayaran </td>
                <td><?php echo form_radio('term','Tunai',true,'id="term_tunai"').'&nbsp;Tunai&nbsp;'.form_radio('term','Kredit',false,'id="term_kredit"').'&nbsp;Kredit&nbsp;'.form_radio('term','Debit',false,'id="term_debit" ').'&nbsp;Debit&nbsp;'; ?></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 150px;padding:5px">Nomor Kartu </td>
                <td><?php echo form_input('nomor_kartu','','style="padding:5px;text-align:right;font-size:20px" class="angka" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="nomor_kartu" '); ?></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 150px;padding:5px">Jumlah </td>
                <td><?php echo form_input('jmh_dk','','style="padding:5px;text-align:right;font-size:20px" class="angka" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="jmh_dk" '); ?></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 150px;padding:5px">Biaya Kartu </td>
                <td><?php echo form_input('biaya_kartu','','style="padding:5px;text-align:right;font-size:20px" class="angka" disabled=disabled value=0 class="text ui-widget-content ui-corner-all" id="biaya_kartu" '); ?></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 150px;padding:5px">Tunai</td>
                <td><input type="input" class="text ui-widget-content ui-corner-all angka" id="bayar_bayar" name="bayar_bayar" style="padding:5px;text-align:right;font-size:20px"/></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 150px;padding:5px">Diskon</td>
                <td><input type="input" class="text ui-widget-content ui-corner-all angka" id="bayar_diskon" name="bayar_diskon" style="padding:5px;text-align:right;font-size:20px;width:50px;"/><input type="input" class="text ui-widget-content ui-corner-all angka" id="bayar_diskon_display" style="padding:5px;text-align:right;font-size:20px;width:165px;" disabled="disabled"/></td>
                <td></td>
            </tr>
            <tr>
                <td style="width: 150px;padding:5px">Kembali</td>
                <td><input type="input" class="text ui-widget-content ui-corner-all angka" id="bayar_kembali" name="bayar_kembali" style="padding:5px;text-align:right;font-size:20px"/></td>
                <td></td>
            </tr>
        </table>
    </div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    function hitung_kembali()
    {
        var tot = $('#total').autoNumericGet();
        var byr = $('#bayar_bayar').autoNumericGet();
        var jmh_dk = $('#jmh_dk').autoNumericGet();
        var diskon = $('#bayar_diskon').autoNumericGet();
        var dskn = (parseFloat(diskon)/100)*parseFloat(tot);

        var biaya_kartu = $('#biaya_kartu').autoNumericGet();

        $('#bayar_diskon_display').autoNumericSet(dskn);
        var kembali =  (parseFloat(byr)+parseFloat(jmh_dk)) - (tot-parseFloat(dskn)) - parseFloat(biaya_kartu);
        $('#bayar_kembali').autoNumericSet(kembali);
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
    $('#jmh_dk').on('change',function()
    {
        var dk = $('#jmh_dk').autoNumericGet();
        var biaya = (3 * dk / 100);
        dk = parseFloat(dk) + parseFloat(biaya);
        $('#biaya_kartu').autoNumericSet(biaya);
        $(this).autoNumericSet(dk);
    });

    $('#term_tunai').on('change',function()
    {
        $('#jmh_dk').val('0');
        $('#jmh_dk').attr('disabled', 'disabled');
        $('#biaya_kartu').attr('disabled', 'disabled');
        $('#nomor_kartu').attr('disabled', 'disabled');
        $('#nomor_kartu').val('0');
        $('#biaya_kartu').val('0');
        //$('#nomor_kartu').removeAttr('disabled');
        hitung_kembali();
    });
    $('#bayar_bayar').on('keyup', hitung_kembali);
    $('#bayar_diskon').on('keyup',hitung_kembali);
    $('#jmh_dk').on('keyup',hitung_kembali);
 });

$('#imei').focus();
$('#imei').jkey('down,return,f12',function(key){
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
    else if(key=='f12')
    {
        if($('#total').val()=='0' || $('#total').val()=='')
        {
            alert("Barang masih kosong!");
        }
        else
        {
            var tot = $('#total').val();
            $('#bayar_total').val(tot);
            $('#jmh_dk').val('0');
            
            $('#bayar_bayar').focus();
            $("#form-bayar" ).dialog("open");
        }
    }
    else
    {
        $('#barang_kd').focus();
    }
});
$(document).jkey('f12',function(){
        if($('#total').val()=='0' || $('#total').val()=='')
        {
            alert("Barang masih kosong!");
        }
        else
        {
            var tot = $('#total').autoNumericGet();
            $('#bayar_total').val(tot);
            $('#bayar_bayar').val('0');
            $('#jmh_dk').val('0');
            $('#bayar_diskon').val('0');
            $('#bayar_diskon_display').val('0');
            $('#bayar_kembali').autoNumericSet(0-tot);
            $('#bayar_bayar').focus();
            $("#form-bayar" ).dialog("open");
        }
});
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
        hg = $('#barang_harga').autoNumericGet();
        dk = $('#diskon').val();
        qy = $('#qty').val();
        im = $('#imei').val();
        jumlah = parseFloat(hg) * parseInt(qy);
        diskon = (dk/100) * jumlah;
        addRow(kd,nm,im,hg,dk,qy);
        tambahin(jumlah-diskon);
        kosongin();
    }
    else
    {
        $('#barang_kd').focus();
    }
});

$('#bayar_total').on('blur',function(){$('#bayar-total').val($('#bayar_total').val());});
$('input[name=term]:checked').on('blur',function(){$('#bayar-term').val($('input[name=term]:checked').val());});
$('#nomor_kartu').on('blur',function(){$('#bayar-nomor-kartu').val($('#nomor_kartu').val());});
$('#jmh_dk').on('blur',function(){$('#bayar-jumlah').val($('#jmh_dk').val());});
$('#bayar_bayar').on('blur',function(){$('#bayar-bayar').val($('#bayar_bayar').val());});
$('#bayar_diskon').on('blur',function(){$('#bayar-diskon').val($('#bayar_diskon').val());});
$('#bayar_kembali').on('blur',function(){$('#bayar-kembali').val($('#bayar_kembali').val());});

function addRow(kd,nm,imei,harga,diskon,qty) { 
if(qty > 0 && harga > 0)
{
    stok = $('#barang_stok').val();
    if(parseInt(qty) < parseInt(stok))
    {
    	var tbl = $('#myTable');
    	var lastRow = tbl.find("tr").length;
        var tblno = lastRow;
    	var tblkodebarang = '<span id="displaytr_kd'+lastRow+'">'+kd+'</span>';
        tblkodebarang += '<input type="hidden" name="tr_kd[]" id="tr_kd'+lastRow+'" value="'+kd+'"/>';
    	var tblnamabarang = (imei!="") ? nm+", IMEI : <span id="+'display_imei'+lastRow+'">'+imei+'</span>' : nm;
        tblnamabarang = tblnamabarang + '<input type="hidden" name="tr_nm'+lastRow+'" id="tr_nm'+lastRow+'" value="'+nm+'" />';
        tblnamabarang = tblnamabarang + '<input type="hidden" name="tr_imei'+lastRow+'" id="tr_imei'+lastRow+'" value="'+imei+'" />';
    	var tblqty = qty + '<input type="hidden" name="tr_qty'+lastRow+'" id="tr_qty'+lastRow+'" value="'+qty+'" />';
    	var tblharga = adnAngka2Str(harga) + '<input type="hidden" name="tr_harga'+lastRow+'" id="tr_harga'+lastRow+'" value="'+harga+'" />';;
    	var tbldiskon = (diskon=='') ? 0 : diskon;
        tbldiskon = tbldiskon + '<input type="hidden" name="tr_diskon'+lastRow+'" id="tr_diskon'+lastRow+'" value="'+diskon+'" />';
        var tdiskon = (diskon/100)*harga;
        var tbljumlah = (qty*harga)-tdiskon;
        tbljumlah = adnAngka2Str(tbljumlah) + '<input type="hidden" name="tr_jmh'+lastRow+'" id="tr_jmh'+lastRow+'" value="'+tbljumlah+'" />';
        var gambarhapus = '<img class="delete'+lastRow+'" src="<?php echo base_url() ?>inovapos_asset/img/bin.gif" style="margin:0;padding:0;cursor:pointer" onclick="delRow('+lastRow+')" />';
    	tbl.children().append("<tr><td>"+tblno+"</td><td>"+tblkodebarang+"</td><td>"+tblnamabarang+"</td><td style="+'"text-align:center;"'+">"+tblqty+"</td><td class='harga' style="+'"text-align:right;"'+">"+tblharga+"</td><td style="+'"text-align:center;"'+">"+tbldiskon+"</td><td style="+'"text-align:right;"'+">"+tbljumlah+"</td><td style="+'"text-align:center;"'+">"+gambarhapus+"</td></tr>");
    	//tbl.children('tbody').children().append("<tr><td>"+tblno+"</td><td>"+tblkodebarang+"</td><td>"+tblnamabarang+"</td><td style="+'"text-align:center;"'+">"+tblqty+"</td><td class='harga' style="+'"text-align:right;"'+">"+tblharga+"</td><td style="+'"text-align:center;"'+">"+tbldiskon+"</td><td style="+'"text-align:right;"'+">"+tbljumlah+"</td><td style="+'"text-align:center;"'+">"+gambarhapus+"</td></tr>");
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
            $('#barang_harga').autoNumericSet(pisahin[4]);
            if($('#imei').val()=='')
            {
               $('#qty').focus(); 
            }
            else
            {
                if(ngecekbarang("",$('#imei').val())=="")
                {
                    addRow(pisahin[1],pisahin[2],$('#imei').val(),pisahin[4],"",1);
                    tambahin(pisahin[4]);
                    kosongin();
                }
                else
                {
                    alert("Barang sudah masuk!");
                    kosongin();
                }
            }
        }
      }
    });
}
function tambahin(nilai)
{
    var tot = $('#total').autoNumericGet();
    var jmhtot = parseInt(nilai) + parseInt(tot);
    $('#total').autoNumericSet(jmhtot); 

}
function kurangin(nilai)
{
    var tot = $('#total').autoNumericGet();
    var jmhtot = parseFloat(tot) - parseFloat(adnStr2Angka(nilai));   
    console.log(parseFloat(tot));
    parseFloat(parseFloat(adnStr2Angka(nilai)))
//    $('#total').val(jmhtot);
    $('#total').autoNumericSet(jmhtot); 
    $('#imei').focus();
}
function kosongin()
{
    $('#imei').val("");
    $('#barang_kd').val("");
    $('#barang_nm').val("");
    $('#stok').val("");
    $('#qty').val("");
    $('#diskon').val("");
    $('#barang_harga').val("");
    $('#imei').focus();
}
function ngecekbarang(lkd,limei)
{
	var tbl = $('#myTable');
	var lastRow = tbl.find("tr").length;
    var status = "";
    if(lastRow > 1)
    {
        for (i=1; i<lastRow; i++)
        {
            nilai = ($('#tr_imei'+i)!=null) ? $('#tr_imei'+i).val() : '';
            status += (nilai!=limei) ? "" : "E";
            nilaikd = ($('#tr_kd'+i)!=null) ? $('#tr_kd'+i).val() : '';
            status += (nilaikd!=lkd) ? "" : "E";
        }
    }
    return status;
}
/**
 * function term_pembayaran()
 * {
 *     if($('#term_kredit').is(":checked") || $('term_debit').is(":checked"))
 *     {
 *         
 *     }
 * }
 */


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