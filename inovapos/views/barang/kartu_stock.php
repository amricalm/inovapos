<div class="grid_12">
    <!--<div class="float-right">
        <a href="<?php echo base_url() ?>index.php/barang/barang_form/tambah?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]" class="button">
        	<span>Tambah Barang <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
        </a>
    </div>-->
    <form action="<?php echo base_url() ?>index.php/barang/kartu_stock" method="POST">
        <table width="100%">
            <tr>
                <td width="35%">Tanggal</td>
                <td><input type="text" name="tanggal" class="tgl input-medium" value="<?php echo $this->session->userdata('tanggal'); ?>" /></td>
                
            </tr>
            <tr>
                <td width="35%">Shift</td>
                <td><?php 
                    echo '<span style="vertical-align:middle">';
                    echo ($this->session->userdata('shift')=='1') ? form_radio('shift','1',true).' 1 </span>' : form_radio('shift','1').' 1 </span>';
                    echo '<span style="vertical-align:middle">';
                    echo ($this->session->userdata('shift')=='2') ? form_radio('shift','2',true).' 2 </span>' : form_radio('shift','2').' 2 </span>' ?></td>
            </tr>
            <tr>
                <td width="35%">Pilih Kelompok Barang</td>
                <td><?php $grup=array(''=>'');foreach($data_group->result() as $rowgroup) : $grup[$rowgroup->group_kd]=$rowgroup->group_nm; endforeach; echo form_dropdown('grup',$grup,$cbogrup,'id="grup" class="input-medium"'); ?></td>
                <td rowspan="3" style="vertical-align:bottom"><a href="<?php echo base_url().'index.php/barang/cetak_stok' ?>" class="submit-green" style="padding-top:10px"><img src="<?php echo $base_img.'/print.png' ?>" /></a><br/>Cetak Stok</td>
            </tr>
            <tr>
                <td>Pencarian Kode atau Nama</td>
                <td><input type="text" name="search" id="search" class="input-medium" value="<?php echo $txtcari; ?>" /></td>
            </tr>
            <tr>
                <td></td>
                <td><input class="submit-green" type="submit" name="submit" value="Cari" /></td>
            </tr>
        </table>
    </form>
    <!--<span class="notification none renggang">
        Kartu Stok yang muncul, adalah Kartu Stok untuk Tanggal dan Shift yang berlaku.
    </span>-->
    <div class="module">
    	<h2><span>Daftar Barang</span></h2>
        <div class="module-table-body">
        	<form action="">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:8%">Kode</th>
                        <th style="width:18%">Nama Barang</th>
                        <th style="width:10%">Saldo Awal</th>
                        <th style="width:8%;">Masuk</th>
                        <th style="width:9%">Keluar</th>
                        <th style="width:10%">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $seq = 1 + $this->uri->segment(3);
                        foreach($data->result() as $rowbarang)
                        {
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq ?></td>
                        <td><?php echo $rowbarang->barang_kd ?></td>
                        <td><?php echo $rowbarang->barang_nm ?></td>
                        <?php
                            $tgl            = $this->session->userdata('tanggal');
                            $saldo          = $this->barang_saldo_model->saldo_hari_ini($rowbarang->barang_kd);
                            $penyesuaianpositif = ($saldo[0]['penyesuaian']>0) ? $saldo[0]['penyesuaian'] : 0;
                            $penyesuaiannegatif = ($saldo[0]['penyesuaian']<0) ? $saldo[0]['penyesuaian'] : 0;
                        ?> 
                        <td><?php echo number_format($saldo[0]['saldo_awal'],0,',','.') ?></td>
                        <td><?php echo number_format($saldo[0]['pembelian']+$saldo[0]['mutasi_masuk']+$saldo[0]['tukar_masuk']+$penyesuaianpositif,0,',','.') ?></td>
                        <td><?php echo number_format($saldo[0]['mutasi_keluar']+$saldo[0]['tukar_keluar']+$penyesuaiannegatif+$saldo[0]['penjualan'],0,',','.') ?></td>
                        <td><?php echo number_format(($saldo[0]['saldo_qty']),0,',','.') ?></td>
                        
                    </tr>
                    <?php 
                        $seq++;
                        }
                    ?>
                </tbody>
            </table>
            </form>
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
    </div> <!-- End .module -->
    <?php echo $this->pagination->create_links(); ?>
</div>
<script type="text/javascript">
function iclose()
{
    $.prettyPhoto.close();
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}
</script>