<div class="grid_12">
    <!--<div class="float-right">
        <a href="<?php echo base_url() ?>index.php/barang/barang_form/tambah?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]" class="button">
        	<span>Tambah Barang <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
        </a>
    </div>-->
    <form action="<?php echo base_url() ?>index.php/barang/stok_opname" method="POST" onsubmit="return valid()" id="frmopname">
        <table width="100%">
            <tr>
                <td width="15%">Kelompok Barang</td>
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
                <td rowspan="4" style="vertical-align:bottom;text-align:center"><a href="javascript:downloadfile()" class="submit-green" style="padding-top:10px"><img src="<?php echo $base_img.'/print.png' ?>" /></a><br/>Download File Stok Awal</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td><input type="text" name="tgl" id="tgl" class="tgl input-short" disabled="disabled" value="<?php echo ($this->uri->segment(3)=='') ? $this->session->userdata('tanggal') : $this->uri->segment(3); ?>" /></td>
            </tr>
            <tr>
                <td>Shift</td>
                <td><input type="text" name="shift" id="shift" class="tgl input-short" disabled="disabled" value="<?php echo $this->session->userdata('shift') ?>" /></td>
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
    </form>
    <?php 
    if($this->uri->segment(3)!='')
    {
    ?>
    <form action="<?php echo base_url() ?>index.php/barang/simpan_stok" method="POST" onsubmit="return valid()"  id="frmopname">
    <?php echo form_hidden('tgl',$tgl); ?>
    <?php echo form_hidden('shift',$shift); ?>
    
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
                        <!--<th style="width:11%;text-align:right;">Saldo</th>-->
                        <th style="width:11%;text-align:right;">Jumlah Fisik</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $seq = 1 + $this->uri->segment(5);
                        $urut = 0;
                        foreach($data->result() as $rowbarang)
                        {
                            $hasil      = $this->barang_saldo_model->saldo_hari_ini($rowbarang->barang_kd);
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq ?></td>
                        <td><?php echo $rowbarang->barang_kd ?></td>
                        <td><?php echo $rowbarang->barang_nm ?></td>
                        <td><?php echo $rowbarang->group_nm ?></td>
                        <!--<td style="text-align:right;"><?php if($rowbarang->barang_group=='10'||$rowbarang->barang_group=='60'||$rowbarang->barang_group=='70'){ echo '<a href="javascript:popupimei('."'$rowbarang->barang_kd'".')">'; } ?><?php echo $hasil['nilaisaldoawal'] ?></a></td>-->
                        <td style="text-align:left;"><?php echo (!$tutup_stok) ? form_hidden('barang_kd[]',$rowbarang->barang_kd).form_input('barang_saldo'.$urut,$hasil[0]['saldo_awal'],'id="qty'.$rowbarang->barang_kd.'" class="fisik" style="width:80%;text-align:right;"').form_hidden('group'.$rowbarang->barang_kd,$rowbarang->barang_group) : $hasil[0]['saldo_awal']; ?><img src="" id="gambar<?php echo $rowbarang->barang_kd; ?>" /></td>
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
function simpan(tgl,kdbarang,qty,elemen,shift)
{
    $.post(
        '<?php echo base_url().'index.php/barang/simpan_stok' ?>',
        {saldo_tgl:tgl,saldo_barang:kdbarang,saldo_qty:qty,saldo_shift:shift},
        function(data)
        {
            var rinci = data.split('#');
            if(rinci[0]=='S')
            {       
                    $('#'+elemen).val(rinci[3]);//Memastikan Jumlah Stok selalu sesuai dg jmh imei
                    $('#gambar'+kdbarang).attr('src','<?php echo $base_img; ?>/notification-tick.gif');
            }
            else
            {                   
                alert(data);
            }
        }
    );
}
$('.fisik').jkey('f12',function(key){
    var id = this.id;
    var pisahin = id.split('qty');
    var kdbarang = pisahin[1];
    var kdgroup = $('#group'+kdbarang).val();
    var tgl = $('#tgl').val();
    var shift = $('#shift').val();
    var qty = this.value;
    if(kdgroup!='10' && kdgroup!='60' && kdgroup!='70')
    {
        simpan(tgl,kdbarang,qty,id,shift);
    }
    else
    {
        window.open("<?php echo base_url() ?>index.php/barang/list_imei_edit/"+tgl+"/"+kdbarang+"/"+id+"/"+shift,"test","scrollbars=yes,width=650,height=400");
    }
});

function valid()
{
    var status = false;
    var id = $('#tgl').val();
    var grp = $('#group_barang').val();
    var shift = $('#shift').val();
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
	window.location = "<?php echo base_url()?>index.php/barang/download_stok_opname2/";
}
function segar()
{
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}
function iclose()
{
    $.prettyPhoto.close();
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}
</script>