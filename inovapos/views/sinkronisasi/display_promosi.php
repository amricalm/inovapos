<div class="module">
    <?php //print_r($data_array) ?>
     <h2><span>Teks Promosi</span></h2>
     <?php echo form_open(base_url().'index.php/sinkronisasi/simpan_promosi/',array('id'=>'frmkirim')); ?>
     <div class="module-table-body">
        <table>
            <tr>
                <th style="width: 5px;">Teks</th>
            </tr>
        <?php
        $seq = 1;
         for($i=0;$i<count($data_array);$i++)
         {
        ?>
        <tr>
            <td><?php echo $data_array[$i]['teks_promosi']; ?></td>
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