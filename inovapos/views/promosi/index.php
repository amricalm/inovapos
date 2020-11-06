<div class="module">
     <h2><span>Teks Promosi</span></h2>
     <div class="module-body">
        <form action="<?php echo base_url() ?>index.php/promosi/simpan" method="POST">
            <p>
                <label>Teks Promosi (max 120 karakter)</label>
                <input type="text" name="promosi_teks" id="promosi_teks" class="input-long" maxlength="40" value="<?php echo $data->row()->promosi_teks ?>" />
            </p>
            <fieldset>
                <input class="submit-green" type="submit" value="Simpan" /> 
                <input class="submit-gray" type="reset" value="Batal" />
            </fieldset>
        </form>
     </div>
</div>