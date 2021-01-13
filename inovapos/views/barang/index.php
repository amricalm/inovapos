<div class="grid_12">
    <div class="float-right">
        <a href="<?php echo base_url() ?>index.php/barang/barang_form/tambah?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]" class="button">
        	<span>Tambah Barang <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
        </a>
    </div>
    <form action="<?php echo base_url() ?>index.php/barang/daftar" method="POST">
        <table width="100%">
            <tr>
                <td width="35%">Pilih Kelompok Barang</td>
                <td><?php $grup=array(''=>'');foreach($data_group->result() as $rowgroup) : $grup[$rowgroup->group_kd]=$rowgroup->group_nm; endforeach; echo form_dropdown('grup',$grup,$cbogrup,'id="grup" class="input-medium"'); ?></td>
                <td rowspan="3" style="vertical-align:bottom;text-align:right"><a href="<?php echo base_url() ?>index.php/export/export_daftar_barang" class="submit-green" style="text-decoration:none;"><img src="<?php echo base_url() ?>/inovapos_asset/img/excel.png"> Unduh Daftar Barang</a></td>
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
    <div class="module">
    	<h2><span>Daftar Barang</span></h2>
        <div class="module-table-body">
        	<form action="">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:10%">Kode Barang</th>
                        <th style="width:10%">Barcode</th>
                        <th style="width:20%">Nama Barang</th>
                        <th style="width:8%">Satuan</th>
                        <th style="width:8%">Grup</th>
                        <th style="width:8%">Stok</th>
                        <th style="width:10%">Harga Jual</th>
                        <?php if($this->session->userdata('user_group')!='Kasir') { ?><th style="width:4%"></th><?php } ?>
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
                        <td><?php echo $rowbarang->barang_barcode ?></td>
                        <td><?php echo $rowbarang->barang_nm ?></td>
                        <td><?php echo $rowbarang->satuan_nm ?></td>
                        <td><?php echo $rowbarang->group_nm ?></td>
                        <td><?php 
                            //$hasil = /*$this->barang_saldo_model->get_saldox($rowbarang->barang_kd)*/$this->barang_saldo_model->kartu_stok($rowbarang->barang_kd,$this->session->userdata('tanggal'));echo $hasil['saldo']+$hasil['masuk']-$hasil['keluar']
                            if($rowbarang->group_elektrik=='' || $rowbarang->group_elektrik=='0')
                            {
                                $saldo          = $this->barang_saldo_model->saldo_hari_ini($rowbarang->barang_kd);
                                if($rowbarang->group_kd=='10'||$rowbarang->group_kd=='60'||$rowbarang->group_kd=='70')
                                {
                                    echo '<a href="javascript:lihat_imei_aja('."'$rowbarang->barang_kd'".')">'.$saldo[0]['saldo_qty'].'</a>'; 
                                }
                                else
                                {
                                    echo ''.$saldo[0]['saldo_qty'].'';
                                }
                            }
                            else
                            {
                                $saldo          = $this->barang_saldo_model->saldo_elektrik_hari_ini($rowbarang->barang_kd);
                                echo ''.$saldo[0]['saldo_qty'].'';
                            }
                            ?>
                        </td>
                        <td style="text-align: right;"><?php echo number_format($rowbarang->barang_harga_jual,0,',','.') ?></td>
                        <?php if($this->session->userdata('user_group')!='Kasir' && $this->session->userdata('user_group')!='SPV') { ?><td>
                            <a href="<?php echo base_url() ?>index.php/barang/barang_form/edit/<?php echo $rowbarang->barang_kd; ?>?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]"><img src="<?php echo $base_img ?>/pencil.gif" width="16" height="16" /></a>
                            <a href="<?php echo base_url() ?>index.php/barang/barang_form/hapus/<?php echo $rowbarang->barang_kd; ?>" onclick="return hapus()"><img src="<?php echo $base_img ?>/bin.gif" width="16" height="16" alt="delete" /></a>
                        </td><?php } ?>
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
function lihat_imei_aja(kdbarang)
{
    mywindow = window.open("<?php echo base_url() ?>index.php/barang/list_imei_doang/"+kdbarang,"list_imei_doang","scrollbars=yes,width=650,height=400");
}
function iclose()
{
    $.prettyPhoto.close();
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}
</script>