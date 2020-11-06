<div class="module">
     <h2><span>Upload File <?php echo $tipe ?></span></h2>
     <?php echo form_open_multipart(base_url().'index.php/sinkronisasi/simpan_mutasi_file/',array('id'=>'frmkirim')); ?>
     <?php echo form_hidden('sumber','file') ?>
     <div class="module-table-body">
        <table>
     <?php
     $data_array            = json_decode($data,true);
     for($i=0;$i<count($data_array);$i++)
     {
     ?>
        <tr>
            <td style="width: 20%;">Dari Gudang</td>
            <td><?php $gudangasal = $this->outlet_model->outlet_ambil($data_array[$i]['kd_gudang_asal'])->row()->outlet_nm; echo $gudangasal; ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td><?php echo $data_array[$i]['tgl']; ?></td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td><?php echo $data_array[$i]['ket']; ?></td>
        </tr>
        </table>
        <table>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Qty</th>
            </tr>
     <?php
        $seq = 1;
        //echo $data;
        for($j=0;$j<count($data_array[$i]['datadetail']);$j++)
        {
            echo '<tr>';
            echo '<td>'.$seq.'</td>';
            echo '<td>'.$data_array[$i]['datadetail'][$j]['kd_barang'].'</td>';
            $nmbarang   = $this->barang_model->get($data_array[$i]['datadetail'][$j]['kd_barang'],'','','')->row()->barang_nm;
            echo '<td>'.$nmbarang.'</td>';
            echo '<td style="text-align:right">'.number_format($data_array[$i]['datadetail'][$j]['qty'],0,',','.').'</td>';
            echo '</tr>';
            if(count($data_array[$i]['datadetail'][$j]['imei'])>0)
            {
                echo '<tr><td colspan="4">';
                echo '<table>';
                echo '<tr><th colspan="2">IMEI</th></tr>';
                $seqdtl    = 1;
                for($k=0;$k<count($data_array[$i]['datadetail'][$j]['imei']);$k++)
                {
                    echo '<tr>';
                    echo '<td style="width:10%">'.$seqdtl.'</td>';
                    echo '<td>'.$data_array[$i]['datadetail'][$j]['imei'][$k].'</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</td></tr>';
            }
            $seq++;
        } 
     ?>
        </table>
        <?php echo form_hidden('no_faktur',$data_array[$i]['no_faktur']) ?>
        <?php echo form_hidden('data_array',$data);?>
        <?php echo form_hidden('data',$data);?>
     <?php
     }
     ?>
        <div style="vertical-align: middle; text-align:center;padding:10px">
            <input class="submit-green" type="submit" value="Simpan" />
        </div>
     <?php echo form_close(); ?>
     </div> <!-- End .module-body -->
</div>  <!-- End .module -->