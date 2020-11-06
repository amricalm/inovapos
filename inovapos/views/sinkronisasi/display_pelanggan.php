<div class="module">
    <?php //print_r($data_array) ?>
     <h2><span>Daftar Pelanggan</span></h2>
     <?php echo form_open(base_url().'index.php/sinkronisasi/simpan_pelanggan/',array('id'=>'frmkirim')); ?>
     <div class="module-table-body">
        <table>
            <tr>
                <th style="width: 5px;">No</th>
                <th>Kode Member</th>
                <th>Nama Pelanggan</th>
                <th>Kategori</th>
                <th>Alamat</th>
                <th>Telp</th>
                <th>Fax</th>
                <th>Email</th>
                <th>HP</th>
            </tr>
        <?php
        $seq = 1;
         for($i=0;$i<count($data_array);$i++)
         {
        ?>
        <tr>
            <td><?php echo $seq; ?></td>
            <td><?php echo $data_array[$i]['id_ps']; ?></td>
            <td><?php echo $data_array[$i]['nm_ps']; ?></td>
            <td><?php echo $data_array[$i]['nm_group_ps']; ?></td>
            <td><?php echo $data_array[$i]['alamat'].' '.$data_array[$i]['kota'].' '.$data_array[$i]['propinsi']; ?></td>
            <td style="text-align: right;"><?php echo $data_array[$i]['telp']; ?></td>
            <td style="text-align: right;"><?php echo $data_array[$i]['fax']; ?></td>
            <td style="text-align: right;"><?php echo $data_array[$i]['email']; ?></td>
            <td style="text-align: right;"><?php echo $data_array[$i]['hp']; ?></td>
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