<div class="grid_12">
    <div class="module">
    	<h2><span>Daftar Mutasi belum Sinkronisasi</span></h2>
        <div class="module-table-body" style="min-height: 250px;">
            <div class="scroll-pane horizontal-only" style="border:1px solid #999999;vertical-align:top">
                <table id="myTable" class="tablesorter">
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:40%">Tanggal</th>
                        <th style="width:40%">Jumlah Mutasi</th>
                        <th style="width:5%"></th>
                    </tr>
                    <?php
                    if($data->num_rows() > 0)
                    {
                        $seq = 1;
                        foreach($data->result() as $rowdata)
                        {
                            echo '<tr>';
                            echo '<td>'.$seq.'</td>';
                            echo '<td>'.$rowdata->tgl.'</td>';
                            echo '<td>'.$rowdata->jmh.' | <a href="#" id="lihat_mutasi" name="'.$rowdata->tgl.'" style="color:red">Lihat</a></td>';
                            echo '<td><a href="#" id="kirim_mutasi" name="'.$rowdata->tgl.'" class="submit-green">Kirim</a></td>';
                            echo '</tr>';
                        } 
                    }
                    ?>
                </table>
            </div>
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
    </div> <!-- End .module -->
</div>
<!-- Dialog Proses -->
<div id="proses" title="Sedang Proses, Mohon Tunggu!" style="text-align: center;vertical-align:center;">
    <img src="<?php echo $base_img.'/ajax-loader.gif'; ?>" />
</div>
<script type="text/javascript">
$('#kirim_mutasi').live('click',function(e){
    //$("#proses").dialog("open");
    var tgl = $(this).attr('name');
    var href = '<?php echo base_url(); ?>index.php/mutasi/kirim_mutasi/'+tgl;
    $.post(href, function(data) {
        alert(data);
        if(data=='Proses Pengiriman Sukses!')
        {
            window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
        }
    }).error(function() { alert("Ada Masalah!"); })
})
$('#lihat_mutasi').live('click',function(e){
    var tgl = $(this).attr('name');
    var href = '<?php echo base_url() ?>index.php/mutasi/lihat_mutasi/'+tgl;
    window.open(href,'test',"scrollbars=yes,width=650,height=400");

});
</script>