<div class="grid_12">
    <div class="module">
    	<h2 style="text-align: center;"><span>Sinkronisasi</span></h2>
        <table class="tablesorter">
            <tr>          
                <td style="width:20%;text-align:center;">
                    <a href="javascript:tampilkanopsi('opsi_groupbarang','link_group_barang')" id="link_group_barang">
                    <img src="<?php echo $base_img ?>/stock-icon.png" id="gambargroup" />
                    <br />
                    Group Barang
                    </a>
                    <br />
                    <div id="opsi_groupbarang" style="display: normal;">
                        <a class="submit-green" href="<?php echo base_url() ?>index.php/sinkronisasi/display_group_barang?iframe=true&width=100%&height=100%" id="display_group_barang" rel="prettyPhoto[iframe]">Sinkronisasi Via Internet</a><br /><br />
                    </div>
                </td>
                <td style="width:20%;text-align:center;">
                    <a href="javascript:tampilkanopsi('opsi_updateharga','link_update_harga')" id="link_update_harga">
                    <img src="<?php echo $base_img ?>/pelanggan.png" id="gambarupdate" />
                    <br />
                    Pelanggan
                    </a>
                    <br />
                    <div id="opsi_updateharga" style="display: normal;">
                        <a class="submit-green" href="<?php echo base_url() ?>index.php/sinkronisasi/display_pelanggan?iframe=true&width=100%&height=100%" id="display_update_harga" rel="prettyPhoto[iframe]">Sinkronisasi Via Internet</a><br /><br />
                    </div>
                </td>
                <td style="width:20%;text-align:center;">
                    <a href="javascript:tampilkanopsi('opsi_diskon','link_diskon')" id="link_diskon">
                        <img src="<?php echo $base_img ?>/biaya.png" id="diskon" />
                        <br />
                        Biaya Kartu
                    </a>
                    <br />
                    <div id="opsi_diskon" style="display: normal;">
                        <a class="submit-green" href="<?php echo base_url() ?>index.php/sinkronisasi/display_biaya_kartu?iframe=true&width=300&height=200" id="display_update_harga" rel="prettyPhoto[iframe]">Sinkronisasi Via Internet</a><br /><br />
                    </div>
                </td>
                <td style="width:20%;text-align:center;">
                    <a href="javascript:tampilkanopsi('opsi_stok','link_stok')" id="link_stok">
                    <img src="<?php echo $base_img ?>/teks_promosi.png" id="stok" />
                    <br />
                    Teks Promosi
                    </a>
                    <br />
                    <div id="opsi_stok" style="display: normal;">
                        <a class="submit-green" href="<?php echo base_url() ?>index.php/sinkronisasi/display_promosi?iframe=true&width=100%&height=100%" id="display_update_harga" rel="prettyPhoto[iframe]">Sinkronisasi Via Internet</a><br /><br />
                    </div>
                </td>
                <td style="width:20%;text-align:center;">
                    <a>
                    <img src="<?php echo $base_img ?>/pelanggan.png" id="gambarupdate" />
                    <br />
                    Karyawan
                    </a>
                    <br />
                    <div id="opsi_updateharga" style="display: normal;">
                        <a class="submit-green" href="<?php echo base_url() ?>index.php/sinkronisasi/display_karyawan?iframe=true&width=100%&height=100%" id="display_update_harga" rel="prettyPhoto[iframe]">Sinkronisasi Via Internet</a><br /><br />
                    </div>
                </td>
            </tr>
        </table>
    </div> <!-- End .module -->
</div>
<script type="text/javascript">
//    function tampilkanopsi(teks,elemen)
//    {
//        $('#'+teks).show('fast');
//        $('#'+elemen).attr('href',"javascript:sembunyikanopsi('"+teks+"','"+elemen+"')");
//    }
//    function sembunyikanopsi(teks,elemen)
//    {
//        $('#'+teks).hide('fast');
//        $('#'+elemen).attr('href',"javascript:tampilkanopsi('"+teks+"','"+elemen+"')");
//    }
    function post_updateharga()
    {
        var gambar_update = $('#gambarupdate').attr("src");
        var gambar_ganti = '<?php echo base_url(); ?>inovapos_asset/img/ajax-loader.gif';
        $('#gambarupdate').attr("src",gambar_ganti);
        var href = '<?php echo base_url(); ?>index.php/sinkronisasi/simpan_harga';
        $.post(href, function(data) {
            alert(data);
            $('#gambarupdate').attr("src",gambar_update);
        });
    }
    function post_kirimjual()
    {
        var gambar_update = $('#gambarkirim').attr("src");
        var gambar_ganti = '<?php echo base_url(); ?>inovapos_asset/img/ajax-loader.gif';
        $('#gambarkirim').attr("src",gambar_ganti);
        var href = '<?php echo base_url(); ?>index.php/sinkronisasi/kirim_jual';
        $.post(href, function(data) {
            alert(data);
            $('#gambarkirim').attr("src",gambar_update);
        });
    }
    function post_kirimmutasikepusat()
    {
        var gambar_update = $('#mutasibarang').attr("src");
        var gambar_ganti = '<?php echo base_url(); ?>inovapos_asset/img/ajax-loader.gif';
        $('#mutasibarang').attr("src",gambar_ganti);
        var href = '<?php echo base_url(); ?>index.php/sinkronisasi/kirim_mutasi';
        $.post(href, function(data) {
            alert(data);
            $('#mutasibarang').attr("src",gambar_update);
        });
    }
</script>