<div class="module">
    <?php //print_r($data_array) ?>
     <h2><span>Upload File <?php echo $tipe ?></span></h2>
     <div class="module-table-body">
     <?php echo form_open_multipart(base_url().'index.php/sinkronisasi/display_file/'.$this->uri->segment(3),array('id'=>'frmkirim')); ?>
        <div style="vertical-align: middle; text-align:center;padding:10px">
            <input type="file" name="file" id="file" value="" />
            <input class="submit-green" type="submit" value="Lihat" />
        </div>
     <?php echo form_close(); ?>
     </div> <!-- End .module-body -->
</div>  <!-- End .module -->