<div class="grid_12">
    <div class="module">
    	<h2><span>Daftar Penjualan</span></h2>
        <div class="module-table-body">
            <form action="<?php echo base_url() ?>index.php/laporan/rekap_penjualan" method="POST">
                <table width="100%">
                    <tr>
                        <td width="35%">Pilih Periode Dari :</td>
                        <td style="width: 45%;">Tanggal : <input type="text" class="tgl input-short" name="tgldari" id="tgldari" value="<?php echo $tgldarifilter ?>" /></td>
                        <td rowspan="5" style="text-align: center;">
                            <?php if($data->num_rows() > 0) { ?>
                            <strong>Periode</strong> : <br />
                            <?php echo $this->adntgl->tgl_panjang($tgldarifilter).' s/d '.$this->adntgl->tgl_panjang($tglsampaifilter) ?>
                            <br />
                            <strong>Jumlah Barang Terjual</strong> : <br />
                            <div style="font-size:24px"><?php $jmhall = 0; foreach($data_all->result() as $rowall){$jmhall += $rowall->jumlah;} echo $jmhall; ?></div>
                            <strong>Total Penjualan</strong> : <br />
                            <div style="font-size:24px"><?php $jmhall = 0; foreach($data_all->result() as $rowall){$jmhall += ($rowall->jumlah*$rowall->harga);} echo number_format($jmhall,0,',','.'); ?></div>
                            <div><a href="<?php echo base_url() ?>index.php/export/export_rekap_penjualan" class="submit-green" style="text-decoration:none;"><img src="<?php echo base_url() ?>/inovapos_asset/img/excel.png"> Unduh Laporan</a></div>
                            <?php } ?>
                        </td>
                    </tr> 
                    <tr>
                        <td width="35%">Pilih Periode Sampai :</td>
                        <td>Tanggal : <input type="text" class="tgl input-short" name="tglsampai" id="tglsampai" value="<?php echo $tglsampaifilter ?>" /></td>
                    </tr> 
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
                        <td><input class="submit-green" type="submit" name="submit" value="Cari" />&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                </table>
            </form>
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:8%">Tanggal</th>
                        <th style="width:10%">No Faktur</th>
                        <th style="width:8%">Kode Barang</th>
                        <th style="width:36%">Nama Barang</th>
                        <th style="width:6%">Qty</th>
                        <th style="width:15%">Harga</th>
                        <th style="width:15%">Total</th>
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
                        <td><?php echo $row->tgl ?></td>
                        <td><?php echo $row->no_faktur ?></td>
                        <td><?php echo $row->barang_kd ?></td>
                        <td><?php echo $row->barang_nm ?></td>
                        <td><?php echo $row->jumlah ?></td>
                        <td style="text-align: right;"><?php echo number_format($row->harga,0,',','.'); ?></td>
                        <td style="text-align: right;"><?php echo number_format(($row->harga*$row->jumlah),0,',','.'); ?></td>
                    </tr>
                    <?php $seq++;
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