<div class="module">
    <?php //print_r($data_array) ?>
     <h2><span>Biaya Kartu</span></h2>
     <?php echo form_open(base_url().'index.php/sinkronisasi/simpan_biaya_kartu/',array('id'=>'frmkirim')); ?>
     <div class="module-table-body">
        <table style="width: 100%;">
        <?php
         $seq = 1;
         for($i=0;$i<count($data_array);$i++)
         {
        ?>
        <tr>
            <td>Biaya Kartu : <input type="text" name="biaya_kartu" class="input-short" style="text-align: right;" id="biaya_kartu" value="<?php echo $data_array[$i]['sys_val'] ?>" />%</td>
        </tr>
        <?php
            $seq++;
         }
        ?>    
        </table>
        <div style="vertical-align: middle; text-align:center">
            <input class="submit-green" type="submit" value="Simpan" />
        </div>
     </div> <!-- End .module-body -->
     </form>
</div>  <!-- End .module -->