<div class="grid_12">
    </div>
    <div class="module" style="width:60%; align:center">
    	<h2><span>Tutup Shift dan Status Proses</span></h2>
        <div class="module-table-body">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th >Proses </th>
                        <th style="width:15%;text-align:center;">Status</th>
                        <th style="width:15%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="align-center">1</td>
                        <td>Penutupan Transaksi &nbsp;&nbsp;&nbsp; <input type="checkbox" name="tanpapenjualan" id="tanpapenjualan" value="1" /> Tanpa Penjualan</td>
                        <td><?php echo "-" ?></td>
                        <td>
                            <?php 
                                if(!$tutup_shift)
                                {
                            ?>
                                <input type="hidden" name="tujuan" value="<?php echo base_url() ?>index.php/tutup_shift/close_transaction" id="tujuan" />
                                <a href="<?php echo base_url() ?>index.php/tutup_shift/close_transaction" class="button" onclick="return cek()" id="close_transaction">
                                    <span>Proses... <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
                                </a>
                            <?php
                                }
                            ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="align-center">2</td>
                        <td>Cetak Bukti Setor</td>
                        <td><?php echo "-" ?></td>
                        <td>
                            <?php 
                                if($tutup_shift)
                                {
                            ?>
                                <a href="<?php echo base_url() ?>index.php/tutup_shift/bukti_setor" class="button">
                                    <span>Proses... <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
                                </a>
                            <?php
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-center">3</td>
                        <td>Cetak Bukti Stock</td>
                        <td><?php echo "-" ?></td>
                        <td>
                            <?php 
                                if($tutup_shift)
                                //if(false)
                                {
                            ?>
                            <a href="<?php echo base_url() ?>index.php/barang/cetak_stok_akhir_shift/sebelumnya" class="button" >
                                <span>Proses... <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
                            </a>
                            <?php
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-center">4</td>
                        <td>Download Stock</td>
                        <td><?php echo "-" ?></td>
                        <td>
                            <?php 
                                if($tutup_shift)
                                //if(false)
                                {
                            ?>
                            <a href="<?php echo base_url() ?>index.php/barang/download_stok_opname2/" class="button" >
                                <span>Proses... <img src="<?php echo $base_img ?>/plus-small.gif" width="12" height="9" /></span>
                            </a>
                            <br/>
                            <a href="<?php echo base_url() ?>index.php/export/stok_opname" class="button" >
                                <span>Excel... </span>
                            </a>
                            <?php
                                }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-center">5</td>
                        <td>Daftar Mutasi Belum Sinkronisasi</td>
                        <td><?php echo "-" ?></td>
                        <td><?php if($this->barang_mutasi_model->get_history_notsync('')->num_rows()>0) { ?>
                            <a href="<?php echo base_url() ?>index.php/mutasi/daftar/?iframe=true&width=50%&height=80%" id="display_mutasi" rel="prettyPhoto[iframe]" class="button" >
                                <span>Daftar Mutasi</span>
                            </a>
                            <?php } else {/*echo $this->db->last_query();*/ echo 'Kosong';} ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-center">6</td>
                        <td>Kirim Laporan Berkala 
                        <div>
                            <?php
                                echo form_input('tgldari','','class="tgl" id="tgldari"'); 
                                echo 's/d';
                                echo form_input('tglsampai','','class="tgl" id="tglsampai"'); 
                                echo form_submit('submitlaporan','Kirim','class="submit-green" id="submitlaporan"'); 
                            ?>
                        </div>
                        </td>
                        <td><?php echo "-" ?></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
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
function cek()
{
    var jmhmutasi = <?php echo count($this->barang_mutasi_model->kirim_mutasi('')); ?>;
    if(jmhmutasi>0)
    {
        var konf  = confirm("Peringatan!\nAda Mutasi yang belum disinkronisasi!");
        if(konf)
        {
            var href = '<?php echo base_url() ?>index.php/sinkronisasi/kirim_mutasi';
            $.post(href, function(data) {
                if(data!='')
                {
                    alert(data);
                    window.location = '<?php echo base_url() ?>index.php/<?php echo $this->uri->uri_string() ?>';
                }
                else
                {
                    alert("Periksa Koneksi Internet Anda!");
                }
            }).error(function() { alert("Periksa Koneksi Internet Anda!");return false; })
            return false;
        }
        else
        {
            var test = confirm("Anda Yakin akan melakukan Tutup Shift Tanggal <?php echo $this->session->userdata('tanggal') ?> dan Shift <?php echo $this->session->userdata('shift') ?>?")
            var tanpapenjualan = ($('#tanpapenjualan').is(':checked')) ? $('#tanpapenjualan').val() : '0';
            var href = $('#tujuan').val();
            var href_baru = href+"/"+tanpapenjualan;
            $('#close_transaction').attr('href',href_baru);
            if(test)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }
    else
    {
        var test = confirm("Anda Yakin akan melakukan Tutup Shift Tanggal <?php echo $this->session->userdata('tanggal') ?> dan Shift <?php echo $this->session->userdata('shift') ?>?")
        var tanpapenjualan = ($('#tanpapenjualan').is(':checked')) ? $('#tanpapenjualan').val() : '';
        var href = $('#tujuan').val();
        var href_baru = href+"/"+tanpapenjualan;
        $('#close_transaction').attr('href',href_baru);
        if(test)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
function post_kirimjual(tgl,shift)
{
    console.log($this);
    var gambar_update = $('#gambarkirim').attr("src");
    var gambar_ganti = '<?php echo base_url(); ?>inovapos_asset/img/ajax-loader.gif';
    $('#gambarkirim').attr("src",gambar_ganti);
    var href = '<?php echo base_url(); ?>index.php/sinkronisasi/kirim_jual';
    $.post(href, function(data) {
        alert(data);
        $('#gambarkirim').attr("src",gambar_update);
    });
}

$(document).ready(function(){
    $('#submitlaporan').bind("click",function(e)
    {   
        e.preventDefault();
        var tgldari = $('#tgldari').val();
        var tglsampai = $('#tglsampai').val();
        if(tgldari!=''&&tglsampai!='')
        {
            
        }
    });
});


</script>