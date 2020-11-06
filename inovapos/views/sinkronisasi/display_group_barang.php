<div class="module">
    <?php //print_r($data_array) ?>
     <h2><span>Group Barang</span></h2>
     <?php echo form_open(base_url().'index.php/sinkronisasi/simpan_group_barang/',array('id'=>'frmkirim')); ?>
     <div class="module-table-body">
        <table>
            <tr>
                <th style="width: 5px;">No</th>
                <th>Kode Group Barang</th>
                <th>Nama Group Barang</th>
            </tr>
        <?php
        $seq = 1;
         for($i=0;$i<count($data_array);$i++)
         {
        ?>
        <tr>
            <td><?php echo $seq; ?></td>
            <td><?php echo $data_array[$i]['kd_group']; ?></td>
            <td><?php echo $data_array[$i]['nm_group']; ?></td>
            <!--<td style="text-align: right;"><?php //echo number_format($data_array[$i]['harga'],0,',','.'); ?></td>-->
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