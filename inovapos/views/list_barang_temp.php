<style type="text/css">
a { text-decoration:underline; }
</style>
<div style="clear: both"></div>
<div class="grid_12">
    <form action="<?php echo base_url() ?>index.php/barang/list_barang_temp<?php echo ($this->uri->segment(3)!='') ? '/'.$this->uri->segment(3) : ''; ?>" method="POST"><?php $grup=array(''=>'');foreach($data_group->result() as $rowgroup) : $grup[$rowgroup->group_kd]=$rowgroup->group_nm; endforeach; echo form_dropdown('grup',$grup,$cbogrup,'id="grup" class="input-short"'); ?><br /><input type="text" name="search" id="search" class="input-short" value="<?php echo $txtcari; ?>" /><input class="submit-green" type="submit" name="submit" value="Cari" /></form>
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
                        <td><a href="<?php 
                                        echo ($rowbarang->group_hp!='-1') ? 
                                            "javascript:tempel('$rowbarang->barang_kd','$rowbarang->barang_group','$rowbarang->barang_nm','$saldo','$rowbarang->barang_harga_jual','','','$rowbarang->group_kd','$rowbarang->group_hp','$rowbarang->group_elektrik')" : 
                                            "javascript:nampil_imei('$rowbarang->barang_kd','$saldo','$rowbarang->barang_harga_jual')"; ?>" style="font-size: small;"><?php echo $rowbarang->barang_nm ?>
                            </a>
                        </td>
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
        $(document).jkey('esc',function(){
           tutup(); 
        });
    });
    function tutup()
    {
        window.close();
    }
    function tempel(kd,grup,nm,stok,hrg,qty,imei,grup,hp,el)
    {
        if(stok <= 0 && el!='1')
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
                if (window.opener && !window.opener.closed)
                {
                    if(hp=='1'&&el=='0')
                    {
                        window.opener.addRow(kd,nm,hrg,stok,0,qty,imei);
                    }
                    else if(hp=='0'&&el=='1')
                    {
                        window.opener.$('#barang_kd').val(kd);
                        window.opener.$('#barang_grup').val(grup);
                        window.opener.$("#barang_nm").val(nm);
                        window.opener.$("#barang_stok").val(stok);
                        window.opener.$('#barang_harga').val(hrg);
                        window.opener.$('#qty').val('1');
                        window.opener.$('#no_hp').removeAttr('disabled');
                        window.opener.$('#no_hp').focus();
                    }
                    else
                    {
                        window.opener.$('#barang_kd').val(kd);
                        window.opener.$('#barang_grup').val(grup);
                        window.opener.$("#barang_nm").val(nm);
                        window.opener.$("#barang_stok").val(stok);
                        window.opener.$('#barang_harga').val(hrg);
                        window.opener.$('#qty').val(qty);
                        window.opener.$('#qty').focus();
                    }
                    tutup();
                }
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
                var tujuan = "<?php echo base_url() ?>index.php/barang/list_imei_temp/"+kd;
                window.open(tujuan,"imei","scrollbars=yes,width=400,height=500");
            }
        }
    }
</script>