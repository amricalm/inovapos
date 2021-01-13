<div class="module">
     <h2><span>Form Group Barang</span></h2>
     <div class="module-body">
        <form action="<?php echo base_url() ?>index.php/group_barang/group_barang_exec/tambah" method="POST" name="frmgroup_barang" id="frmgroup_barang">       
            <p>
                <label>Kode</label>
                <input type="text" name="kd_group" id="kd_group" class="input-long" />
            </p>
            
            <p>
                <label>Group Barang</label>
                <input type="text" name="nm_group" id="nm_group"class="input-long" />
            </p>                
            <fieldset>
                <input class="submit-green" type="submit" value="Simpan" /> 
                <input class="submit-gray" type="reset" value="Batal" />
            </fieldset>
        </form>
     </div> <!-- End .module-body -->
</div>  <!-- End .module -->