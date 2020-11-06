<div class="grid_12">
    <div class="float-right">
        <a href="<?php echo base_url() ?>index.php/karyawan/karyawan_form/tambah?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]" class="button">
        	<span>Tambah Karyawan <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
        </a>
    </div>
    <form action="<?php echo base_url() ?>index.php/karyawan/daftar" method="POST"><input type="text" name="search" id="search" class="input-short" value="<?php echo $txtcari; ?>" /><input class="submit-green" type="submit" name="submit" value="Cari" /></form>
    
    <div class="module">
    	<h2><span>Daftar Karyawan</span></h2>
        <div class="module-table-body">
        	<form action="">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:18%">Nama Karyawan</th>
                        <th style="width:20%">Alamat</th>
                        <th style="width:13%">Kelurahan</th>
                        <th style="width:13%">Kecamatan</th>
                        <th style="width:10%">Kota</th>
                        <th style="width:10%">No. HP</th>
                        <th style="width:10%">Email</th>
                        <th style="width:5%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $seq = 1;
                        foreach($data->result() as $rowkaryawan)
                        {
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq ?></td>
                        <td><?php echo $rowkaryawan->karyawan_nm_lengkap ?></td>
                        <td><?php echo $rowkaryawan->karyawan_alamat ?></td>
                        <td><?php echo $rowkaryawan->karyawan_kelurahan ?></td>
                        <td><?php echo $rowkaryawan->karyawan_kecamatan ?></td>
                        <td><?php echo $rowkaryawan->karyawan_kota ?></td>
                        <td><?php echo $rowkaryawan->karyawan_hp ?></td>
                        <td><?php echo $rowkaryawan->karyawan_email ?></td>
                        <td>
                            <a href="<?php echo base_url() ?>index.php/karyawan/karyawan_form/edit/<?php echo $rowkaryawan->karyawan_kd; ?>?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]"><img src="<?php echo $base_img ?>/pencil.gif" width="16" height="16" /></a>
                            <a href="<?php echo base_url() ?>index.php/karyawan/karyawan_form/hapus/<?php echo $rowkaryawan->karyawan_kd; ?>" onclick="return hapus()"><img src="<?php echo $base_img ?>/bin.gif" width="16" height="16" alt="delete" /></a>
                        </td>
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
</div>
<script type="text/javascript">
function iclose()
{
    $.prettyPhoto.close();
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}
</script>