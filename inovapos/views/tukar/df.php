<div class="grid_12">
    <div class="module">
    	<h2><span>Daftar Tukar Barang</span></h2>
        <div class="module-table-body">
            <!--<form action="<?php echo base_url() ?>index.php/laporan/tukar_barang" method="POST">
                <table width="100%">
                    <tr>
                        <td width="35%">Pilih Kelompok Barang</td>
                        <td><?php //$grup=array(''=>'');foreach($data_group->result() as $rowgroup) : $grup[$rowgroup->group_kd]=$rowgroup->group_nm; endforeach; echo form_dropdown('grup',$grup,$cbogrup,'id="grup" class="input-medium"'); ?></td>
                    </tr>
                    <tr>
                        <td>Pencarian Kode atau Nama</td>
                        <td><input type="text" name="search" id="search" class="input-medium" value="<?php echo $txtcari; ?>" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input class="submit-green" type="submit" name="submit" value="Cari" />&nbsp;&nbsp;&nbsp;<a href="<?php echo base_url().'index.php/laporan/penjualan_per_faktur' ?>" class="submit-green">Per Faktur</a></td>
                    </tr>
                </table>
            </form>-->
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <!--
<th style="width:2%;text-align:center;">#</th>
-->
                        <th style="width:9%">Tanggal</th>
                        <th style="width:28%">No Faktur</th>
                        <th style="width:61%">Keterangan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
    </div> <!-- End .module -->
    <?php echo $this->pagination->create_links(); ?>
</div>
<script type="text/javascript">
function iclose()
{
    $.prettyPhoto.close();
    window.location = "<?php echo base_url().'index.php/'.$this->uri->uri_string()?>";
}

$(document).ready(function(){
    var tbl = $('#myTable').dataTable({
        "bServerSide": false,
        "sAjaxSource": '<?php echo base_url(); ?>'+'index.php/tukar/aj_df',
        "bProcessing": true ,
        "aoColumns": [
//                            {
                                //"sName":"NO_URUT"
                            //},

                            {
                                "sName": "TGL",
                                "mRender": function (data, type, row) {
                                    return '<a href="#" class="pilihBtn">' + data + '</a>';}
                                
                            },
                            {
                                "sName": "NO_FAKTUR"
                                
                            },
                            {
                                "sName": "KET"
                                
                            },
                            {
                                "sName": "no_faktur",
                                "mRender": function (data, type, row) {
                                    return '<a href="cetak/' + data + '" class="updateBtn">Cetak</a>';
                                }
                            }
                    ],
        "columnDefs": [ {
            //"searchable": false,
            //"orderable": false,
            //"targets": 0
        } ],
        "order": [[ 1, 'asc' ]]
    });
    
    //tbl.fnDraw(false);
//    tbl.on('order.dt', function () {
//                console.log(tbl.column(-1));
//                //tbl.column(0).nodes();
//    }).fnDraw();
    
    
    $('#myTableBarang').on( 'click','a.pilihBtn', function (event) {
        event.preventDefault();
        
//        var pilihanBaris = $(this).parents('tr');
//        var namaBarang = pilihanBaris.children('td').eq(1).html();
//        var harga = pilihanBaris.children('td').eq(3).html();
//        
//        var elBaris = elSelectedKdBarang.parents('tr').closest('tr');
//        elBaris.children('td').eq(2).children('input').val(namaBarang);
//        elBaris.children('td').eq(3).children('input').val(harga);
//        elSelectedKdBarang.val($(this).text());
//        $('#PilihBarangDialog').dialog('close');
        
    });
    
 });//end document.ready())
</script>