<style type="text/css">
a { text-decoration:underline; }
</style>
<div style="clear: both"></div>
<div class="grid_12">
    <!--<form action="<?php echo base_url() ?>index.php/barang/list_imei_temp" method="POST"><input type="text" name="search" id="search" class="input-short" value="<?php echo $txtcari; ?>" /><input class="submit-green" type="submit" name="submit" value="Cari" /></form>-->
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
                        $saldo = 0;
                        //$hasil = $this->barang_saldo_model->kartu_stok($data->row()->barang_kd,$this->session->userdata('tanggal'));
                        //$saldo = $hasil['saldo']+$hasil['masuk']-$hasil['keluar'];
                        //$hasil      = $this->barang_saldo_model->saldo_hari_ini($data->row()->imei_barang);
                        //$saldo      = $hasil[0]['saldo_qty'];
                        foreach($data->result() as $rowbarang)
                        {
                    ?>
                            <tr>
                                <td class="align-center"><!--<a href="javascript:nilai_imei('<?php echo trim($rowbarang->imei_barang); ?>','<?php echo $this->barang_model->get(trim($rowbarang->imei_barang),'','','','','')->row()->barang_nm; ?>','','<?php echo $this->barang_model->get(trim($rowbarang->imei_barang),'','','','','')->row()->barang_harga_jual; ?>','<?php echo /*$rowbarang->imei*/trim($rowbarang->imei_no); ?>')" style="font-size: small;"><?php echo $seq ?></a>--><?php echo form_checkbox('kd[]',$rowbarang->imei_no,false,'id="kd'.$seq.'" class="cek"'); ?></td>
                                <td><?php echo /*$rowbarang->imei*/trim($rowbarang->imei_no) ?></td>
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
    $(document).jkey('esc',function(){
        window.close();
    });
    function keluargituloh()
    {
        var kd = '<?php echo $rowbarang->imei_barang ?>';
        var grup = '<?php echo $this->barang_model->get($rowbarang->imei_barang,'','','','','')->row()->barang_group; ?>';
        var nm = '<?php echo $this->barang_model->get($rowbarang->imei_barang,'','','','','')->row()->barang_nm ?>';
        nm = nm + ':';
        var stok = <?php echo $saldo ?>;
        var hrg = <?php echo $this->barang_model->get($rowbarang->imei_barang,'','','','','')->row()->barang_harga_jual; ?>;
        var imei = '';
        var jmhimei = 0;
        var dimei = new Array();
        var i = 0;
        $("input:checkbox[class=cek]:checked").each(function() {
            dimei[i] = this.value.trim();
            imei += '\n#';
            imei += this.value.trim();
            jmhimei++;
            i++;
        });
        if(jmhimei != 0)
        {
            stok = jmhimei; // berarti ada stok
            window.opener.tempel(kd,grup,nm+imei,stok,hrg,jmhimei,dimei);
        }
    }
</script>