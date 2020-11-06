<div class="grid_12">
    <div class="module">
    	<h2><span>Daftar Penjualan</span></h2>
        <div class="module-table-body">
                <table width="100%">
                    <tr>
                        <td></td>
                        <td><a href="<?php echo base_url().'index.php/laporan/penjualan' ?>" class="submit-green" >Per Barang</a></td>
                    </tr>
                </table>
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:9%">No Faktur</th>
                        <th style="width:28%">Total</th>
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
                        <td class="align-center"><?php echo $seq ?></td>
                        <td><?php echo $row->no_faktur ?><br /><br /><a href="<?php echo base_url().'index.php/kasir/cetak_dari_faktur/'.$row->no_faktur; ?>" class="submit-green">Cetak</a></td>
                        <td class="align-right">Rp.  <?php echo number_format($row->total,0,',','.') ?></td>
                        <td>Rincian : <br />
                            <table style="border:0">
                                <?php foreach($this->history_kasir_model->penjualan_dtl_per_faktur($row->no_faktur,$this->session->userdata('tanggal'),$this->session->userdata('shift'))->result() as $rowdtl) { ?>
                                <?php //echo $this->db->last_query(); ?>
                                <tr>
                                    <td style="width: 20%;"><?php echo $rowdtl->barang_kd ?></td>
                                    <td style="width: 45%;"><?php echo $rowdtl->barang_nm ?></td>
                                    <td style="width: 5%;"><?php echo $rowdtl->qty ?></td>
                                    <td style="text-align: right;width: 15%"><?php echo number_format($rowdtl->harga,0,',','.') ?></td>
                                    <td style="text-align: right;width: 15%"><?php echo number_format($rowdtl->jmh,0,',','.') ?></td>
                                </tr>
                                <?php } ?>
                            </table>
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