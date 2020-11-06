<div class="module">
     <h2><span>Upload File <?php echo $tipe ?></span></h2>
     <?php echo form_open_multipart(base_url().'index.php/sinkronisasi/simpan_harga_file/'.$this->uri->segment(3),array('id'=>'frmkirim')); ?>
     <?php echo form_hidden('data',$data); ?>
     <div class="module-table-body">
        <table>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Qty</th>
            </tr>
     <?php
        $data_from_json = json_decode($data,true);
        //print_r($data_from_json);
        $seq=1;
        for($i=0;$i<count($data_from_json);$i++)
        {
            echo '<tr>';
            echo '<td>'.$seq.'</td>';
            echo '<td>'.$data_from_json[$i]['kd_barang'].'</td>';
            echo '<td>'.$data_from_json[$i]['nm_barang'].'</td>';
            echo '<td style="text-align:right">'.number_format($data_from_json[$i]['harga'],0,',','.').'</td>';
            echo '</tr>';
            $seq++;
        } 
     ?>
        </table>
        <div style="vertical-align: middle; text-align:center;padding:10px">
            <input class="submit-green" type="submit" value="Simpan" />
        </div>
     <?php echo form_close(); ?>
     </div> <!-- End .module-body -->
</div>  <!-- End .module -->