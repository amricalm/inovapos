<div class="module">
     <h2><span>Mutasi Tanggal <?php echo $this->adntgl->tgl_panjang($this->session->userdata('tanggal')); ?></span></h2>
     <?php //echo form_open(base_url().'index.php/sinkronisasi/simpan_mutasi_file/',array('id'=>'frmkirim')); ?>
     <?php echo form_hidden('sumber','server'); ?>
     <div class="module-table-body">
        <table>
            <tr>
                <th style="width: 20%;">No Faktur</th>
                <th style="width: 20%;">Dari Gudang</th>
                <th style="width: 20%;">Gudang Tujuan</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Status</th>
            </tr>
     <?php
     //echo $data;
     //$data_array            = json_decode($data_array,true);
     for($i=0;$i<count($data_array);$i++)
     {
     ?>
            <tr>
                <td><a href="javascript:getdetail('<?php echo trim($data_array[$i]['no_faktur']) ?>')" style="text-decoration: underline;"><?php echo $data_array[$i]['no_faktur']; ?></a></td>
                <td><?php $gudangasal = $this->outlet_model->outlet_ambil($data_array[$i]['kd_gudang_asal'])->row()->outlet_nm; echo $gudangasal; ?></td>
                <td><?php $gudangtujuan = $this->outlet_model->outlet_ambil($data_array[$i]['kd_gudang_tujuan'])->row()->outlet_nm; echo $gudangtujuan; ?></td>
                <td><?php echo $data_array[$i]['tgl']; ?></td>
                <td><?php echo $data_array[$i]['ket']; ?></td>
                <td><?php echo $data_array[$i]['sinkronisasi']; ?></td>
            </tr>
    <?php echo form_hidden('data',"'".$data."'"); 
        }
     ?>
        </table>
     </div>
</div>  <!-- End .module -->
<script type="text/javascript">
function getdetail(nofaktur)
{
    window.open('<?php echo base_url() ?>index.php/sinkronisasi/daftar_mutasi_detail/'+nofaktur,'test',"scrollbars=yes,width=650,height=400");
}
function segarkembali()
{
    window.location = window.location;
}
</script>