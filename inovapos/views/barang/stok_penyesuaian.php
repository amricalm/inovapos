<style type="text/css">
 table, th, td, tr {vertical-align:top}
</style>
<div class="grid_12">
    <!--<div class="float-right">
        <a href="<?php echo base_url() ?>index.php/barang/barang_form/tambah?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]" class="button">
        	<span>Tambah Barang <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
        </a>
    </div>-->
    <form action="<?php echo base_url() ?>index.php/barang/stok_penyesuaian" method="POST" onsubmit="return valid()" id="frmopname">
        <table style="width:100%">
            <tr>
                <td style="width:60%;padding:0;">
                    <table style="width:100%;vertical-align: top">
                        <tr>
                            <td style="width: 25%;">Kelompok Barang</td>
                            <td>
                                <?php 
                                    $groupbarang = array(''=>'');
                                    foreach($data_group->result() as $rowgroup)
                                    {
                                        $groupbarang[$rowgroup->group_kd]   = $rowgroup->group_nm;
                                    }
                                    echo form_dropdown('group_barang',$groupbarang,$kdgroup,'id="group_barang" class="input-short"') 
                                ?>
                            </td>
                            <td rowspan="4" style="vertical-align:bottom"><!--<a href="javascript:downloadfile()" class="submit-green" style="padding-top:10px"><img src="<?php echo $base_img.'/print.png' ?>" /></a>--></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td><input type="text" name="tgl" id="tgl" class="tgl input-short" value="<?php echo ($this->uri->segment(3)=='') ? $this->session->userdata('tanggal') : $this->uri->segment(3); ?>" disabled="disabled" /></td>
                        </tr>
                        <tr>
                            <td>Shift</td>
                            <td><input type="text" name="shift" id="shift" class="tgl input-short" value="<?php echo ($this->uri->segment(4)=='') ? $this->session->userdata('shift') : $this->uri->segment(4); ?>" disabled="disabled" /></td>
                        </tr>
                        <tr>
                            <td>Pencarian Kode atau Nama</td>
                            <td><input type="text" name="search" id="search" class="input-medium" value="" /></td>
                        </tr>
            
                        <tr>
                            <td></td>
                            <td><input class="submit-green" type="submit" name="submit" value="Filter" /></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 25%;">
                    <table style="width:100%;vertical-align: top">
                        <tr>
                            <td>
                                <a href="javascript:saldo_awal_baru()" class="dashboard-module">
                        	       <img src="<?php echo $base_img ?>/proses_refresh.png" width="60" height="60" id="gambarproses" />
                        	       <span>Proses menjadi<br/>Saldo Awal</span>
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>                
        </table>
    </form>
    <!--<span class="notification n-information">Isi dengan Jumlah Selisih. Secara otomatis kolom FISIK terisi dengan Jumlah Riil dari Stok.</span>-->
    <?php 
    if($this->uri->segment(3)!='')
    {
    ?>
    <form action="<?php echo base_url() ?>index.php/barang/simpan_stok" method="POST" onsubmit="return valid()"  id="frmopname">
    <?php echo form_hidden('no_faktur',$no_faktur); ?>  
    <?php echo form_hidden('url',base_url().'index.php/'.$this->uri->uri_string()) ?>
    <div class="module">
    	<h2><span>Daftar Barang</span></h2>
        <div class="module-table-body">
        	<form action="">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:10%">Kode</th>
                        <th style="width:28%">Nama Barang</th>
                        <th style="width:20%">Grup</th>
                        <th style="width:7%;text-align:right;">Saldo</th>
                        <th style="width:16%;text-align:right;">Jumlah Fisik</th>
                        <th style="width:10%;text-align:right;">Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $seq = 1 + $this->uri->segment(6);
                        $urut = 0;
                        foreach($data->result() as $rowbarang)
                        {
                            //$saldo = $this->barang_saldo_model->get_saldo($rowbarang->barang_kd);
                            //$hasil = $this->barang_saldo_model->kartu_stok($rowbarang->barang_kd,$this->session->userdata('tanggal'));
                            $hasil = $this->barang_saldo_model->saldo_hari_ini($rowbarang->barang_kd);
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq ?></td>
                        <td><?php echo $rowbarang->barang_kd ?></td>
                        <td><?php echo $rowbarang->barang_nm ?></td>
                        <td><?php echo $rowbarang->group_nm ?></td>
                        <td style="text-align:right;">
                            <?php 
                                //$saldo          = ($hasil['saldo']+$hasil['nilaisaldoawalsekarang']+$hasil['nilaimutasimasuksekarang'])-($hasil['nilaipenjualansekarang']+$hasil['nilaimutasikeluarsekarang']);
                                //echo $saldo;   
                                echo ((int)$hasil[0]['saldo_awal']+(int)$hasil[0]['mutasi_masuk']-(int)$hasil[0]['mutasi_keluar']-(int)$hasil[0]['penjualan']);
                            ?>
                        </td>
                        <td style="text-align:right;">
                            <?php 
                                //$fisik          = $hasil['saldo']+$hasil['masuk']-$hasil['keluar'];
                                $fisik          = $hasil[0]['saldo_qty'];
                                echo form_hidden('barang_kd[]',$rowbarang->barang_kd).form_input('barang_saldo'.$urut,$fisik,'id="qty'.$rowbarang->barang_kd.'" class="fisik" style="width:75%;text-align:right;"').form_hidden('group'.$rowbarang->barang_kd,$rowbarang->barang_group);
                                if($rowbarang->barang_group=='10' || $rowbarang->barang_group=='60' || $rowbarang->barang_group=='70')
                                {
                                    echo '<a href="javascript:popup_ah('."'$rowbarang->barang_kd','$rowbarang->barang_group','barang_saldo$urut'".')"><img src="'.$base_img.'/Email.png" style="width:20px" id="gbr'.$rowbarang->barang_kd.'"/></a>';
                                }
                                else
                                {
                                    echo '<a href="javascript:simpan_ah('."'$rowbarang->barang_kd','$rowbarang->barang_group'".')"><img src="'.$base_img.'/save.png" style="width:20px" id="gbr'.$rowbarang->barang_kd.'"/></a>';
                                }   
                            ?>
                        </td>
                        <td style="text-align:right;"><span id="selisih"><?php /*$selisih = $fisik - $saldo; echo $selisih;*/ echo $hasil[0]['penyesuaian']  ?></span></td>
                    </tr>
                    <?php 
                        $seq++;
                        $urut++;
                        }
                    ?>
                </tbody>
            </table>
            <div class="table-apply">
                <div style="padding: 5px;">
                    <!--<a href="<?php echo base_url().'index.php/barang/download_stok_opname/'.$this->uri->segment(3).'/'.$this->uri->segment(4) ?>" class="submit-green" type="button" >Download</a><input class="submit-green" type="submit" value="Simpan" /><input class="submit-gray" type="reset" value="Reset" />-->
                </div>
            </div>
            </form>
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
    </div> <!-- End .module -->
    <?php echo $this->pagination->create_links(); ?>
    </form>
    <?php } ?>
</div>
<script type="text/javascript">
$('.fisik').jkey('f12',function(key){
    
    var id = this.id;
    var pisahin = id.split('qty');
    var kdbarang = pisahin[1];
    var kdgroup = $('#group'+kdbarang).val();
    var tgl = $('#tgl').val();
    var qty = this.value;
    if(kdgroup!='10' && kdgroup!='60' && kdgroup!='70')
    {
        simpan(kdbarang,kdgroup,qty,id);
    }
    else
    {
        window.open("<?php echo base_url() ?>index.php/barang/list_imei_penyesuaian/"+tgl+"/"+kdbarang+"/"+id,"test","scrollbars=yes,width=650,height=400");
    }
});

function simpan(kdbarang,kdgroup,qty,elemen)
{
    $.post(
        '<?php echo base_url().'index.php/barang/simpan_stok_penyesuaian' ?>',
        {stok_kdbarang:kdbarang,stok_qty:qty,stok_kdgrup:kdgroup},
        function(data)
        {    
            var rinci = data.split('#');
            if(rinci[0]=='S')
            {       
                $('#'+elemen).val(qty);//Memastikan Jumlah Stok selalu sesuai dg jmh imei
                //$('#gambar'+kdbarang).attr('src','<?php echo $base_img; ?>/notification-tick.gif');
                window.location = window.location;
            }
            else
            {              
                alert("Ada Kesalahan!");
            }
        }
    );
}

function simpan_ah(kdbarang,kdgroup)
{
    var qty = $('#qty'+kdbarang).val();
    $('#gbr'+kdbarang).attr('src','<?php echo $base_img; ?>/ajax-loader.gif');
    $.post(
        '<?php echo base_url().'index.php/barang/simpan_stok_penyesuaian' ?>',
        {stok_kdbarang:kdbarang,stok_qty:qty,stok_kdgrup:kdgroup},
        function(data)
        {    
            var rinci = data.split('#');
            if(rinci[0]=='S')
            {       
                $('#gbr'+kdbarang).attr('src','<?php echo $base_img; ?>/save.png');
                window.location = window.location;
            }
            else
            {              
                alert("Ada Kesalahan!"+data);
                $('#gbr'+kdbarang).attr('src','<?php echo $base_img; ?>/save.png');
            }
            
        }
    );
}

function popup_ah(kdbarang,grupbarang,id)
{
    $('#gbr'+kdbarang).attr('src','<?php echo $base_img; ?>/ajax-loader.gif');
    var tgl = $('#tgl').val();
    window.open("<?php echo base_url() ?>index.php/barang/list_imei_penyesuaian/"+tgl+"/"+kdbarang+"/"+id,"test","scrollbars=yes,width=650,height=400");
}

function valid()
{
    var status = false;
    var id = $('#tgl').val();
    var shift = $('#shift').val();
    var grp = $('#group_barang').val();
    var ref = $('#frmopname').attr('action');
    if(id!='')
    {
        if(grp!='')
        {
            $('#frmopname').attr('action',ref+'/'+id+'/'+shift+'/'+grp);
        }
        else
        {
            $('#frmopname').attr('action',ref+'/'+id+'/'+shift+'/0');
        }
        status = true;
    }
    else
    {
        alert("Isi tanggal Stok Opname!");
    }
    return status;
}

function downloadfile()
{
	var tgl  = $('#tgl').val();
	window.location = "<?php echo base_url()?>index.php/barang/download_stok_opname2/"+tgl;
}
function segar()
{
    //window.location = window.location;
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}
function iclose()
{
    $.prettyPhoto.close();
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}
function saldo_awal_baru()
{
    test = confirm("Apakah anda yakin menjadikan saldo awal dari penyesuaian?");
    if(test)
    {
    $('#gambarproses').attr('src','<?php echo $base_img; ?>/ajax-loader.gif');
    $.post(
        '<?php echo base_url().'index.php/tutup_shift/saldo_awal_baru' ?>',
        //{},
        function(data)
        {    
            alert(data);
            if(data=='Sukses!')
            {
                window.location = window.location;
            }
            $('#gambarproses').attr('src','<?php echo $base_img; ?>/proses_refresh.png');
        }
    );
    }
    else
    {
    $('#gambarproses').attr('src','<?php echo $base_img; ?>/proses_refresh.png');
    }
}
</script>