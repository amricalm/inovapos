<div class="grid_12">
    <div class="module">
    	<h2 style="text-align: center;"><span>Sinkronisasi</span></h2>
        <table class="tablesorter">
            <tr>
                <td style="width:25%;text-align:center;">
                    <a href="#" id="link_update_harga">
                    <img src="<?php echo $base_img ?>/update_harga.png" id="gambarupdate" />
                    <br />
                    Update Harga
                    </a>
                </td>
                <td style="width:75%;text-align:left;vertical-align:middle">
                    <a class="submit-green" href="<?php echo base_url() ?>index.php/sinkronisasi/display_harga?iframe=true&width=100%&height=100%" id="display_update_harga" rel="prettyPhoto[iframe]">Sinkronisasi Via Internet</a>
                    <br /><br />
                    <a class="submit-green" href="<?php echo base_url() ?>index.php/sinkronisasi/display_upload_file/update_harga?iframe=true&width=60%&height=60%" id="display_upload_file_harga" rel="prettyPhoto[iframe]">Dari File</a>
                </td>
            </tr>
        </table>
    </div> <!-- End .module -->
</div>
<script type="text/javascript">
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
</script>