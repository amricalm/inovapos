            <div class="grid_12">
                <?php
                $menuikon           = $this->app_model->menu('ikon');
                for($i=1;$i<=count($menuikon);$i++)
                { 
                    if($menuikon[$i]['nama']=="Kasir" && $tutup_shift)
                    {
                        //nothing;
                    }
                    else
                    {
                ?>
                        <a href="<?php echo $menuikon[$i]['link']; ?>" class="dashboard-module"<?php echo $menuikon[$i]['extra'] ?>>
                        	<img src="<?php echo $base_img ?>/<?php echo $menuikon[$i]['ikon']; ?>" width="64" height="64" alt="edit" />
                        	<span><?php echo $menuikon[$i]['nama']; ?></span>
                        </a>
                <?php 
                    }
                }
                ?>
                <div style="clear: both"></div>
            </div> <!-- End .grid_7 -->
            <!-- Account overview -->
            <div class="grid_5">
                <div class="module">
                        <h2><span></span></h2>
                        <div class="module-body">
                            <h5 style="color:blue;">Omset Shift Ini :<span style="float: right;">Rp. <?php echo number_format($this->kasir_model->get_jmh($this->session->userdata('tanggal'),$this->session->userdata('shift')),0,',','.');//echo $this->db->last_query();  ?></span></h5>
                            <input type="hidden" value="<?php echo $this->db->last_query(); ?>" />
                            <h5 style="color: green;">Diskon : <span style="float: right;">Rp. <?php echo number_format($this->kasir_model->get_diskon($this->session->userdata('tanggal'),$this->session->userdata('shift'))->row()->total,0,',','.');  ?></span></h5>
                            <!-- <h5 style="color: red;">Saldo Elektrik <span style="float: right;"><blink>Rp. <?php $saldo = $this->barang_saldo_model->get_saldo_elektrik();/*print_r($saldo);*/ echo number_format($saldo['saldo_qty'],0,',','.'); ?></blink></span></h5> -->
                            <!-- <h5 style="color: orange;">Faktur Mutasi Terakhir : <span style="float: right;"><?php echo ($this->barang_mutasi_model->get_max()->row()->no_faktur > $this->barang_mutasi_model->get_max_history()->row()->no_faktur) ? $this->barang_mutasi_model->get_max()->row()->no_faktur : $this->barang_mutasi_model->get_max_history()->row()->no_faktur; ?></span></h5> -->
                            <input type="hidden" value="<?php echo $this->db->last_query(); ?>" />
                            <h5 style="color:violet">Penjualan : <span style="float: right;"><?php echo ($this->kasir_model->get_max()->row()->no_faktur > $this->kasir_model->get_max_history()->row()->no_faktur) ? $this->kasir_model->get_max()->row()->no_faktur : $this->kasir_model->get_max_history()->row()->no_faktur; ?></span></h5>
                            <h5></h5>
                        </div>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div class="grid_5">
                <div class="module">
                        <h2><span></span></h2>
                        <div class="module-body">
                            <h5 style="color:blueviolet">Periode Transaksi</h5>
                            <h5 style="color:brown">Tanggal : <span style="float: right;"><?php echo $this->session->userdata('tanggal'); ?></span></h5>
                            <h5 style="color: darkblue;">Shift : <span style="float: right;"><?php echo $this->session->userdata('shift'); ?></span></h5>
                            <h5 style="color: magenta;">Gudang : <span style="float: right;"><?php echo $this->session->userdata('outlet_nm'); ?></span></h5>
                            <h5 style="color: gray;">User : <span style="float: right;"><?php echo $this->session->userdata('user_nm'); ?></span></h5>
                            <h5></h5>
                        </div>
                </div>
                <div style="clear:both;"></div>
            </div>
            <div style="clear:both;"></div>
            <script type="text/javascript">
                setTimeout("Redirect()",5000); 
                
                
            $(document).ready(function(){
                console.log('Loading Sukses...');
                
                 
            });
                
            </script>