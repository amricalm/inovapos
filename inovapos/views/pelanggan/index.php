<div class="grid_12">
    <div class="float-right">
        <!--<?php if($this->session->userdata('user_group')!='Kasir') { ?><a href="<?php echo base_url() ?>index.php/pelanggan/pelanggan_form/tambah?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]" class="button">
        	<span>Tambah Pelanggan <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
        </a><?php } ?>-->
    </div>
    <form action="<?php echo base_url() ?>index.php/pelanggan/daftar" method="POST"><input type="text" name="search" id="search" class="input-short" value="<?php echo $txtcari; ?>" /><input class="submit-green" type="submit" name="submit" value="Cari" /></form>
    <div class="module">
    	<h2><span>Daftar Pelanggan</span></h2>
        <div class="module-table-body">
        	<form action="">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:23%">Nama Pelanggan</th>
                        <th style="width:13%">Kode Member</th>
                        <th style="width:13%">Kategori</th>
                        <th style="width:31%">Alamat</th>
                        <th style="width:13%">Telp</th>
                        <!--<?php if($this->session->userdata('user_group')!='Kasir') { ?><th style="width:5%"></th><?php } ?>-->
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $seq = 1 + $this->uri->segment(3);
                        foreach($data->result() as $rowpelanggan)
                        {
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq ?></td>
                        <td><?php echo $rowpelanggan->pelanggan_nm_lengkap ?></td>
                        <td><?php echo $rowpelanggan->pelanggan_member ?></td>
                        <td><?php echo $rowpelanggan->pelanggan_kategori ?></td>
                        <td><?php echo $rowpelanggan->pelanggan_alamat.' '.$rowpelanggan->pelanggan_kota.' '.$rowpelanggan->pelanggan_provinsi ?></td>
                        <td><?php echo $rowpelanggan->pelanggan_telprumah ?></td>
                        <!--<?php if($this->session->userdata('user_group')!='Kasir') { ?><td>
                            <a href="<?php echo base_url() ?>index.php/pelanggan/pelanggan_form/edit/<?php echo $rowpelanggan->pelanggan_kd; ?>?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]"><img src="<?php echo $base_img ?>/pencil.gif" width="16" height="16" /></a>
                            <a href="<?php echo base_url() ?>index.php/pelanggan/pelanggan_form/hapus/<?php echo $rowpelanggan->pelanggan_kd; ?>" onclick="return hapus()"><img src="<?php echo $base_img ?>/bin.gif" width="16" height="16" alt="delete" /></a>
                        </td><?php } ?>-->
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