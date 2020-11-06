<div class="grid_12">
    <div class="float-right">
        <a href="<?php echo base_url() ?>index.php/user/user_form/tambah?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]" class="button">
        	<span>Tambah User <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
        </a>
    </div>
    <form action="<?php echo base_url() ?>index.php/user/daftar" method="POST">
        <table width="100%">
            <tr>
                <td width="35%">Pilih Grup User</td>
                <td><?php $grup=array(''=>'');foreach($data_group->result() as $rowgroup) : $grup[$rowgroup->group_kd]=$rowgroup->group_nm; endforeach; echo form_dropdown('grup',$grup,$cbogrup,'id="grup" class="input-medium"'); ?></td>
                <td rowspan="3" style="vertical-align:bottom;text-align:center"><!--<a href="<?php echo base_url().'index.php/barang/cetak_stok' ?>" class="submit-green" style="padding-top:10px"><img src="<?php echo $base_img.'/print.png' ?>" /></a><br />Cetak Stok--></td>
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
    	<h2><span>Daftar USER</span></h2>
        <div class="module-table-body">
        	<form action="">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:18%">Kode User</th>
                        <th style="width:18%">Nama User</th>
                        <th style="width:20%">Grup</th>
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
                        <td><?php echo $rowbarang->user_kd ?></td>
                        <td><?php echo $rowbarang->user_nm ?></td>
                        <td><?php echo $rowbarang->group_nm ?></td>
                        <?php if($this->session->userdata('user_group')!='Kasir' && $this->session->userdata('user_group')!='SPV') { ?><td>
                            <a href="<?php echo base_url() ?>index.php/user/user_form/edit/<?php echo $rowbarang->user_kd; ?>?iframe=true&amp;width=800&amp;height=600" rel="prettyPhoto[iframe]"><img src="<?php echo $base_img ?>/pencil.gif" width="16" height="16" /></a>
                            <a href="<?php echo base_url() ?>index.php/user/user_form/hapus/<?php echo $rowbarang->user_kd; ?>" onclick="return hapus()" ><img src="<?php echo $base_img ?>/bin.gif" width="16" height="16" /></a>
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

function iclose()
{
    $.prettyPhoto.close();
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}
</script>