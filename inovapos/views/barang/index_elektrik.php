<div class="grid_12">
    <!--<div class="float-right">
        <a href="<?php echo base_url() ?>index.php/barang/barang_form/tambah?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]" class="button">
        	<span>Tambah Barang <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
        </a>
    </div>
    <form action="<?php echo base_url() ?>index.php/barang/daftar" method="POST">
        <table width="100%">
            <tr>
                <td width="35%">Pilih Kelompok Barang</td>
                <td><?php $grup=array(''=>'');foreach($data_group->result() as $rowgroup) : $grup[$rowgroup->group_kd]=$rowgroup->group_nm; endforeach; echo form_dropdown('grup',$grup,$cbogrup,'id="grup" class="input-medium"'); ?></td>
                <td rowspan="3" style="vertical-align:bottom;text-align:center"><a href="<?php echo base_url().'index.php/barang/cetak_stok' ?>" class="submit-green" style="padding-top:10px"><img src="<?php echo $base_img.'/print.png' ?>" /></a><br />Cetak Stok</td>
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
    </form>-->
    <div class="module">
    	<h2><span>Daftar Barang</span></h2>
        <div class="module-table-body">
        	<form action="">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:20%">Kode Barang</th>
                        <th style="width:30%">Nama Barang</th>
                        <th style="width:20%;">Harga Pokok</th>
                        <th style="width:20%">Harga</th>
                        <?php if($this->session->userdata('user_group')!='Kasir') { ?><th style="width:2%"></th><?php } ?>
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
                        <td style="text-align: right;"><?php echo number_format($rowbarang->barang_harga_pokok,0,',','.') ?></td>
                        <td style="text-align: right;"><?php echo number_format($rowbarang->barang_harga_jual,0,',','.') ?></td>
                        <?php if($this->session->userdata('user_group')!='Kasir' && $this->session->userdata('user_group')!='SPV') { ?><td>
                            <a href="<?php echo base_url() ?>index.php/barang/barang_elektrik_form/edit/<?php echo $rowbarang->barang_kd; ?>?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]"><img src="<?php echo $base_img ?>/pencil.gif" width="16" height="16" /></a>
                            <!--<a href="<?php echo base_url() ?>index.php/barang/barang_form/hapus/<?php echo $rowbarang->barang_kd; ?>" onclick="return hapus()"><img src="<?php echo $base_img ?>/bin.gif" width="16" height="16" alt="delete" /></a>-->
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