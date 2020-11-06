<div class="grid_12">
    <h3 style="margin-bottom:0;">Tukar Barang</h3>
    <hr />
    <form action="<?php echo base_url() ?>index.php/tukar/simpan" method="POST" onsubmit="return valid()" id="frmopname">
        <table width="100%">
            <tr >
            <td width="15%">Tanggal</td>
                <td><?php echo form_input('tgl',$this->session->userdata('tanggal').' '.date('H:i:s'),'id="tgl" class="tgl input-short"'); ?></td>
                <td width="25%" style="text-align:right">Selisih &nbsp;&nbsp;<?php echo form_input('selisih','0','id="selisih" class="input-short" style="width:150px;text-align:right" disabled="disabled"'); ?></td>
            </tr>
            <tr>
                
            </tr>
            <tr>
                <td style="vertical-align: top;">Keterangan</td>
                <td><?php echo form_textarea('keterangan','','id="keterangan" class="input-short" style="height:35px"'); ?></td>
                <td width="25%" style="text-align:right">Nilai Retur &nbsp;&nbsp;<?php echo form_input('nilai-retur','0','id="nilai-retur" class="input-short" style="width:150px;text-align:right" disabled="disabled"'); ?></td>
            </tr>
            <tr>
                <td width="15%">No Struk Penjualan</td>
                <td><?php echo form_input('no-struk','','id="no-struk" class="input-short"'); ?><span id="tgl-struk"></span> 
                    <span>&nbsp;IMEI :&nbsp;</span><span><?php echo form_input('imei','','id="imei" class="input-short"'); ?></span>
                    <span>Pengganti : &nbsp;</span><span><?php echo form_input('imei-pengganti','','id="imei-pengganti" class="input-short" disabled="disabled"'); ?></span></td>
                <td width="25%" style="text-align:right">Nilai Pengganti &nbsp;&nbsp;<?php echo form_input('nilai-pengganti','0','id="nilai-pengganti" class="input-short" style="width:150px;text-align:right" disabled="disabled"'); ?></td>
            </tr>
        </table>
        <div class="module">
        	<h2><span>Daftar Barang</span></h2>
            <div class="module-table-body" style="overflow:auto;height:130px">
                <table id="myTable" class="tablesorter">
                    <thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:20%">Kode Barang</th>
                        <th style="width:30%">Nama Barang</th>
                        <th style="width:15%;">Harga</th>
                        <th style="width:15%">Qty</th>
                        <th style="width:15%">Qty Retur</th>
                        <th></th>
                        <!--<th style="text-align: center;vertical-align:middle;width:2%;"><a href="javascript:tambah_baris()" title="Tambah Baris"><img src="<?php echo $base_img; ?>/plus-icon.png" /></a></th>
                    --></tr>
                    </thead>
                    <tbody></tbody>

                </table>
<!--
                <div class="table-apply" style="padding: 10px;">
                    <div>
                        <input class="submit-green" type="submit" value="Simpan" />
                        <input class="submit-gray" type="reset" value="Reset" />
                    </div>
                </div>
-->
                </form>
                <div style="clear: both"></div>
            </div><!-- End .module-table-body -->                
        </div> <!-- End .module -->
        
        <div class="module">
        	<h2><span>Daftar Barang Pengganti</span></h2>
            <div class="module-table-body" style="overflow:auto;height:130px">
                <table id="myTablePengganti" class="tablesorter">
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:20%">Kode Barang</th>
                        <th style="width:30%">Nama Barang</th>
                        <th style="width:15%;">Harga</th>
                        <th style="width:15%">Qty</th>
                        <th style="text-align: center;vertical-align:middle;width:2%;"><a href="#" id="btntambah-baris-pengganti" title="Tambah Baris"><img src="<?php echo $base_img; ?>/plus-icon.png" /></a></th>
                    </tr>

                </table>
                <div class="table-apply" style="padding: 10px;">
                    <div>
                        <input type="button" class="submit-green" id="btnProses" value="Proses"/>
                        <!--
<input class="submit-green" type="submit" value="Simpan" />
-->
                        <input class="submit-gray" type="reset" value="Reset" />
                    </div>
                </div>
                </form>
                <div style="clear: both"></div>
             </div> <!-- End .module-table-body -->
        </div> <!-- End .module -->
        
    <?php echo $this->pagination->create_links(); ?>
    </form>
    
<div id="PilihBarangDialog" title="Pilih Barang">
    <div class="module" style="width: 100%;">
    	<h2><span>Daftar Barang</span></h2>
        <div class="module-table-body" style="overflow:auto;height:400px;width: 100%">
            <table id="myTableBarang" class="tablesorter" style="width: 82%;">
            <thead>
                <tr>
                    <th style="width:10%">Kode Barang</th>
                    <th style="width:30%">Nama Barang</th>
                    <th style="width:10%">Stok</th>
                    <th style="width:10%">Harga</th>
                    <th>Group HP</th>
                </tr>
            </thead>
            </table>
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
    </div> <!-- End .module -->
</div><!-- End PilihBarangDialog -->


<div id="PilihBarangPenggantiDialog" title="Pilih Barang Pengganti" style="display:none;" >
    <div class="module">
    	<h2><span>Daftar Barang</span></h2>
        <div class="module-table-body" style="overflow:auto;height:400px">
            <table id="myTablePilihPengganti" class="tablesorter">
            <thead>
                <tr>
                    <th style="width:20%">Kode Barang</th>
                    <th style="width:30%">Nama Barang</th>
                    <th style="width:15%;">Harga</th>
                </tr>
            </thead>
            </table>
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
    </div> <!-- End .module -->
</div><!-- End PilihBarangPenggantiDialog -->

<div id="PilihBarangPenggantiHPDialog" title="Pilih Barang Pengganti" style="display:none;" >
    <div class="module">
    	<h2><span>Daftar Barang</span></h2>
        <div class="module-table-body" style="overflow:auto;height:400px">
            <table id="myTablePilihPenggantiHP" class="tablesorter">
            <thead>
                <tr>
                    <th style="width:20%">Kode Barang</th>
                    <th style="width:30%">Nama Barang</th>
                    <th style="width:15%;">Harga</th>
                </tr>
            </thead>
            </table>
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
    </div> <!-- End .module -->
</div><!-- End PilihBarangPenggantiDialog -->

<div id="lstImeiDialog" title="Pilih Imei" style="display:none;">
        
<!--
        <div>
                <form action="#" onsubmit="return simpanimei()" style="text-align:left;">
                    <div style="padding: 5px;">
                        <input type="text" name="tambahimei" id="tambahimei" class="input-medium" autocomplete="off" />
                        <input type="text" name="jmhimei" id="jmhimei" disabled="disabled" value="0" class="input-short" style="width: 30px;" />
                        <input type="submit" value="Cari" class="submit-green" />
                    </div>
                </form>
         </div>
-->
         
    <div class="module">
    	<h2><span>Daftar Imei</span></h2>
        <div class="module-table-body">
            <table id="tblImei" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th>IMEI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="align-center">
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <div style="clear: both"></div>
        	
         </div> <!-- End .module-table-body -->
    </div> <!-- End .module -->
</div>


<!-- Dialog Bayar  -->
    <div id="bayar-dlg" title="Pembayaran" style="display:none;">
        <div class="module">
            <div class="" style="overflow:auto;height:250px">
                <table id="" class="">
                <tbody>
                    <tr>
                        <th style="width:10%">Selisih</th>
                        <th style="width:20%"><input type="text" id="bayar-selisih" value="" style="text-align:right;" disabled="disabled" /></th>
                    </tr>
                    <tr>
                        <th style="width:15%">Uang Tunai</th>
                        <th style="width:20%"><input type="text" id="bayar-tunai" value="0" style="text-align:right;" /></th>
                    </tr>
                    <tr>
                        <th style="width:15%">Kembali</th>
                        <th style="width:20%"><input type="text" id="bayar-kembali" value="0" style="text-align:right;" /></th>
                    </tr>
                </tbody>
                </table>
               
                <div style="clear: both"></div>
             </div> <!-- End .module-table-body -->
        </div> <!-- End .module -->
    </div><!-- End SalesDialog -->

<div id="dialog-confirm" title="Konfirmasi Simpan?" style="display:none;">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Simpan Tukar Barang, OK?</p>
</div><!-- end Dialog Konfirm -->

</div><!-- end Main Container -->


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
        var tdkd = '<td style="vertical-align: top;"><input type="text" name="barang_kd[]" value="" id="barang_kd'+lastRow+'" class="barang_kd input-short" style="width:80%" />' ;
            tdkd += '<a href="javascript:lookup('+"'barang_kd"+lastRow+"'"+')"><img src="<?php echo $base_img; ?>/notification-information.gif" title="Klik disini untuk lookup Tabel Master Barang" /></a>';
            tdkd += '<a href="javascript:lookup('+"'barang_kd"+lastRow+"'"+')"><img src="<?php echo $base_img; ?>/folder.png" title="Klik disini untuk lookup Tabel IMEI" /></a></td>';
        var tdnm = '<td style="vertical-align: top;"><input type="text" name="barang_nm'+lastRow+'" value="" id="barang_nm'+lastRow+'" class="input-short" style="width:95%" disabled="disabled" /><input type="hidden" name="barang_imei'+lastRow+'"'+' id="barang_imei'+lastRow + '"' +' value="" /></td>';
        var tdstk = '<td style="vertical-align: top;"><input type="text" name="barang_stok'+lastRow+'" id="barang_stok'+lastRow+'" class="input-short" style="width:95%;text-align:right" value="" disabled="disabled"/></td>';
        var tdqty = '<td style="vertical-align: top;"><input type="text" name="qty'+lastRow+'" value="" id="qty'+lastRow+'" class="input-short" style="width:95%;text-align:right" /></td>';
        var tdtbl = '<td style="text-align: center;vertical-align:middle"><a href="javascript:hapus_baris('+"'"+lastRow+"'"+')" title="Hapus Baris"><img src="<?php echo base_url(); ?>/inovapos_asset/img/remove-icon.png" /></a></td>';
        tbl.children().append("<tr>"+tdno+tdkd+tdnm+tdstk+tdqty+tdtbl+"</tr>");
    }

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
//LookupImei------------------------------------

function lookupImei(kd)
{
    
        var isiImei = elSelectedQtyRetur.next().val();
        var arrImei = isiImei.split('#');

        $('#tambahimei').val('');
        $('#jmhimei').val('0');
        
        $.ajax({
        url     : '<?php echo base_url(); ?>'+'index.php/tukar/getLstImeiByNoFaktur',
        type    : 'POST',
        data    : {
            kdBarang    : kd,
            NoStruk     :  $('#no-struk').val()
            },
        success : function(respon)
        {
            var msg = JSON.parse(respon);
            if(msg.IsSuccess)
            {
                var noUrut = 1;
                $('#tblImei > tbody tr').remove();
                $.each(msg.Message,function(){
                    var tdNo = "<td>"+noUrut+"</td>";
                    var tdImei = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input type="checkbox" name="imei[]" class="chkImei" value="' + msg.Message[noUrut-1].imei_no +'">' + msg.Message[noUrut-1].imei_no + '</td>';
                    $('#tblImei > tbody').append("<tr>"+tdNo+tdImei+"</tr>");
                    noUrut++;
                });
                
                for (var i = 0; i < arrImei.length; i++) {
                    $('#tblImei > tbody tr').each(function(){
                        var nilai = $(this).children('td').eq(1).children('input').val();
                        if(arrImei[i]== nilai)
                        {
                            $(this).children('td').eq(1).children('input').attr('checked','checked');
                        }
                    });
                }
                
            }
            else
            {
                alert(msg.Message);
            }
        },
        dataType: "text"  

    })//end Ajax   
        
        
    $('#lstImeiDialog').dialog(
    {
        resizable: false,
        height: 400,
        width: 600,
        modal: true,
        buttons: {
            'Tutup': function(){
                $(this).dialog('close');
            }
        }
    });//--- END #dlg-lstImei
}//---- end lookupImei---------------------------

function lookupImeiPengganti(kd)
{
    
        var isiImei = elSelectedQtyPengganti.next().val();
        var arrImei = isiImei.split('#');

        $('#tambahimei').val('');
        $('#jmhimei').val('0');
        
        $.ajax({
        url     : '<?php echo base_url(); ?>'+'index.php/tukar/getLstImeiPengganti',
        type    : 'POST',
        data    : {
            kdBarang    : kd
            },
        success : function(respon)
        {
            var msg = JSON.parse(respon);
            if(msg.IsSuccess)
            {
                var noUrut = 1;
                $('#tblImei > tbody tr').remove();
                $.each(msg.Message,function(){
                    var tdNo = "<td>"+noUrut+"</td>";
                    var tdImei = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input type="checkbox" name="imei[]" class="chkImei" value="' + msg.Message[noUrut-1].imei_no +'">' + msg.Message[noUrut-1].imei_no + '</td>';
                    $('#tblImei > tbody').append("<tr>"+tdNo+tdImei+"</tr>");
                    noUrut++;
                });
                
                for (var i = 0; i < arrImei.length; i++) {
                    $('#tblImei > tbody tr').each(function(){
                        var nilai = $(this).children('td').eq(1).children('input').val();
                        if(arrImei[i]== nilai)
                        {
                            $(this).children('td').eq(1).children('input').attr('checked','checked');
                        }
                    });
                }
                
            }
            else
            {
                alert(msg.Message);
            }
        },
        dataType: "text"  

    })//end Ajax   
        
        
    $('#lstImeiDialog').dialog(
    {
        resizable: false,
        height: 400,
        width: 600,
        modal: true,
        buttons: {
            'Tutup': function(){
                $(this).dialog('close');
            }
        }
    });//--- END #dlg-lstImei
}//---- end lookupImeiPengganti---------------------------

//------------------------------------------
function tambahBarisPengganti(KdBarang, NmBarang, Harga, Qty)
{
    var tbl = $('#myTablePengganti');
    var lastRow = tbl.find("tr").length;
    var noUrut = lastRow;
    var tdNo = "<td>"+noUrut+"</td>";
    var tdKd = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input data-kolom="kd_barang" type="text" name="barang_kd[]" value="'+KdBarang+'" id="barang_kd'+noUrut+'" class="barang_kd input-short" style="width:70%" />';
        tdKd += '&nbsp<a href="#" class="LookupBarang"><img src="<?php echo $base_img; ?>/folder_closed.png" title="Klik disini untuk lookup Tabel Master Barang" /></a></td>';
    var tdNm = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input  type="text" name="barang_nm'+noUrut+'" value="'+NmBarang+'" id="barang_nm'+noUrut+'" class="input-short" style="width:95%" disabled="disabled" /><input type="hidden" name="barang_imei'+noUrut+'"'+' id="barang_imei'+noUrut + '"' +' value="" /></td>';
    var tdHarga = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input data-kolom="harga" type="text" name="barang_harga'+noUrut+'" id="barang_harga'+noUrut+'" class="input-short" style="width:95%;text-align:right" value="'+Harga+'" disabled="disabled"/></td>';
    var tdQty = '<td style="vertical-align: top;padding:3px 5px 3px 5px;"><input data-kolom="qty" type="text" name="qty'+noUrut+'" value="'+Qty+'" id="qty'+noUrut+'" class="input-short pengganti" style="width:95%;text-align:right" /></td>';    
    var tdAksi = '<td style="text-align: center;vertical-align:middle"><a href="#" class="hapusBaris" title="Hapus Baris"><img src="<?php echo base_url(); ?>/inovapos_asset/img/remove-icon.png" /></a></td>';
    $('#myTablePengganti').append("<tr>"+tdNo+tdKd+tdNm+tdHarga+tdQty+tdAksi+"</tr>");
}

//----------- Event KeyPress ------------
function keyPressNoStruk()
{
    $.ajax({
        url     : '<?php echo base_url(); ?>'+'index.php/tukar/cariStrukJual',
        type    : 'POST',
        data    : {
            no_struk    : $('#no-struk').val()
            },
        success : function(respon)
        {
            var msg = JSON.parse(respon);
            
            if(msg.IsSuccess)
            {
                var tbl = $('#myTable');
                //var lastRow = tbl.find("tr").length;
                var noUrut = 1;
                $.each(msg.Message,function(){
                    
                    var tdNo = "<td>"+noUrut+"</td>";
                    var tdKd = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input type="text" data-kolom="kd_barang" name="barang_kd[]" value="'+this.kd_barang +'" id="barang_kd'+noUrut+'" class="barang_kd input-short" style="width:70%" />';
                        tdKd += '&nbsp<a href="#" class="LookupBarang"><img src="<?php echo $base_img; ?>/folder_closed.png" title="Klik disini untuk lookup Tabel Master Barang" /></a>';
                        tdKd += '</td>';
                    var tdNm = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input type="text" name="barang_nm'+noUrut+'" value="'+this.barang_nm +'" id="barang_nm'+noUrut+'" class="input-short" style="width:95%" disabled="disabled" /><input type="hidden" name="barang_imei'+noUrut+'"'+' id="barang_imei'+noUrut + '"' +' value="" /></td>';
                    var tdHarga = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input data-kolom="harga" type="text" name="barang_harga'+noUrut+'" id="barang_harga'+noUrut+'" class="input-short" style="width:95%;text-align:right" value="'+this.harga +'" disabled="disabled"/></td>';
                    var tdQty = '<td style="vertical-align: top;padding:3px 5px 3px 5px;"><input type="text" name="qty'+noUrut+'" value="'+this.qty +'" id="qty'+noUrut+'" class="input-short" style="width:80%;text-align:right" />';
                        tdQty += '<input type="hidden" data-kolom="imei_jual" value="" id="imei_jual'+noUrut+'" />';
                        tdQty += '&nbsp<a href="#" class="LookupImei"><img src="<?php echo $base_img; ?>/notification-information.gif" title="Klik disini untuk lookup Tabel IMEI" /></a></td>';
                    
                    var tdQtyTukar = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input data-kolom="qty-retur" type="text" name="qty-retur'+noUrut+'" value="" id="qty-retur'+noUrut+'" class="input-short retur" style="width:95%;text-align:right" />';
                        tdQtyTukar += '<input type="hidden" data-kolom="imei" value="" id="imei'+noUrut+'" /></td>';
                    
                    var tdAksi = '<td style="text-align: center;vertical-align:middle"><a href="javascript:hapus_baris('+"'"+noUrut+"'"+')" title="Hapus Baris"><img src="<?php echo base_url(); ?>/inovapos_asset/img/remove-icon.png" /></a></td>';
                    $('#myTable > tbody').append("<tr>"+tdNo+tdKd+tdNm+tdHarga+tdQty+tdQtyTukar+tdAksi+"</tr>");
                    noUrut++;
                   
                });
            }
            else
            {
                alert(msg.Message);
            }
        },
        dataType: "text"  

    })//end Ajax   
}// End keyPressNoStruk

function keyPressImei()
{
    $.ajax({
        url     : '<?php echo base_url(); ?>'+'index.php/tukar/cariImei',
        type    : 'POST',
        data    : {
            imei    : $('#imei').val()
            },
        success : function(respon)
        {
            var msg = JSON.parse(respon);
            console.log(msg.IsSuccess);
            if(msg.IsSuccess)
            {
                console.log('xxx');
                $('#imei-pengganti').attr("disabled", false);
                $('#imei-pengganti').focus();
            }
            else
            {
                alert(msg.Message);
            }
        },
        dataType: "text"  

    })//end Ajax   
}//keyPressImei


function keyPressImeiPengganti()
{
    imei = $('#imei').val();
    imeiPengganti = $('#imei-pengganti').val();
    
    $.ajax({
        url     : '<?php echo base_url(); ?>'+'index.php/tukar/getBarangByImei',
        type    : 'POST',
        data    : {
            imei    : imei,
            noStruk : $('#no-struk').val()
            },
        success : function(respon)
        {
            console.log(respon);
            var msg = JSON.parse(respon);
            if(msg.IsSuccess)
            {
                tambahBarisPengganti(msg.Message.kd_barang, msg.Message.nm_barang +', IMEI:#'+ imeiPengganti, msg.Message.harga, 1);
                HitungNilaiPengganti();
                
                $('#myTable tr').each(function(){
                    var kd = $(this).children('td').eq(1).children("input[id*='barang_kd']").val();
                    if (kd != undefined)
                    {
                        if(trim(kd) == trim(msg.Message.kd_barang))
                        {
                            var nm = $(this).children('td').eq(2).children("input[id*='barang_nm']").val();
                            nm = nm + ", IMEI: #"+ imei;
                            $(this).children('td').eq(2).children("input[id*='barang_nm']").val(nm);
                            $(this).children('td').eq(5).children('input').val("1");
                        }
                    }
                });
                HitungNilaiRetur();
            }
            else
            {
                alert(msg.Message);
            }
        },
        dataType: "text"  

    })//end Ajax   
}//keyPressImei


function HitungNilaiRetur()
{
    var jmh = 0;
    $('#myTable tr').each(function(){
        var harga = $(this).children('td').eq(3).children('input').val();
        if (harga != undefined  && harga != '')
        {   
            var qty = $(this).children('td').eq(5).children('input').val();
            if(qty!='' && qty>0)
            {
                jmh = parseFloat(jmh) + (parseFloat(harga)*parseFloat(qty));
            }
        }
    });
    $('#nilai-retur').val(jmh);
    var selisih = jmh - parseFloat($('#nilai-pengganti').val());
    $('#selisih').val(selisih);
}

function HitungNilaiPengganti()
{
    var jmh = 0;
        $('#myTablePengganti tr').each(function(){
            var harga = $(this).children('td').eq(3).children('input').val();
            
            if (harga != undefined && harga != '')
            {  
                var qty = $(this).children('td').eq(4).children('input').val();
                if(qty!='' && qty>0)
                {
                    jmh = parseFloat(jmh) + (parseFloat(harga)*parseFloat(qty));
                }
            }
        });
        $('#nilai-pengganti').val(jmh);
        var selisih = parseFloat($('#nilai-retur').val()) - jmh;
        $('#selisih').val(selisih);
}

//--- Fungsi CRUD ------------------------------------------------------------------
function simpan()
{
    console.log('Eksekusi Simpan....');
    var AoA = new Array();
    $('#myTablePengganti tr').each(function(){
        var kd_barang = $(this).find("[data-kolom='kd_barang']");
        if ($(kd_barang).val()!=undefined)
        {             
            var no_faktur ="";
            var qty = $(this).find("[data-kolom='qty']").val();
            var harga = $(this).find("[data-kolom='harga']").val();
            var imei =  $(this).find("[data-kolom='imei']").val();
            var o = new TukarDtl(no_faktur, $(kd_barang).val(),qty,harga);
            
            var arrImei = imei.split('#');
            for(var i=0;i<arrImei.length;i++)
            {
                var item_imei = new clsImei($(kd_barang).val(),arrImei[i],i);   
                o.ac_ttukar_dtl_imei.push(item_imei);
            }
            AoA.push(o);
        }
    });
        
    var AoRetur = new Array();
    $('#myTable tr').each(function(){
        var kd_barang = $(this).find("[data-kolom='kd_barang']");
        if ($(kd_barang).val()!=undefined)
        {             
            var no_faktur ="";
            var qty = $(this).find("[data-kolom='qty-retur']").val();
            var harga = $(this).find("[data-kolom='harga']").val();
            var imei =  $(this).find("[data-kolom='imei']").val();
            var o = new TukarDtl(no_faktur, $(kd_barang).val(),qty,harga);
            
            var arrImei = imei.split('#');
            
            for(var i=0;i<arrImei.length;i++)
            {
                var item_imei = new clsImei($(kd_barang).val(),arrImei[i],i);   
                o.ac_ttukar_dtl_imei.push(item_imei);
            }
            AoRetur.push(o);
        }
    });
    
    if($(AoRetur).length <1)
    {
        alert('Tidak Ada Barang yang Diretur!');
    }
    else
    {
        if($(AoA).length <1)
        {
            alert('Tidak Ada Barang Pengganti!');
        }
        else
        {
            $('#dialog-confirm').dialog({
                    resizable: false,
                    height: 200,
                    width: 400,
                    modal: true,
                    buttons: {
                        'Simpan': function(){
                            
                            var data = {
                                no_faktur       : "", // Auto
                                tgl             : $('#tgl').val(),
                                ket             : $('#keterangan').val(),
                                faktur_jual     : $('#no-struk').val(),
                                uang_tunai      : $('#bayar-selisih').val(),
                                ac_ttukar_dtl   : AoA,
                                ac_ttukar_masuk_dtl : AoRetur
                                
                            };//--- end data
                            console.log(data);
                            var json = JSON.stringify(data);
                            $.post(
                                "<?php echo base_url().'index.php/tukar/simpan' ?>",
                                {data:json},
                                function(respon){
                                    console.log(respon);
                                    var obj = JSON.parse(respon);
                                    if (obj.IsSuccess) 
                                    {
                                        location.reload();
                                    }
                                    else
                                    {
                                        //alert(obj.Message);
                                    }
                                    alert("Simpan Data: " + obj.Message);
                                },
                              "text"
                            );//---end ajax
                            $(this).dialog('close');
                        },
                        'Batal': function(){
                            $(this).dialog('close');
                        }
                    }
            });//--- end #dialog-confirm
        }
    } //--- end if($(AoRetur).length <1)
    
}// --- end function Simpan

//--- END Fungsi CRUD

function TukarDtl(no_faktur,kd_barang,qty,harga) 
{
    this.no_faktur = no_faktur;
    this.kd_barang = kd_barang;
    this.qty = adn_cnum(qty);
    this.harga = adn_cnum(harga);
    this.ac_ttukar_dtl_imei = new Array();
}

function clsImei(kd_barang,imei,urutan)
{
    this.kd_barang = kd_barang;
    this.imei      = imei;
    this.urutan    = urutan;
    this.kd_dtl    = 0;
}


    var elSelectedKdBarang;
    var elSelectedQtyRetur;
    var elSelectedQtyPengganti;
    var EditBarangPengganti = false;
    var IsImeiPengganti = false;
    
 $(document).ready(function(){
    $('#myTableBarang').dataTable({
        "bServerSide": false,
        "sAjaxSource": '<?php echo base_url(); ?>'+'index.php/tukar/pilihBarang',
        "bProcessing": true ,
        "columnDefs": [
                {
                    "targets":4,
                    "visible": false,
                    "searchable": false
                }        
            ],
        "aoColumns": [
                            {
                                "sName": "BARANG_KD",
                                "mRender": function (data, type, row) {
                                    return '<a href="#" class="pilihBtn">' + data + '</a>';}
                                
                            },
                            {
                                "sName": "BARANG_NM"
                                
                            },
                            {
                                "sName": "BARANG_STOK"
                                
                            },
                            {
                                "sName": "BARANG_HARGA_JUAL"
                                
                            },
                            {
                                "sName": "GROUP_HP"
                            }
                            
                    ]
    });
    
    $('#myTableBarang').on( 'click','a.pilihBtn', function (event) {
        event.preventDefault();
        
        var pilihanBaris = $(this).parents('tr');
        var namaBarang = pilihanBaris.children('td').eq(1).html();
        var harga = pilihanBaris.children('td').eq(3).html();

        if(EditBarangPengganti)
        {      
            var elBaris = elSelectedKdBarang.parents('tr');//.parents('tr').closest('tr');
            elBaris.children('td').eq(2).children('input').val(namaBarang);
            elBaris.children('td').eq(3).children('input').val(harga);
            elSelectedKdBarang.val($(this).text());
        }
        else
        {
            var tbl = $('#myTablePengganti');
            var lastRow = tbl.find("tr").length;
            var noUrut = lastRow;
            var tdNo = "<td>"+noUrut+"</td>";
            var tdKd = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input data-kolom="kd_barang" type="text" name="barang_kd[]" value="'+ $(this).text() +'" id="barang_kd'+noUrut+'" class="barang_kd input-short" style="width:70%" />';
                tdKd += '&nbsp<a href="#" class="LookupBarang"><img src="<?php echo $base_img; ?>/folder_closed.png" title="Klik disini untuk lookup Tabel Master Barang" /></a></td>';
            var tdNm = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input  type="text" name="barang_nm'+noUrut+'" value="' +namaBarang +'" id="barang_nm'+noUrut+'" class="input-short" style="width:95%" disabled="disabled" /><input type="hidden" name="barang_imei'+noUrut+'"'+' id="barang_imei'+noUrut + '"' +' value="" /></td>';
            var tdHarga = '<td style="vertical-align: top;padding:3px 5px 1px 5px;"><input data-kolom="harga" type="text" name="barang_harga'+noUrut+'" id="barang_harga'+noUrut+'" class="input-short" style="width:95%;text-align:right" value="'+ harga+'" disabled="disabled"/></td>';
            var tdQty = '<td style="vertical-align: top;padding:3px 5px 3px 5px;"><input data-kolom="qty" type="text" name="qty'+noUrut+'" value="" id="qty'+noUrut+'" class="input-short pengganti" style="width:80%;text-align:right" />';
                tdQty += '<input type="hidden" data-kolom="imei" value="" id="imei-pengganti'+noUrut+'" />';
                tdQty += '&nbsp<a href="#" class="LookupImeiPengganti"><img src="<?php echo $base_img; ?>/notification-information.gif" title="Klik disini untuk lookup Tabel IMEI" /></a>';
                tdQty += '</td>';    
            var tdAksi = '<td style="text-align: center;vertical-align:middle"><a href="#" class="hapusBaris" title="Hapus Baris"><img src="<?php echo base_url(); ?>/inovapos_asset/img/remove-icon.png" /></a></td>';
       
            $('#myTablePengganti').append("<tr>"+tdNo+tdKd+tdNm+tdHarga+tdQty+tdAksi+"</tr>");
        }
        $('#PilihBarangDialog').dialog('close'); 
        
    });
    
    $('#no-struk').keypress(function(event){
        if ( event.which == 13 ) {
            event.preventDefault();
            keyPressNoStruk();
        }
    });
    
    $('#imei').keypress(function(event){
        if ( event.which == 13 ) {
            event.preventDefault();
            keyPressImei();
        }
    });
    
    $('#imei-pengganti').keypress(function(event){
        if ( event.which == 13 ) {
            event.preventDefault();
            keyPressImeiPengganti();
        }
    });
    
    $('#myTable').on("click",".LookupImei",function(){
        //var elSelectedImeiBaris = $(this).parent().children('input');
        elSelectedQtyRetur = $(this).closest('tr').children('td').eq(5).children("input[id*='qty']")
        var kd = $(this).closest('tr').children('td').eq(1).children("input[id*='barang_kd']").val();
        IsImeiPengganti = false;
        lookupImei(kd);  
    });
    
    $('#myTablePengganti').on("click",".LookupImeiPengganti",function(){
        //var elSelectedImeiBaris = $(this).parent().children('input');
        elSelectedQtyPengganti = $(this).closest('tr').children('td').eq(4).children("input[id*='qty']")
        var kd = $(this).closest('tr').children('td').eq(1).children("input[id*='barang_kd']").val();
        IsImeiPengganti = true;
        lookupImeiPengganti(kd);  
    });
    
    $('#myTablePengganti').on("click",".hapusBaris",function(event){
        event.preventDefault();
        $(this).closest("tr").remove();
    });
    
    $('#myTablePengganti').on("click",".LookupBarang",function(){
        EditBarangPengganti = true;
        elSelectedKdBarang = $(this).parent().children('input');
        $('#PilihBarangDialog').dialog('open');
    });
    
    $('#PilihBarangDialog').dialog({
            autoOpen: false,
            hide: "explode",
            width: '900px',
            modal: true,
            resizable: false
    });
    
    $('#btnSimpan').click(function(){
         simpan();
    });
    
    $('#btntambah-baris-pengganti').click(function(event){
        event.preventDefault();
        EditBarangPengganti = false;
        //tambahBarisPengganti("","",0,0); 
        //elSelectedKdBarang = $(this).parent().children('input');
        $('#PilihBarangDialog').dialog('open')
    });
    
   $('#myTable').on( 'keyup','.input-short.retur', function (event) {
        HitungNilaiRetur();
    });
    
    $('#myTable').on('keypress','.input-short.retur',function(event){
        if ( event.which == 13 ) {
            event.preventDefault();
            $('#PilihBarangDialog').dialog('open');
        }
    });
    
    $('#myTablePengganti').on( 'keyup','.input-short.pengganti', function (event) {
        HitungNilaiPengganti();
    });
    
    $('#tblImei').on("change",".chkImei",function(){
        var qty = 0;
        var imei = '';
        $('#tblImei tr').each(function(){
            if($(this).children('td').eq(1).children('input').is(':checked')) {
                qty++;
                if (imei !=''){
                    imei = imei + '#';
                }
                imei += $(this).children('td').eq(1).children('input').val();
            }
        });
        
        if (IsImeiPengganti)
        {
            console.log('imei ' + imei);
            elSelectedQtyPengganti.val(qty);
            elSelectedQtyPengganti.attr('disabled','disabled');
            
            elSelectedQtyPengganti.next().val(imei);
            HitungNilaiPengganti(); 
        }
        else
        {
            elSelectedQtyRetur.val(qty);
            elSelectedQtyRetur.attr('disabled','disabled');
            elSelectedQtyRetur.next().val(imei);
            HitungNilaiRetur();
        }
    });
    
    $('#btnProses').click(function()
    {
        if(parseFloat($('#nilai-retur').val())==0 &&  parseFloat($('#nilai-pengganti').val())==0)
        {
            alert('Tidak Ada Transaksi!');
        }
        else
        {
            $('#bayar-selisih').val($('#selisih').val());
            $('#bayar-dlg').dialog(
            {
                resizable: false,
                height: 400,
                width: 400,
                modal: true,
                buttons: {
                    'Simpan': function(){
                        simpan();
                    },
                    'Batal': function(){
                        $(this).dialog('close');
                    }
                }
            });//--- END #dlg-sales
         } //else     
    });
    
    $('#bayar-tunai').on( 'keyup', function (event) {
        var kembali  = parseFloat($('#bayar-selisih').val()) + parseFloat($('#bayar-tunai').val());
        $('#bayar-kembali').val(kembali);
    });
    
   
    
 });//end document.ready())
</script>