<div class="module">
     <h2><span>Form Barang</span></h2>
     <div class="module-body">
        <form action="<?php echo base_url() ?>index.php/barang/barang_elektrik_exec/edit" method="POST" name="frmbarang" id="frmbarang">    
            <?php echo form_hidden('barang_kd',$this->uri->segment(4)); ?>   
            <p>
                <label>Kode </label>
                <input type="text" name="kd_barang" id="kd_barang" class="input-long" value="<?php echo $data->row()->barang_kd ?>" disabled="disabled"/>
            </p>
            
            <p>
                <label>Nama Barang</label>
                <input type="text" name="nm_barang" id="nm_barang"class="input-long" value="<?php echo $data->row()->barang_nm ?>" disabled="disabled"/>
            </p>
            <p>
                <label>Harga Pokok</label>
                <input type="text" name="barang_harga_pokok" id="barang_harga_pokok" class="input-long" value="<?php echo $data->row()->barang_harga_pokok ?>" />
            </p>
            <p>
                <label>Harga</label>
                <input type="text" name="barang_harga_jual" id="barang_harga_jual" class="input-long" value="<?php echo $data->row()->barang_harga_jual ?>" />
            </p> 
            <fieldset>
                <input class="submit-green" type="submit" value="Simpan" /> 
                <input class="submit-gray" type="reset" value="Batal" />
            </fieldset>
        </form>
     </div> <!-- End .module-body -->
</div>  <!-- End .module -->