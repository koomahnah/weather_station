var request;
var e;
function odbierzDane()
{
    if (request.readyState == 4) {
        if (request.status == 200) {
            e.innerHTML = request.responseText;            
        }
    }
}
function wymienTresc(adresurl, htmlid)
{
    if (request = createReq()) {
        e = document.getElementByClassName(htmlid);
        request.open("GET", adresurl, true);
        request.onreadystatechange = odbierzDane;
        request.send(null);    
    } else {
		alert("Obiekt nie mogl zostac stworzony!");
	}
}
	function odliczanie() {
		var dzisiaj = new Date();
		var dzien = dzisiaj.getDate();
		if (dzien<10) dzien = "0" + dzien;
		var miesiac = dzisiaj.getMonth()+1;
		if (miesiac<10) miesiac = "0" + miesiac;
		var rok = dzisiaj.getFullYear();
		var godzina = dzisiaj.getHours();
		if (godzina<10) godzina = "0"+godzina;
		var minuty = dzisiaj.getMinutes();
		if (minuty<10) minuty = "0"+minuty;
		var sekundy = dzisiaj.getSeconds();
		if (sekundy<10) sekundy = "0"+sekundy;
		document.getElementById("timer").innerHTML = 
		dzien+"/"+miesiac+"/"+rok+ "<br>"+godzina+":"+minuty+":"+sekundy;
		setTimeout("odliczanie()", 1000);
	}
	function load() {
		generuj();
		odliczanie();
	}
	$(document).ready(function() {
		var NavY=$('#menu').offset().top;
		var stickyNav = function() {
			var ScrollY= $(window).scrollTop();
			if (ScrollY > NavY) {
				$('#menu').addClass('sticky');
			} else {
				$('#menu').removeClass('sticky');
			}
			};
			stickyNav();
			$(window).scroll(function() {
				stickyNav();
			});
			});
