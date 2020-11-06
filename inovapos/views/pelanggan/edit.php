<div class="module">
     <h2><span>Form Pelanggan</span></h2>
     <div class="module-body">
        <form action="<?php echo base_url() ?>index.php/pelanggan/pelanggan_exec/edit" method="POST">    
            <?php echo form_hidden('pelanggan_kd',$data->row()->pelanggan_kd) ?>   
            <!--<p>
                <label>Kode Member Pelanggan</label>
                <input type="text" name="pelanggan_member" id="pelanggan_member" class="input-long" value="<?php echo $data->row()->pelanggan_member ?>" />
            </p>
            -->
            <p>
                <label>Nama Pelanggan</label>
                <input type="text" name="pelanggan_nm_lengkap" id="pelanggan_nm_lengkap"class="input-long" value="<?php echo $data->row()->pelanggan_nm_lengkap ?>" />
            </p>
            <!--
             <p>
                <label>Tanggal Bergabung</label>
                <input type="text" name="pelanggan_tgl_gabung" id="pelanggan_tgl_gabung" class="tgl input-long" value="<?php echo $data->row()->pelanggan_tgl_gabung ?>" />
            </p>                             
            -->
            <p>
                <label>Alamat</label>
                <textarea name="pelanggan_alamat" id="pelanggan_alamat" cols="90" rows="7" class="input-long"><?php echo $data->row()->pelanggan_alamat ?></textarea>
            </p>
            <!--
            <p>
                <label>Kelurahan</label>
                <input type="text" name="pelanggan_kelurahan" id="pelanggan_kelurahan" class="input-long" value="<?php echo $data->row()->pelanggan_kelurahan ?>" />                                
            </p>
            
            <p>
                <label>Kecamatan</label>
                <input type="text" name="pelanggan_kecamatan" id="pelanggan_kecamatan" class="input-long" value="<?php echo $data->row()->pelanggan_kecamatan ?>" />                                
            </p>
            
            <p>
                <label>Kabupaten</label>
                <input type="text" name="pelanggan_kabupaten" id="pelanggan_kabupaten" class="input-long" value="<?php //echo $data->row()->pelanggan_kabu ?>" />                                
            </p>-->
            
            <p>
                <label>Kota</label>
                <input type="text" name="pelanggan_kota" id="pelanggan_kota" class="input-long" value="<?php echo $data->row()->pelanggan_kota ?>" />                                
            </p>
            
            <p>
                <label>Provinsi</label>
                 <select name="pelanggan_provinsi" id="pelanggan_provinsi" class="input-long">
                    <?php echo $this->app_model->list_provinsi($data->row()->pelanggan_provinsi); ?>                                   
                </select>
            </p>
            
            <p>
                <label>Kode Pos</label>
                <input type="text" name="pelanggan_kodepos" id="pelanggan_kodepos" class="input-long" value="<?php echo $data->row()->pelanggan_kodepos ?>" />                                
            </p>
            
            <p>
                <label>No.Telp Rumah</label>
                <input type="text" name="pelanggan_telprumah" id="pelanggan_telprumah" class="input-long" value="<?php echo $data->row()->pelanggan_telprumah ?>" />
            </p>
            
            <p>
                <label>No.HandPhone</label>
                <input type="text" name="pelanggan_handphone" id="pelanggan_handphone" class="input-long" value="<?php echo $data->row()->pelanggan_handphone ?>" />                               
            </p>
            
             <p>
                <label>No.Faximile</label>
                <input type="text" name="pelanggan_faximile" id="pelanggan_faximile" class="input-long" value="<?php echo $data->row()->pelanggan_faximile ?>" />                                
            </p>
            
            <p>
                <label>Alamat Email</label>
                <input type="text" name="pelanggan_email" id="pelanggan_email" class="input-long" value="<?php echo $data->row()->pelanggan_email ?>" />                               
            </p>
            <!--
            <p>
                <label>Keterangan</label>
                <textarea name="pelanggan_keterangan" id="pelanggan_keterangan" rows="7" cols="90" class="input-long"><?php echo $data->row()->pelanggan_keterangan ?></textarea>
            </p>      
            -->        
            <fieldset>
                <input class="submit-green" type="submit" value="Simpan" /> 
                <input class="submit-gray" type="reset" value="Batal" />
            </fieldset>
        </form>
     </div>
</div>