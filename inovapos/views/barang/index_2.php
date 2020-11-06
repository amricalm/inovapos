    <div id="PeopleTableContainer" style="width:100%"></div>
	<script type="text/javascript">

		$(document).ready(function () {

		    //Prepare jTable
			$('#PeopleTableContainer').jtable({
				title: 'Daftar Barang',
                deleteConfirmation: function(data) {
                    data.deleteConfirmMessage = 'Yakin akan menghapus data barang ' + data.record.nm_barang + '?';
                },
                paging : true,
                pagesize : 20,
				actions: {
					listAction: '<?php echo base_url() ?>index.php/barang/list_barang',
					createAction: '<?php echo base_url() ?>index.php/barang/simpan',
					updateAction: '<?php echo base_url() ?>index.php/barang/update',
					deleteAction: '<?php echo base_url() ?>index.php/barang/hapus'
				},
				fields: {
					kd_barang: {
						key: true,
						create: false,
						edit: false,
						list: false
					},
					barcode: {
						title: 'Barcode',
                        list : false
					},
					nm_barang: {
						title: 'Nama Barang',
						width: '40%'
					},
					kd_group: {
						title: 'Grup',
						width: '10%',
                        options: {<?php $seq=0;foreach($list_group_barang->result() as $row){ if($seq!=0){ echo ',';} echo '"'.$row->kd_group.'":"'.$row->nm_group.'"'; } ?>}
					},
					kd_satuan: {
						title: 'Satuan',
						width: '10%',
                        options: {<?php $seq=0;foreach($list_satuan->result() as $rows){ if($seq!=0){ echo ',';} echo '"'.$rows->kd_satuan.'":"'.$rows->nm_satuan.'"'; } ?>}
					},
					harga_jual: {
						title: 'Harga Jual',
						width: '20%'
					},
					hpp: {
						title: 'HPP',
						width: '20%'
					}
				}
			});

			//Load person list from server
			$('#PeopleTableContainer').jtable('load');

		});

	</script>