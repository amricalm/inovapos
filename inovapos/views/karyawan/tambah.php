<div class="module">
     <h2><span>Form</span></h2>
        
     <div class="module-body">
        <form action="<?php echo base_url() ?>index.php/karyawan/karyawan_exec/tambah" method="POST">
            <p>
                <label>Nama Lengkap</label>
                <input type="text" name="karyawan_nm_lengkap" id="karyawan_nm_lengkap" class="input-long" />
            </p>              
            <fieldset>
                <legend>Jenis Kelamin</legend>
                <ul>
                    <li><label><input type="radio" value="L" name="karyawan_kelamin" id="karyawan_kelamin" checked="checked" />Laki-laki</label></li>
                    <li><label><input type="radio" value="P" name="karyawan_kelamin" id="karyawan_kelamin" />Perempuan</label></li>
                </ul>
            </fieldset>
            <p>
                <label>Tempat Lahir</label>
                <input type="text" name="karyawan_tp_lahir" id="karyawan_tp_lahir" class="input-long" />
            </p>
            
            <p>
                <label>Tanggal Lahir</label>
                <input type="text" name="karyawan_tgl_lahir" id="karyawan_tgl_lahir" class="tgl input-long" />
            </p>
                
            <p>
                <label>Alamat</label>
                <textarea name="karyawan_alamat" id="karyawan_alamat" rows="7" cols="90" class="input-long"></textarea>
            </p>
            
           <p>
                <label>Kelurahan</label>
                <input type="text" name="karyawan_kelurahan" id="karyawan_kelurahan" class="input-long" />
            </p>
            
           <p>
                <label>Kecamatan</label>
                <input type="text" name="karyawan_kecamatan" id="karyawan_kecamatan" class="input-long" />
            </p>
            
           <p>
                <label>Kota</label>
                <input type="text" name="karyawan_kota" id="karyawan_kota" class="input-long" />
            </p>
            
            <p>
                <label>Kode Pos</label>
                <input type="text" name="karyawan_kodepos" id="karyawan_kodepos" class="input-short" />
            </p>     
            <p>
                <label>Status</label>
                <select name="karyawan_status" id="karyawan_status" class="input-long">
                    <?php echo $this->app_model->list_status_menikah(''); ?>
                </select>
            </p>
            
            <p>
                <label>Tingkat Pendidikan</label>
                <input type="text" name="karyawan_tk_pdd" id="karyawan_tk_pdd" class="input-long" />
            </p>
            
             <fieldset>
                <legend>Ijazah</legend>
                <ul>
                    <li><label><input type="checkbox" name="karyawan_ijazah" id="karyawan_ijazah" />Ada</label></li>
                </ul>
            </fieldset>
            
            <p>
                <label>Telpon</label>
                <input type="text" name="karyawan_telp" id="karyawan_telp" class="input-long" />
            </p>
            
            <p>
                <label>HP</label>
                <input type="text" name="karyawan_hp" id="karyawan_hp" class="input-long" />
            </p>
            
            <p>
                <label>E-mail</label>
                <input type="text" name="karyawan_email" id="karyawan_email" class="input-long" />
            </p>
            
            <p>
                <label>Tanggal Masuk</label>
                <input type="text"name="karyawan_tgl_masuk" id="karyawan_tgl_masuk" class="tgl input-long" />
            </p>
            
            <fieldset>
                <input class="submit-green" type="submit" value="Simpan" /> 
                <input class="submit-gray" type="reset" value="Batal" />
            </fieldset>
        </form>
     </div>
</div>