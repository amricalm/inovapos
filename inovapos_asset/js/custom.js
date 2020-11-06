
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
    $( "#proses" ).dialog({
		autoOpen: false,
        resizable : false,
        modal:true,
		height: 150,
		width: 150
        });
    $( "#form-bayar" ).dialog({
		autoOpen: false,
        resizable : false,
		height: 530,
		width: 730,
		modal: true,
		buttons: {
			"Bayar": function() {
			     bayar = adn_cnum($('#bayar_bayar').val());
                 belanja = adn_cnum($('#bayar_total').val());
                 kembali = adn_cnum($('#bayar_kembali').val());
                 
                 var Sah = true;
                 var sDK = $('input[name=term]:checked').val();
                 
                 //-- Validasi -------------------------------------------
                 if(parseFloat(kembali) < 0)
                 {
                    Sah = false;
                    alert("Ada Salah Hitung, Silakan Dicek Kembali!\n Atau Jumlah Pembayaran Kurang!");
                    $('#bayar_bayar').focus();
                 }
                 
                 if((sDK.toUpperCase()=='KREDIT'||sDK.toUpperCase()=='DEBIT' || sDK.toUpperCase()=='LEASING')
                        &&($('#nomor_kartu').val()==''||$('#nomor_kartu').val()=='0'))
                 {
                    Sah = false;
                    alert("Nomor Kartu Atau Referensi Tidak Boleh Kosong!");
                 }
                 //--- END VALIDASI ------------------------------------------------------------------------
                 if(Sah)
                 {
                    $("#proses").dialog("open");
                    SimpanKasirBaru();
                 }
                 
			},
			"Batal": function() {
				$(this).dialog("close");
			}
		},
		close: function() {
			kosongin();
		}
	});
    
    $( "#form-bayar-elektrik" ).dialog({
		autoOpen: false,
        resizable : false,
		height: 325,
		width: 730,
		modal: true,
		buttons: {
			"Bayar": function() {
			     bayar = convert_to_string($('#bayar_bayar').val());
                 belanja = convert_to_string($('#bayar_total').val());
                 kembali = convert_to_string($('#bayar_kembali').val());
                 //alert(kembali);
                 if(parseFloat(kembali) >= 0)
                 {
                    //if(parseFloat(bayar) < parseFloat(belanja))
                    //{
                    //    alert("Bayar tidak boleh kurang!");
                    //    $('#bayar_bayar').focus();
                    //}
                    //else
                    //{
                     $("#proses").dialog("open");
			         SimpanKasirBaru();
                    //}
                 }
                 else
                 {
                    alert("Bayar tidak boleh kurang!");
                    $('#bayar_bayar').focus();
                 }
			},
			"Batal": function() {
				$(this).dialog("close");
			}
		},
		close: function() {
			kosongin();
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
}); //end document.ready

 function trim(str) {
    return str.replace(/^\s+|\s+$/g,"");
}

function simpanKasir()
{
    var AoA = $('#myTable tr').map(function(){
                return [
                    $('td',this).map(function(){
//                            var barang = $(this).text();
//                            var n=barang.indexOf("IMEI");
//                            var imei = barang.substr(n+5);
                        return $(this).text();                 
                    }).get()
                ];
                }).get(); 
                    
        //console.log(AoA); 
    
    var sDK = $('input[name=term]:checked').val();
    var jmhDebet = 0; jmhKredit = 0;
    
    if(sDK.toUpperCase()  == 'KREDIT')
    {
        jmhKredit =  $('#jmh_dk').autoNumericGet();
    }
    else if (sDK.toUpperCase()  == 'DEBIT')
    {
        jmhDebet = $('#jmh_dk').autoNumericGet();
    }
    
    var totalBelanja = $('#bayar_total').autoNumericGet();
    var biayaKartu = $('#biaya_kartu').autoNumericGet();
    var jmhTunai = parseFloat(totalBelanja) + parseFloat(biayaKartu) - parseFloat(jmhKredit) - parseFloat(jmhDebet); 
    var hasil = totalBelanja + '-' + biayaKartu + '-' + jmhDebet + '-' + jmhKredit;
    $('#hasil').html(hasil);
    var jmh_bayar = $('#bayar_bayar').val();
//    if(jmh_bayar < totalBelanja)
//    {
//        alert("Total Bayar tidak boleh kurang dari Total Belanja!!!");
//    }
//    else
//    {
        var data = {
            kd_term         : 'KASIR',
            jmh_belanja     : $('#jmh_belanja').val(),
            diskon_p        : $('#bayar_diskon').val(),
            diskon_nominal  : $('#bayar_diskon_display').val(),
            total_belanja   : totalBelanja,
            biaya_kirim     : 0,
            lunas           : 0,
            nomor_kartu     : $('#nomor_kartu').val(),
            dk              : $('input[name=term]:checked').val(),
            jmh_tunai       : jmhTunai,
            jmh_debet       : jmhDebet,
            jmh_kredit      : jmhKredit,
            biaya_kartu     : biayaKartu,
            jmh_uang        : $('#bayar_bayar').val(),
            jmh_kembali     : $('#bayar_kembali').val(),
    
            rows            : AoA
        };
        
        var json = JSON.stringify(data);         
                     
        $.post(
            baseURL+'index.php/kasir/simpan',
            {data:json},
            function(res)
            {
                var statusfaktur = res.split('#');
                //alert(res);
                var status = statusfaktur[0];
                if(status=='E')
                {
                    $("#proses").dialog("close");
                    alert(statusfaktur[1]);
                }
                else
                {
                    print_faktur(statusfaktur[1]);
                    window.location = window.location;
                }
            }
        );
    //}
    return false; 
}
function print_faktur(faktur)
{
    $.post(base_url + 'index.php/kasir/cetak_dari_faktur/'+faktur+'/kasir',function(data)
    {
        window.location=window.location;
    })
    /*.error(function(){alert("Error Printer!");});*/
}
function print_screen()
{
    var AoA = $('#myTable tr').map(function(){return [$('td',this).map(function(){return $(this).text();}).get()];}).get();
    if(AoA.length == 1)
    {
        alert("isi dulu, baru print!");
    }
    else
    {
        $.post(
            baseURL + 'index.php/kasir/cetak_screen', 
            {data: AoA}, 
            function(res)
            {
                if(res!='')
                {
                    alert(res);
                }
            });
    }
}
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

function convert_to_numeric(number)
{
    /*
     * inspired by http://www.cssnewbie.com/javascript-currency-conversion-script/
     * Rob Glazebrook
     */
    var number = number.toString(), 
        pecahan = number.split('.')[0];
        pecahan = pecahan.split('').reverse().join('')
            .replace(/(\d{3}(?!$))/g, '$1.')
            .split('').reverse().join('');
    return pecahan;
}
function convert_to_string(number)
{
    /*
     * inspired by gins
     */
    return number.replace(/\./g,'');
}
function convert_to_float(number)
{
    return number.replace(/\,/g,'.');
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
    if(!currentTime)
    {
        timeElement.innerHTML = ""+currentTime+""
        setTimeout("DisplayTime()",1000);
    }
}
function Redirect() 
{
    window.location = window.location;
}

function adn_cnum(str)
{
    if (str=='' || str == undefined )
    {
        return 0;
    }
    else
    {
        var hasil = str.replace(/\./g,'');
        var angka = parseFloat(hasil);
        return angka;
    }
}
window.onload=DisplayTime;