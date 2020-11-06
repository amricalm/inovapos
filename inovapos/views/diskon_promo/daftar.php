


<div class="grid_12">    
    <div id="AdnTableContainer"></div>
    
    
    <div class="module">
    	<h2><span>Daftar Diskon/Promo</span></h2>
        <div class="module-table-body">
            <table id="myTable" class="tablesorter">
            	<thead>
                    <tr>
                        <th style="width:20%">Kode Promo</th>
                        <th style="width:20%">Periode</th>
                        <th style="width:25%">Keterangan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>
            <div style="clear: both"></div>
         </div> <!-- End .module-table-body -->
    </div> <!-- End .module -->
    <?php echo $this->pagination->create_links(); ?>
    <?php //} ?>
    <div class="sembunyi" style="background: #E1E1E1;text-align:right">
        <!--<input type="submit" class="submit-green" value="Proses" />-->
    </div>
    </form>
    <div style="clear: both"></div>
    <div id="updateDialog" title="Update" >
        <iframe id="adnIframe" src=""></iframe>
    </div>
    
</div>
<script type="text/javascript">
function valid()
{
    var bln = $('#bulan').val();
    var thn = $('#tahun').val();
    var aksi = $('#frmFilter').attr('action');
    var aksibaru = aksi+'/'+bln+'/'+thn;
    $('#frmFilter').attr('action',aksibaru);
    return true;
}
function daftarmutasi(tgl,kdgudang)
{
    var tujuan = '<?php echo base_url()."index.php/mutasi/daftar_mutasi/" ?>'+tgl+"/"+kdgudang;
    window.open(tujuan,'test',"scrollbars=yes,width=650,height=400");
}

function seger()
{

}

$(document).ready(function () {
  
    var tbl = $('#myTable').dataTable({
		"bServerSide": false,
        "sAjaxSource": '<?php echo base_url(); ?>'+'index.php/diskon_promo/aj_df',
        "bProcessing": true ,
        "aoColumns": [
                            {
                                "sName": "KD_PROMO"
                                
                            },
                            {
                                "sName": "TGL_DR"
                                
                            },
                            {
                                "sName": "KET"
                                
                            },
                            {
                                "sName": "",
                                "mRender": function (data, type, row) {
                                return '<a class="updateBtn" data-kolom="' + data + '" href="#"><img src="<?php echo $base_img ?>/pencil.gif" width="16" height="16" alt="Edit" /></a>';
                            }
                            
                        },
                    ],
        "columnDefs": [ {
            //"searchable": false,
            //"orderable": false,
            //"targets": 0
        } ],
        "order": [[ 1, 'asc' ]]

	});//end--- Data Table
    
    $('#updateDialog').dialog({
            autoOpen: false,
            hide: "explode",
            width: '95%',
            modal: true,
            resizable: false,
            //open: function(ev, ui){
//                $('#adnIframe').attr('src','<?php echo base_url() . 'index.php/diskon_promo/form/edit/'; ?>');
//          },
            buttons:{
                Simpan:function(){
                   alert('x'); 
                },
                Tutup:function(){
                    $( this ).dialog( "close" );
                }
            }
    });
    
    $('#myTable').delegate('a.updateBtn', 'click', function () {

    
                $('#adnIframe').attr('src','<?php echo base_url() . 'index.php/diskon_promo/form/edit/'; ?>'+$(this).attr('data-kolom'));
                $('#updateDialog').dialog('open');
                $('.ui-widget-overlay').css('background', 'black');
                $(".ui-dialog-titlebar").hide();

            return false;
        }); //end update delegate
});//end document.ready())

</script>