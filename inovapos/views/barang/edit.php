<div class="module">
     <h2><span>Form Barang</span></h2>
     <div class="module-body">
        <form action="<?php echo base_url() ?>index.php/barang/barang_exec/edit" method="POST" name="frmbarang" id="frmbarang">    
            <?php echo form_hidden('barang_kd',$this->uri->segment(4)); ?>   
            <p>
                <label>Kode Barang</label>
                <input type="hidden" name="kd_barang" id="kd_barang" class="input-long" value="<?php echo $data->row()->barang_kd ?>"/>
                <input type="text" name="kd_barang" id="kd_barang" class="input-long" value="<?php echo $data->row()->barang_kd ?>" disabled="disabled"/>
            </p>
            
            <p>
                <label>Barcode </label>
                <input type="hidden" name="barcode_barang" id="barcode_barang" class="input-long" value="<?php echo $data->row()->barang_barcode ?>"/>
                <input type="text" name="barcode_barang" id="barcode_barang" class="input-long" value="<?php echo $data->row()->barang_barcode ?>"/>
            </p>
            <p>
                <label>Nama Barang</label>
                <input type="text" name="nm_barang" id="nm_barang"class="input-long" value="<?php echo $data->row()->barang_nm ?>"/>
            </p>
             <p>
                <label>Group</label>
                <?php
                    $grup = $this->group_barang_model->get('','','','');
                    $arraygrup = array();
                    foreach($grup->result() as $rowgrup)
                    {
                        $arraygrup[trim($rowgrup->group_kd)] = trim($rowgrup->group_nm);
                    }            
                    echo form_dropdown('kd_group',$arraygrup,$data->row()->group_kd,'id="kd_group" class="input-long"');
                ?>

            </p>  
            <p>
                <label>Satuan</label>
                <?php
                    $satuan = $this->satuan_model->get('','','','');
                    $arraysatuan = array();
                    foreach($satuan->result() as $rowsatuan)
                    {
                        $arraysatuan[trim($rowsatuan->satuan_kd)] = trim($rowsatuan->satuan_nm);
                    }            
                    echo form_dropdown('kd_satuan',$arraysatuan,$data->row()->satuan_kd,'id="kd_satuan" class="input-long"');
                ?>
            </p> 
            <p>
                <label>Harga</label>
                <input type="text" name="barang_harga_jual" id="barang_harga_jual" class="input-long" value="<?php echo $data->row()->barang_harga_jual ?>" />
            <fieldset>
                <input class="submit-green" type="submit" value="Simpan" /> 
                <input class="submit-gray" type="reset" value="Batal" />
            </fieldset>
        </form>
     </div> <!-- End .module-body -->
</div>  <!-- End .module -->