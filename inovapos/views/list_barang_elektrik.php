<style type="text/css">
a { text-decoration:underline; }
a:focus { background:red;color:white; }
</style>
<div style="clear: both"></div>
<div class="grid_12">
    <form action="<?php echo base_url() ?>index.php/barang/list_barang_elektrik<?php echo ($this->uri->segment(3)!='') ? '/'.$this->uri->segment(3) : ''; ?>" method="POST">
        <div style="padding: 10px;text-align:center;background:red;color:white;font-weight:bold;margin:10px;">
            Saldo Elektrik setelah Transaksi terakhir : Rp. <blink><?php $saldo = $this->barang_saldo_model->get_saldo_elektrik(); echo number_format($saldo['saldo_qty'],0,',','.'); ?></blink>
            <input type="hidden" id="saldo_elektrik" value="<?php echo $saldo['saldo_qty']; ?>" />
        </div>        
        <div>
            <input type="text" name="search" id="search" class="input-short" value="<?php echo $txtcari; ?>" />
            <input class="submit-green" type="submit" name="submit" value="Cari" />
        </div>
    </form>
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
                        <th style="width:20%;text-align:center;">Harga Pokok</th>
                        <th style="width:20%;text-align:center;">Harga Jual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $seq = 1;
                        foreach($data->result() as $rowbarang)
                        {
                    ?>
                    <tr>
                        <td class="align-center"><?php echo $seq ?></td>
                        <td><?php echo $rowbarang->barang_kd ?></td>
                        <td><a href="javascript:tempel('<?php echo $rowbarang->barang_kd?>','<?php echo $rowbarang->barang_nm?>','<?php echo $rowbarang->barang_harga_pokok?>','<?php echo $rowbarang->barang_harga_jual?>')"><?php echo $rowbarang->barang_nm ?></a></td>
                        <td style="text-align: right;"><?php echo number_format($rowbarang->barang_harga_pokok,0,',','.') ?></td>
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
    function tempel(kd,nm,hpp,harga)
    {
        var saldo = parseFloat(convert_to_string($('#saldo_elektrik').val()));
        if(saldo > hpp)
        {
            if (window.opener && !window.opener.closed)
            {
                window.opener.$('#barang_kd').val(kd);
                window.opener.$("#barang_nm").val(nm);
                window.opener.$("#hpokok").val(hpp);
                window.opener.$('#barang_harga').val(harga);
                window.opener.$('#qty').val('1');
                if(window.opener.$('#imei').val()!='')
                {
                    
                }
                else
                {
                    window.opener.$('#imei').focus();
                }
                tutup();
            }
        }
        else
        {
            alert("Saldo Nol/Tidak cukup!");
        }
    }
</script>