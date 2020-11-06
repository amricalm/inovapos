<div class="grid_12">
    <div class="module">
    	<h2 style="text-align: center;"><span>Sinkronisasi</span></h2>
        <table class="tablesorter">
            <tr>
                <td style="width:25%;text-align:center;">
                    <a href="#" id="link_mutasi_barang">
                    <img src="<?php echo $base_img ?>/truck-icon.png" id="mutasibarang" />
                    <br />
                    Mutasi Barang
                    </a>
                </td>
                <td style="width:75%;text-align:left;vertical-align:middle">
                    <a class="submit-green" href="<?php echo base_url() ?>index.php/sinkronisasi/mutasi_barang?iframe=true&width=100%&height=100%" id="display_mutasi_barang" rel="prettyPhoto[iframe]">Input Mutasi Barang</a>
                    <br /><br />
                    <h3>Antar Toko</h3>
                    <a class="submit-green" href="<?php echo base_url() ?>index.php/sinkronisasi/display_upload_file/mutasi_barang?iframe=true&width=60%&height=60%" id="display_upload_file_barang" rel="prettyPhoto[iframe]">Terima Barang</a>
                    <a class="submit-green" href="<?php echo base_url() ?>index.php/sinkronisasi/daftar_mutasi_barang?iframe=true&width=100%&height=100%" id="display_daftar_mutasi_barang" rel="prettyPhoto[iframe]">Daftar Mutasi</a>
                    <br /><br />
                    <h3>Antara Toko dan Pusat</h3>
                    <a class="submit-green" name="<?php echo $this->session->userdata('tanggal') .'.' . $this->session->userdata('shift') ?>" href="#" id="sync_kirim_ke_pusat">Sync ke Pusat</a>
                    <a class="submit-green" href="<?php echo base_url() ?>index.php/sinkronisasi/terima_mutasi?iframe=true&width=100%&height=100%" id="display_terima_mutasi" rel="prettyPhoto[iframe]">Terima dari Pusat</a>
                </td>
            </tr>
        </table>
    </div> <!-- End .module -->
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#sync_kirim_ke_pusat').bind("click",function(e)
    {   
        e.preventDefault();
        var sesi =$(this).attr('name');
        var gambar_update = $('#mutasibarang').attr("src");
        var gambar_ganti = '<?php echo base_url(); ?>inovapos_asset/img/ajax-loader.gif';
        $('#mutasibarang').attr("src",gambar_ganti);
        var href = '<?php echo base_url(); ?>index.php/sinkronisasi/kirim_mutasi';
        $.post(href, function(data) {
            alert(data);
            $('#mutasibarang').attr("src",gambar_update);
        }).error(function() { alert("Ada Masalah!"); })
    }
    );
});
//    function post_kirimmutasikepusat()
//    {
//        var gambar_update = $('#mutasibarang').attr("src");
//        var gambar_ganti = '<?php echo base_url(); ?>inovapos_asset/img/ajax-loader.gif';
//        $('#mutasibarang').attr("src",gambar_ganti);
//        var href = '<?php echo base_url(); ?>index.php/sinkronisasi/kirim_mutasi';
//        $.post(href, function(data) {
//            alert(data);
//            $('#mutasibarang').attr("src",gambar_update);
//        });
//    }
</script>