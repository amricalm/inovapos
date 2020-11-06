<div class="grid_12">
    <div class="module">
    	<h2><span>Daftar Penjualan</span></h2>
        <div class="module-table-body">
            <form action="<?php echo base_url() ?>index.php/laporan/laporan_elektrik" method="POST">
                <table width="100%">
                    <tr>
                        <td width="35%">Pilih Periode Dari :</td>
                        <td style="width: 45%;">Tanggal : <input type="text" class="tgl input-short" name="tgldari" id="tgldari" value="<?php echo $tgldarifilter ?>" /></td>
                        <td rowspan="5" style="text-align: center;">
                            <?php if($data->num_rows() > 0) { ?>
                            <strong>Periode</strong> : <br />
                            <?php echo $this->adntgl->tgl_panjang($tgldarifilter).' s/d '.$this->adntgl->tgl_panjang($tglsampaifilter) ?>
                            <br />
                            Saldo Elektrik : <br/>
                            <div style="font-size:24px">Rp. <blink><?php $saldo = $this->barang_saldo_model->get_saldo_elektrik(); echo number_format($saldo['saldo_qty'],0,',','.'); ?></div>
                            <strong>Jumlah Saldo Terjual</strong> : <br />
                            <div style="font-size:24px"><?php $jmhall = 0; foreach($data_all->result() as $rowall){$jmhall += $rowall->qty*$rowall->harga_pokok;} echo number_format($jmhall,0,',','.'); ?></div>
                            <strong>Total Penjualan</strong> : <br />
                            <div style="font-size:24px"><?php $jmhall = 0; foreach($data_all->result() as $rowall){$jmhall += ($rowall->qty*$rowall->harga);} echo number_format($jmhall,0,',','.'); ?></div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="35%">Pilih Periode Sampai :</td>
                        <td>Tanggal : <input type="text" class="tgl input-short" name="tglsampai" id="tglsampai" value="<?php echo $tglsampaifilter ?>" /></td>
                    </tr> 
                    <!--<tr>
                        <td width="35%">Shift</td>
                        <td><?php //$stat1=false; if($this->input->post('shift1')) {$stat1=true;} $stat2=false;if($this->input->post('shift2')){$stat2=true;} echo form_checkbox('shift1','1',$stat1)." Shift 1 ".form_checkbox('shift2','2',$stat2)." Shift 2 "; ?></td>
                    </tr>-->
                    <tr>
                        <td>Pencarian Kode atau Nama</td>
                        <td><input type="text" name="search" id="search" class="input-medium" value="<?php echo $txtcari; ?>" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input class="submit-green" type="submit" name="submit" value="Cari" />&nbsp;&nbsp;&nbsp;
                            <!--<a href="<?php echo base_url().'index.php/laporan/penjualan_per_faktur' ?>" class="submit-green">Per Faktur</a>-->
                        </td>
                    </tr>
                </table>
            </form>
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:20%">No Faktur</th>
                        <th style="width:9%">Kode Barang</th>
                        <th style="width:20%">Nama Barang</th>
                        <th style="width:10%">HPP</th>
                        <th style="width:10%;">Harga</th>
                        <th style="width:15%;">No. HP</th>
                        <th style="width:10%;">Terkirim</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $jmhhrg = 0;
                        $jmhhpp = 0;
                        $seq = 1 + $this->uri->segment(3);
                        foreach($data->result() as $row)
                        {
                            $qty = 0;
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq ?></td>
                        <td><?php echo $row->no_faktur; ?><span style="float: right;"><a href="<?php echo base_url().'index.php/kasir/cetak_elektrik_dari_faktur/'.$row->no_faktur; ?>" class="submit-green" id="cetak<?php echo $row->no_faktur; ?>">CETAK</a></span></td>
                        <td><?php echo $row->barang_kd; ?></td>
                        <td><?php echo $row->barang_nm; ?></td>
                        <td style="text-align: right;"><?php echo number_format($row->harga_pokok,0,',','.'); ?></td>
                        <td style="text-align: right;"><?php echo number_format($row->harga,0,',','.'); ?></td>
                        <td style="text-align: center;"><?php echo $row->no_hp; ?></td>
                        <td style="text-align: center;"><?php echo ($row->status!=1) ? '<a href="#" style="cursor:pointer" id="'.$row->no_faktur.'"><img src="'.$base_img.'/cross-on-white.gif"/></a>' : '<img src="'.$base_img.'/tick-on-white.gif"/>'; ?></td>
                    </tr>
                    <?php 
                            $jmhhrg += $row->harga;
                            $jmhhpp += $row->harga_pokok;
                            $seq++;
                        }
                    ?>
                </tbody>
                <tfoot>
                        <th style="text-align:center;" colspan="4">TOTAL</th>
                        <th style="text-align:right"><?php echo number_format($jmhhpp,0,',','.') ?></th>
                        <th style="text-align:right"><?php echo number_format($jmhhrg,0,',','.') ?></th>
                        <th colspan="2"></th>
                </tfoot>
            </table>
            </form>
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
    </div> <!-- End .module -->
    <?php echo $this->pagination->create_links(); ?>
</div>
<script type="text/javascript">
$('a[id^=OSI]').live('click',function()
{
    $("#proses").dialog("open");
    var idthis = $(this).attr('id');
    var tujuan = '<?php echo base_url(); ?>index.php/kasir/kirim_elektrik/'+idthis;
    $.post(tujuan,function(data)
    {
        //alert('"'+data+'"');
        if(data=='Sukses')
        {
            window.location = window.location;
        }
        else
        {
            alert(data);
        }
    });
})
$('a[id^=cetak]').live('click',function()
{
    $("#proses").dialog("open");
    var idthis = $(this).attr('id');
    var idthis = idthis.split('cetak');
    var tujuan = '<?php echo base_url(); ?>index.php/kasir/cetak_elektrik_dari_faktur/'+idthis[1]+'/kasir';
    $.post(tujuan,function(data)
    {
		//alert(data);return;
        window.location=window.location;
    });
})
function iclose()
{
    $.prettyPhoto.close();
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}
</script>