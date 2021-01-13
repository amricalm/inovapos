<div class="grid_12">
    <div class="float-right">
        <a href="<?php echo base_url() ?>index.php/group_barang/group_form/tambah?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]" class="button">
        	<span>Tambah Group Barang <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
        </a>
    </div>
    <form action="<?php echo base_url() ?>index.php/group_barang/daftar" method="POST"><input type="text" name="search" id="search" class="input-short" value="<?php echo $txtcari; ?>" /><input class="submit-green" type="submit" name="submit" value="Cari" /></form>
    <div class="module">
    	<h2><span>Daftar Group Barang</span></h2>
        <div class="module-table-body">
        	<form action="">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th>Nama Barang</th>
                        <th style="width:5%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $seq = 1 + $this->uri->segment(3);
                        foreach($data->result() as $rowgroup)
                        {
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq ?></td>
                        <td><?php echo $rowgroup->group_nm ?></td>
                        <td>
                            <a href="<?php echo base_url() ?>index.php/group_barang/group_form/edit/<?php  echo $rowgroup->group_kd; ?>?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]"><img src="<?php echo $base_img ?>/pencil.gif" width="16" height="16" /></a>
                            <a href="<?php echo base_url() ?>index.php/group_barang/group_form/hapus/<?php echo $rowgroup->group_kd; ?>" onclick="return hapus()"><img src="<?php echo $base_img ?>/bin.gif" width="16" height="16" alt="delete" /></a>
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
    <?php echo $this->pagination->create_links(); ?>
</div>
<script type="text/javascript">
function iclose()
{
    $.prettyPhoto.close();
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}
</script>