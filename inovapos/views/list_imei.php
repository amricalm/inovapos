<style type="text/css">
a { text-decoration:underline; }
</style>
<div style="clear: both"></div>
<div class="grid_12">
    <!--<form action="<?php echo base_url() ?>index.php/barang/list_imei" method="POST"><input type="text" name="search" id="search" class="input-short" value="<?php echo $txtcari; ?>" /><input class="submit-green" type="submit" name="submit" value="Cari" /></form>-->
    <div class="module">
    	<h2><span>Daftar Barang</span></h2>
        <div class="module-table-body">
        	<form action="">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th>IMEI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $seq = 1;
                        //$hasil = $this->barang_saldo_model->kartu_stok($data->row()->barang_kd,$this->session->userdata('tanggal'));
                        //$saldo = $hasil['saldo']+$hasil['masuk']-$hasil['keluar'];
                        $hasil      = $this->barang_saldo_model->saldo_hari_ini($data->row()->imei_barang);
                        $saldo      = $hasil[0]['saldo_qty'];
                        foreach($data->result() as $rowbarang)
                        {
                    ?>
                    <tr>
                        <td class="align-center"><!--<a href="javascript:nilai_imei('<?php echo $rowbarang->imei_barang; ?>','<?php echo $this->barang_model->get($rowbarang->imei_barang,'','','','','')->row()->barang_nm; ?>','','<?php echo $this->barang_model->get($rowbarang->imei_barang,'','','','','')->row()->barang_harga_jual; ?>','<?php echo /*$rowbarang->imei*/trim($rowbarang->imei_no); ?>')" style="font-size: small;"><?php echo $seq ?></a>--><?php echo form_checkbox('kd[]',$rowbarang->imei_no,false,'id="kd'.$seq.'" class="cek"'); ?></td>
                        <td><?php echo /*$rowbarang->imei*/$rowbarang->imei_no ?></td>
                    </tr>
                    <?php 
                        $seq++;
                        }
                    ?>
                </tbody>
            </table>
            </form>
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
         <div style="padding: 5px;text-align:center;text-decoration: none;"><a href="javascript:window.close()" id="keluars" class="submit-green">Keluar</a></div>
    </div> <!-- End .module -->
    <?php echo $this->pagination->create_links(); ?>
</div>
<script type="text/javascript">

    function nilai_imei(kd,nm,stok,hrg,imei)
    {
        window.opener.tempel(kd,nm,stok,hrg,imei,'');
    }
    function keluargituloh()
    {
        var kd = '<?php echo $rowbarang->imei_barang ?>';
        var nm = '<?php echo $this->barang_model->get($rowbarang->imei_barang,'','','','','')->row()->barang_nm ?>';
        var stok = <?php echo $saldo ?>;
        var hrg = <?php echo $this->barang_model->get($rowbarang->imei_barang,'','','','','')->row()->barang_harga_jual; ?>;
        var imei = '';
        var jmhimei = 0;
        $("input:checkbox[class=cek]:checked").each(function() {
            if(jmhimei!=0)
            {
                imei += '#';
            }
            imei += this.value;
            jmhimei++;
        });
        if(imei!='')
        {
            window.opener.tempel(kd,nm,stok,hrg,imei,jmhimei,'<?php echo $this->barang_model->get($rowbarang->imei_barang,'','','','','')->row()->barang_group; ?>');
        }
        else
        {
            window.close();
        }
    }
</script>