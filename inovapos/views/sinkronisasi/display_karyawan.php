<div class="module">
     <h2><span>Daftar Karyawan</span></h2>
     <?php echo form_open(base_url().'index.php/sinkronisasi/simpan_karyawan/',array('id'=>'frmkirim')); ?>
     <div class="module-table-body">
        <table>
            <tr>
                <th style="width: 5px;">No</th>
                <th>NIK</th>
                <th>Nama Karyawan</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>Propinsi</th>
                <th>Telp</th>
                <th>HP</th>
                <th>Email</th>
            </tr>
        <?php
        $seq = 1;
         for($i=0;$i<count($data_array);$i++)
         {
        ?>
        <tr>
            <td><?php echo $seq; ?></td>
            <td><?php echo $data_array[$i]['nik']; ?></td>
            <td><?php echo $data_array[$i]['nm_lengkap']; ?></td>
            <td><?php echo $data_array[$i]['alamat']; ?></td>
            <td><?php echo $data_array[$i]['kota']; ?></td>
            <td><?php echo $data_array[$i]['propinsi']; ?></td>
            <td><?php echo $data_array[$i]['telp']; ?></td>
            <td><?php echo $data_array[$i]['hp']; ?></td>
            <td><?php echo $data_array[$i]['email']; ?></td>
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