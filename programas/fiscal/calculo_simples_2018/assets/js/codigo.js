function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

function verificarMoeda(){
	if (isNumber(event.key) == true | event.key == "." | event.key == ","){
		alert();
	} else {
		
		return false;
	}
}