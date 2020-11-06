<div class="grid_12">
    <div class="module">
    	<h2><span>Daftar Tukar Barang</span></h2>
        <div class="module-table-body">
            <form action="<?php echo base_url() ?>index.php/laporan/tukar_barang" method="POST">
                <table width="100%">
                    <tr>
                        <td width="35%">Pilih Kelompok Barang</td>
                        <td><?php $grup=array(''=>'');foreach($data_group->result() as $rowgroup) : $grup[$rowgroup->group_kd]=$rowgroup->group_nm; endforeach; echo form_dropdown('grup',$grup,$cbogrup,'id="grup" class="input-medium"'); ?></td>
                    </tr>
                    <tr>
                        <td>Pencarian Kode atau Nama</td>
                        <td><input type="text" name="search" id="search" class="input-medium" value="<?php echo $txtcari; ?>" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input class="submit-green" type="submit" name="submit" value="Cari" />&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url().'index.php/laporan/penjualan_per_faktur' ?>" class="submit-green">Per Faktur</a></td>
                    </tr>
                </table>
            </form>
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:9%">Kode Barang</th>
                        <th style="width:28%">Nama Barang</th>
                        <th style="width:61%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $seq = 1 + $this->uri->segment(3);
                        $kdbarang = '';
                        foreach($data->result() as $row)
                        {
                            $qty = 0;
                    ?>
                    <tr>
                        <td class="align-center"><?php echo ($kdbarang!=$row->barang_kd) ? $seq : '' ?></td>
                        <td><?php echo ($kdbarang!=$row->barang_kd) ? $row->barang_kd : '' ?></td>
                        <td><?php echo ($kdbarang!=$row->barang_kd) ? $row->barang_nm : '' ?></td>
                        <td>
                            <table style="border:0">
                                <?php foreach($this->history_kasir_model->penjualan_dtl($row->barang_kd,$this->session->userdata('tanggal'),$this->session->userdata('shift'))->result() as $rowdtl) { ?>
                                <?php //echo $this->db->last_query(); die();?>
                                <tr>
                                    <td style="width: 35%;"><?php echo $rowdtl->no_faktur ?>&nbsp;&nbsp;&nbsp;
                                        <?php if($row->group_elektrik=='1'){ ?>
                                        <a href="<?php echo base_url().'index.php/kasir/cetak_elektrik_dari_faktur/'.$rowdtl->no_faktur; ?>" class="submit-green">Cetak</a>
                                        <?php } else { ?>
                                        <a href="<?php echo base_url().'index.php/kasir/cetak_dari_faktur/'.$rowdtl->no_faktur; ?>" class="submit-green">Cetak</a>
                                        <?php } ?>
                                    </td>
                                    <td style="width: 15%;"><?php echo $rowdtl->qty ?></td>
                                    <td style="text-align: right;width: 25%"><?php echo number_format($rowdtl->harga,0,',','.') ?></td>
                                    <td style="text-align: right;width: 25%"><?php echo number_format($rowdtl->jmh,0,',','.') ?></td>
                                </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                    <?php 
                            if($kdbarang!=$row->barang_kd)
                            {
                                $seq++;
                                $kdbarang = $row->barang_kd;
                            }
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