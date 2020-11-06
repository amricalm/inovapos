<div class="module">
     <h2><span>Mutasi</span></h2>
     <?php echo form_open(base_url().'index.php/sinkronisasi/cetak_mutasi_barang/',array('id'=>'frmkirim')); ?>
     <?php echo form_hidden('sumber',base_url().'index.php/sinkronisasi/daftar_mutasi_detail/'.$this->uri->segment(3)); ?>
     <div class="module-table-body">
     <?php
     $data_array            = json_decode($data,true);
     for($i=0;$i<count($data_array);$i++)
     {
        if(trim($data_array[$i]['no_faktur'])==$no_faktur)
        {
     ?>
        <table>
            <tr>
                <td style="width: 20%;">Dari Gudang</td>
                <td><?php $gudangasal = $this->outlet_model->outlet_ambil($data_array[$i]['kd_gudang_asal'])->row()->outlet_nm; echo $gudangasal; ?></td>
            </tr>
            <tr>
                <td style="width: 20%;">Gudang Tujuan</td>
                <td><?php $gudangtujuan = $this->outlet_model->outlet_ambil($data_array[$i]['kd_gudang_tujuan'])->row()->outlet_nm; echo $gudangtujuan; ?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td><?php echo $data_array[$i]['tgl']; ?><font color="red"> [tahun/bulan/tanggal]</font></td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td><?php echo $data_array[$i]['ket']; ?></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%">
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Qty</th>
                        </tr>
     <?php
        $seq=1;
        for($j=0;$j<count($data_array[$i]['datadetail']);$j++)
        {
            echo '<tr>';
            echo '<td>'.$seq.'</td>';
            echo '<td>'.$data_array[$i]['datadetail'][$j]['kd_barang'].'</td>';
            $nmbarang   = ($this->barang_model->get($data_array[$i]['datadetail'][$j]['kd_barang'],'','','')->num_rows()>0) ? $this->barang_model->get($data_array[$i]['datadetail'][$j]['kd_barang'],'','','')->row()->barang_nm : '[BARANG BARU]';
            echo '<td>'.$nmbarang.'</td>';
            echo '<td style="text-align:right">'.number_format($data_array[$i]['datadetail'][$j]['qty'],0,',','.').'</td>';
            echo '</tr>';
            $seq1 = 1;
            if(count($data_array[$i]['datadetail'][$j]['imei']))
            {
                echo '<tr>';
                echo '<td colspan="4">';
                echo '<table>';
                echo '<tr>';
                echo '<th>No</th>';
                echo '<th>IMEI</th>';
                echo '</tr>';
                for($k=0;$k<count($data_array[$i]['datadetail'][$j]['imei']);$k++)
                {
                    echo '<tr>';
                    echo '<td>'.($k+1).'</td>';
                    echo '<td>'.$data_array[$i]['datadetail'][$j]['imei'][$k].'</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</td>';
                echo '</tr>';
            }
            $seq++;
        } 
     ?>
                    </table>
                </td>
            </tr>
        </table>
        <?php echo form_hidden('data',$data); echo form_hidden('no_faktur',$no_faktur);} }?>
        <div style="vertical-align: middle; text-align:center;padding:5px;">
            <?php if($data_array[0]['kd_gudang_tujuan']!=$this->session->userdata('outlet_kd')) { ?>
            <a href="<?php echo base_url().'index.php/sinkronisasi/download_mutasi_barang/'.'TX-'.substr($this->uri->segment(3),3,strlen($this->uri->segment(3))-12).'-'.substr($this->uri->segment(3),-9,strlen($this->uri->segment(3))) ?>"><input class="submit-green" type="button" value="Download" /></a>
            <input class="submit-green" type="submit" value="Cetak" />
            <?php } ?>
        </div>
        </form>
     </div>
</div>  <!-- End .module -->