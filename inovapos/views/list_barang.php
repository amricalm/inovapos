 <style type="text/css">
a { text-decoration:underline; }
</style>
<div style="clear: both"></div>
<div class="grid_12">
    <form action="<?php echo base_url() ?>index.php/barang/list_barang<?php echo ($this->uri->segment(3)!='') ? '/'.$this->uri->segment(3) : ''; ?>" method="POST"><?php $grup=array(''=>'');foreach($data_group->result() as $rowgroup) : $grup[$rowgroup->group_kd]=$rowgroup->group_nm; endforeach; echo form_dropdown('grup',$grup,$cbogrup,'id="grup" class="input-short"'); ?><br /><input type="text" name="search" id="search" class="input-short" value="<?php echo $txtcari; ?>" /><input class="submit-green" type="submit" name="submit" value="Cari" /></form>
    <div class="module">
    	<h2><span>Daftar Barang</span></h2>
        <div class="module-table-body">
        	<form action="">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:2%;text-align:center;">#</th>
                        <th style="width:15%;text-align:center;">Kode</th>
                        <th style="width:40%;text-align:center;">Nama Barang</th>
                        <th style="width:13%;text-align:center;">Satuan</th>
                        <th style="width:5%;text-align:center;">Stok</th>
                        <th style="width:20%;text-align:center;">Harga Jual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $seq = 1;
                        foreach($data->result() as $rowbarang)
                        {
                            //$saldo = $this->barang_saldo_model->get_saldox($rowbarang->barang_kd);
                            //$hasil      = $this->barang_saldo_model->kartu_stok($rowbarang->barang_kd,$this->session->userdata('tanggal'));
                            //$saldo       = $hasil['saldo']+$hasil['masuk']-$hasil['keluar'];
                            $saldo      = $this->barang_saldo_model->saldo_hari_ini($rowbarang->barang_kd);
                            $saldo      = $saldo[0]['saldo_qty'];
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq ?></td>
                        <td><?php echo $rowbarang->barang_kd ?></td>
                        <td><a href="<?php echo ($rowbarang->group_kd!='10' && $rowbarang->group_kd!='60' && $rowbarang->group_kd!='70') ? "javascript:tempel('$rowbarang->barang_kd','$rowbarang->barang_nm','$saldo','$rowbarang->barang_harga_jual','','','$rowbarang->group_kd')" : "javascript:nampil_imei('$rowbarang->barang_kd','$saldo','$rowbarang->barang_harga_jual')"; ?>" style="font-size: small;"><?php echo $rowbarang->barang_nm ?></a></td>
                        
                        <td><?php echo $rowbarang->satuan_nm ?></td>
                        <td style="text-align: right;"><?php echo $saldo; ?></td>
                        <td style="text-align: right;"><?php echo number_format($rowbarang->barang_harga_jual,0,',','.') ?></td>
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
    </div> <!-- End .module -->
    <?php echo $this->pagination->create_links(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#search').focus();
    });
    function tempel(kd,nm,stok,hrg,imei,qty,grup)
    {
        var uri3 = '<?php echo $this->uri->segment(3)?>';
        if(stok <= 0)
        {
            alert("Stok Kosong!");
        }
        else
        {
            if(hrg <= 0)
            {
                alert("Harga Kosong!");
            }
            else
            {
                //if (window.opener && !window.opener.closed)
                //{
                    window.opener.$('#barang_kd<?php echo ($this->uri->segment(3)!='') ? $this->uri->segment(3) : ''; ?>').val(kd);
                    if(uri3!='' && (grup=='10' || grup=='60' || grup=='70')){ nama = nm+' IMEI : '+imei;} else { nama = nm;}
                    window.opener.$("#barang_nm<?php echo ($this->uri->segment(3)!='') ? $this->uri->segment(3) : ''; ?>").val(nama);
                    window.opener.$("#barang_stok<?php echo ($this->uri->segment(3)!='') ? $this->uri->segment(3) : ''; ?>").val(stok);
                    window.opener.$('#barang_harga').val(convert_to_numeric(hrg));
                    if(imei!='') 
                    { 
                        window.opener.$('#<?php echo ($this->uri->segment(3)!='') ? 'barang_imei'.$this->uri->segment(3) : 'imei'; ?>').val(imei);
                        window.opener.$('#qty<?php echo ($this->uri->segment(3)!='') ? $this->uri->segment(3) : ''; ?>').val(qty);
                        //window.opener.$('#diskon').trigger('click');
                        //window.opener.$("#diskon").jkey("return");
                        //if(uri3=='')
                        //{
                            var im = imei;
                            var hg = hrg;
                            var qy = qty;
                            jumlah = parseFloat(hg) * parseInt(qy);
                            if(window.opener.addRow(kd,nm,im,hg,0,qy))
                            {
                                window.opener.tambahin(jumlah);
                            }
                            window.opener.kosongin();
                        //}
                    }
                    else
                    {
                        window.opener.$('#qty<?php echo ($this->uri->segment(3)!='') ? $this->uri->segment(3) : ''; ?>').focus();
                    }
                    window.close();
                //}
            }
        }
    }
    function nampil_imei(kd,stok,harga)
    {
        if(stok <= 0)
        {
            alert("Stok Kosong!");
        }
        else
        {
            if(harga<=0)
            {
                alert("Harga kosong!");
            }
            else
            {
                var cek = window.opener.cek_barang(kd);
                if(cek=='')
                {
                    window.open("<?php echo base_url() ?>index.php/barang/list_imei/"+kd,"testchildren","scrollbars=yes,width=650,height=400");
                }
                else
                {
                    alert(cek);
                }
            }
        }
    }
</script>