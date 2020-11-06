$(document).ready(function() {
    $("#nav ul ").css({display: "none"}); // Opera Fix
    $("#nav li").hover(function(){
        $(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
        },function(){
        $(this).find('ul:first').css({visibility: "hidden"});
    });
    $('.myTable').dataTable({
        "bPaginate": false,
        "bFilter" : false,
        "bInfo" : false
    });
    $('.th-no').width((2/100)*$('.module').width());
    $('.th-kdbarang').width((19/100)*$('.module').width());
    $('.th-nmbarang').width((34/100)*$('.module').width());
    $('.th-qty').width((5/100)*$('.module').width());
    $('.th-harga').width((13/100)*$('.module').width());
    $('.th-diskon').width((13/100)*$('.module').width());
    $('.th-jumlah').width((13/100)*$('.module').width());
    $('.th-tombol').width((5/100)*$('.module').width());
    $( "#form-bayar" ).dialog({
		autoOpen: false,
        resizable : false,
		height: 450,
		width: 400,
		modal: true,
		buttons: {
			"Bayar": function() {
                $('#bayar-total').val($('#bayar_total').val());
                $('#bayar-term').val($('input[name=term]:checked').val());
                $('#bayar-nomor-kartu').val($('#nomor_kartu').val());
                $('#bayar-jmh-dk').val($('#jmh_dk').val());
                $('#bayar-bayar').val($('#bayar_bayar').val());
                $('#bayar-biaya-kartu').val($('#biaya_kartu').val());
                $('#bayar-diskon').val($('#bayar_diskon').val());
                $('#bayar-kembali').val($('#bayar_kembali').val());
                //alert($('#bayar-total').val()+"-"+$('#bayar-term').val()+"-"+$('#bayar-nomor_kartu').val()+"-"+$('#bayar-jmh-dk').val()+"-"+$('#bayar-bayar').val()+"-"+$('#bayar-biaya-kartu').val()+"-"+$('#bayar-diskon').val()+"-"+$('#bayar-kembali').val());
                $('#frmKasir').submit();
			},
			"Batal": function() {
				$(this).dialog("close");
			}
		},
		close: function() {
			kosongin();
            kosongin-total();
		}
	});
	oTable = $('#groceryCrudTable').dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bStateSave": true,
        "bFilter" : false
	});

	$('a[role=button]').hover(function(){
		$(this).addClass('ui-state-hover');
    },
	function(){
    	$(this).removeClass('ui-state-hover');
	});
    //$('#create-user1').attr("href",window.location.protocol + "//" + window.location.host + "/" + window.location.pathname+"/index.php/kasir?iframe=true&amp;width="+screen.width+"&amp;height="+screen.height);
	$("a[rel^='prettyPhoto']").prettyPhoto({
	       modal: true,
	       social_tools : false
    });
    $('.tgl').datepicker({
            defaultDate: +1,
			dateFormat: "yy-mm-dd",
			regional: "id",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1,
            yearRange: '1960:2030'
		});
    $('.qty1000').jStepper({minValue:0, maxValue:1000});
    $('.qty100').jStepper({minValue:0, maxValue:100});
});

function hapus()
{
    var cfrm = confirm("Yakin akan dihapus?");
    if(cfrm)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function DisplayTime()
{
    if (!document.all && !document.getElementById)
    return
        timeElement = document.getElementById? document.getElementById("clock"): document.all.tick2
    var CurrentDate = new Date()
    var hours = CurrentDate.getHours()
    var minutes = CurrentDate.getMinutes()
    var seconds = CurrentDate.getSeconds()
    if (minutes<=9) minutes="0"+minutes;
    if (seconds<=9) seconds="0"+seconds;
    var currentTime=hours+":"+minutes+":"+seconds;
    timeElement.innerHTML = ""+currentTime+""
    setTimeout("DisplayTime()",1000);
}
function Redirect() 
{
    window.location = window.location;
}
window.onload=DisplayTime;