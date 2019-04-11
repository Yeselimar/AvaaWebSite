fechaformartear: function (fecha)
{
	var d = new Date(fecha);
	var dia = d.getDate();
    var mes = d.getMonth() + 1;
    var anho = d.getFullYear();
    var fecha = this.zfill(dia,2) + "/" + this.zfill(mes,2) + "/" + anho;
    return fecha;
},
horaformatear: function (hora)
{
	var cadena = "2018-11-11 "+hora;
	var dia = new Date (cadena);
	return moment(dia).format('hh:mm A');
},
zfill: function(number, width)
{
	var numberOutput = Math.abs(number); /* Valor absoluto del número */
	var length = number.toString().length; /* Largo del número */ 
    var zero = "0"; /* String de cero */  
    
    if (width <= length) {
        if (number < 0) {
             return ("-" + numberOutput.toString()); 
        } else {
             return numberOutput.toString(); 
        }
    } else {
        if (number < 0) {
            return ("-" + (zero.repeat(width - length)) + numberOutput.toString()); 
        } else {
            return ((zero.repeat(width - length)) + numberOutput.toString()); 
        }
    }
}