var textNone = "Ninguno";
var textLearnMore = "Haz clic aquí para saber más"
var textLearnMoreHover = "Mientras revisas un perfil de personaje, haz clic en la tachuela ubicada en la parte superior derecha de esta ventana para clavarlo y que quede guardado en ella.";

var textClickPin = "Haz clic aquí para clavar este perfil";
var tClickPinBreak = "Haz clic aquí para<br />clavar este perfil";
var textViewProfile = "Ver perfil";

var textSearchTheArmory = "Buscar en la Armería";

var textArmory = "Armería";
var textSelectCategory = "-- Categorías --";
var textArenaTeams = "Equipos de arena";
var textCharacters = "Personajes";
var textGuilds = "Hermandades";
var textItems = "Objetos";

var textEnterGuildName = "Ingresa el nombre de la hermandad";
var textEnterCharacterName = "Ingresa el nombre del personaje";
var textEnterTeamName = "Ingresa el nombre de tu equipo";

var textVs2 = "2c2";
var textVs3 = "3c3";
var textVs5 = "5c5";

var textCurrentlyEquipped = "<span class = 'myGray'>Equipado</span>";

var beforeDiminishingReturns = "<span class = 'myGray'>(Antes del rendimiento decreciente)</span>";

var textPoor = "Pobre";
var textFair = "Regular";
var textGood = "Bueno";
var textVeryGood = "Muy bueno";
var textExcellent = "Excelente";

var tStwoChar = "La palabra de búsqueda debe contener al menos 2 caracteres.";
var tScat = "Por favor, selecciona una categoría.";
var tSearchAll = "Buscar en ";

	var textHideItemFilters = "Ocultar filtros";
	var textShowItemFilters = "Mostrar filtros";
	
	var textHideAdvancedOptions = "Ocultar opciones";
	var textShowAdvancedOptions = "Mostrar opciones";
	
	var textErrorLevel = "Nivel min. es mayor que nivel max.";
	var textErrorSkill = "Habilidad min. es mayor que Habilidad max.";

var tPage = "Página";
var textOf = "de";

var tRelevance = "Relevancia";
var tRelevanceKr = "";

var tGuildLeader = "Líder de la hermandad";
var tGuildRank = "Rango";

var textrace = "";
var textclass = "";

var text1race = "Humano";
var text2race = "Orco";
var text3race = "Enano";
var text4race = "Elfo de la noche";
var text5race = "No muerto";
var text6race = "Tauren";
var text7race = "Gnomo";
var text8race = "Troll";
var text10race = "Elfo de sangre";
var text11race = "Draenei";

var text1class = "Guerrero";
var text2class = "Paladín";
var text3class = "Cazador";
var text4class = "Pícaro";
var text5class = "Sacerdote";
var text6class = "Caballero de la Muerte";
var text7class = "Shamán";
var text8class = "Mago";
var text9class = "Brujo";
var text11class = "Druida";

function printWeeks(numWeeks) {
	if (numWeeks == 1)
		return "1 semana";
	else
		return numWeeks + " semanas";
}

var tCharName = "Nombre de personaje";
var tGRank = "Rango de hermandad";
var toBag = "Origen";
var tTType = "Acción";
var tdBag = "Destino";
var tItem = "Objeto"
var tDate = "Fecha y hora";

var tItemName = "Objeto";
var tItemBag = "Pestaña";
var tItemSlot = "Hueco";
var tItemType = "Tipo";
var tItemSubtype = "Subtipo";

var tenchT = "Encantamiento";
var tenchP = "Encantamiento";

var tLoading = "Cargando";
var errorLoadingToolTip = "Error al cargar la ventana de información.";

function returnDateOrder(theMonth, theDay, theYear, theTime) {
	return theDay + theMonth + theYear + theTime; //organize the variables according to your region's custom
}

function returnDay(theDay, nospace) {
	
	if (nospace) {
		switch (theDay) {
		case 0: return 'Domingo';
		case 1: return 'Lunes';
		case 2: return 'Martes';
		case 3: return 'Miércoles';
		case 4: return 'Jueves';
		case 5: return 'Viernes';
		case 6: return 'Sábado';
		default: return '';
		}		
	} else {
		switch (theDay) {
		case 0: return 'Dom&nbsp;';
		case 1: return 'Lun&nbsp;';
		case 2: return 'Mar&nbsp;&nbsp;';
		case 3: return 'Mi&eacute;&nbsp;';
		case 4: return 'Jue';
		case 5: return 'Vie&nbsp;';
		case 6: return 'S&aacute;b&nbsp;';
		default: return '';
		}	
	}
}

function formatDate(theDate, isSimple) {

	//var amPM;
	//if (theDate.getHours() >= 12)
	//	amPM = "PM"
	//else
	//	amPM = "AM"
		
	var theHour = theDate.getHours();
	//if (!theHour)
	//	theHour = 12;
		
	var theMinutes = theDate.getMinutes();
	if (!theMinutes)
		theMinutes = "00"
	if ((parseInt(theMinutes) <= 9) && (theMinutes != "00"))	
		theMinutes = "0" + theMinutes;
		
	var theYear = theDate.getFullYear();

	if (isSimple)
		var d = theDate.getDate() +"/"+ (theDate.getMonth() + 1) +"/"+ theYear;
	else
		var d = returnDay(theDate.getDay()) + ", " + theDate.getDate() +"/"+ (theDate.getMonth() + 1) +"/"+ theYear +" "+ theHour +":"+ theMinutes;	
	return d;
}

function formatDateGraph(theDate) {
	
	var monthArray = new Array();
	monthArray[0] = "Ene";
	monthArray[1] = "Feb";
	monthArray[2] = "Mar";
	monthArray[3] = "Abr";
	monthArray[4] = "May";
	monthArray[5] = "Jun";
	monthArray[6] = "Jul";
	monthArray[7] = "Ago";
	monthArray[8] = "Sep";
	monthArray[9] = "Oct";
	monthArray[10] = "Nov";
	monthArray[11] = "Dic";
	

	//var amPM;
	//if (theDate.getHours() >= 12)
	//	amPM = "PM"
	//else
	//	amPM = "AM"
		
	var theHour = theDate.getHours();
	//if (!theHour)
	//	theHour = 12;
		
	var theYear = theDate.getFullYear();

	var theMinutes = theDate.getMinutes();
	if (!theMinutes)
		theMinutes = "00"
	if ((parseInt(theMinutes) <= 9) && (theMinutes != "00"))	
		theMinutes = "0" + theMinutes;
		
	var d = new Array();
	d = [theDate.getDate() +". "+ monthArray[theDate.getMonth()] +" "+ theYear, theHour +":"+ theMinutes];
	return d;
}

function returnDateFormat() {
	return ['day', 'month', 'year'];
}

var gTruncItemNameContents = 70;
var gTruncItemName = 35;
var gTruncGuildRank = 18;

function printBag(bagId) {
	return tItemBag + " " + bagId;
}

var textTeam = "Equipo";

var textOpponent = "Nombre del equipo oponente";
var textResult = "resultado";
var textDate = "Fecha y hora";
var textNewRating = "Nueva calificación";
var textRatingChange = "Cambio de calif.";

var textOverallRatingChange = "Cambio total de calif.";
var textW = "V";
var textWins = "Victorias";
var textL = "D";
var textLosses = "Derrotas";
var textMP = "PJ";
var textMatchesPlayed = "Partidas jugadas";
var textWinPercent = "% victorias";
var textAvgChange = "Cambio promedio por partida";

var textCharName = "Personaje";
var textKillingBlows = "Golpes de gracia";
var textDamageDone = "Daño causado&nbsp;";
var textDamageTaken = "Daño recibido&nbsp;";
var textHealingDone = "Sanación realizada&nbsp;";
var textHealingTaken = "Sanación recibida&nbsp;&nbsp;";
var tRace = "Raza&nbsp;";
var tClass = "Clase";
var textFindGraph = "&lt;Buscar la gráfica&gt;";
var textEmpty = "";

var textRealm = "Reino";
var textTeamDeleted = "Este equipo ya no existe";
var textOHistory = "Ver un resumen interactivo de todas las partidas jugadas contra este equipo.";

function formatNumber(number)
{
	number = number.toString();
	if (number.length > 3) {
	var mod = number.length % 3;
	var output = (mod > 0 ? (number.substring(0,mod)) : '');
	for (i=0 ; i < Math.floor(number.length / 3); i++) {
		if ((mod == 0) && (i == 0))
			output += number.substring(mod+ 3 * i, mod + 3 * i + 3);
		else
			output+= ',' + number.substring(mod + 3 * i, mod + 3 * i + 3);
	}
	return (output);
	}
	else return number;
}

//used in datepicker
function dateLocalization(){	
	Date.dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
	Date.abbrDayNames = ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'];
	Date.monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
	Date.abbrMonthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
	
	Date.firstDayOfWeek = 0;
	Date.format = 'dd/mm/yyyy';
	
	Date.use24hr = false;
	Date.pm = "PM";
	Date.am = "AM";
	
	/*	
		g  	12-hour format of an hour without leading zeros		1 through 12
		G 	24-hour format of an hour without leading zeros 	0 through 23
		h 	12-hour format of an hour with leading zeros		01 through 12
		H 	24-hour format of an hour with leading zeros		00 through 23
		i 	Minutes with leading zeros 							00 to 59
		s 	Seconds, with leading zeros 						00 through 59		
	*/	
	Date.timeformat = 'g:i:s';
	Date.dateTimeFormat = 'dd/mm/yyyy g:i:s'
	
	Date.seconds = "segs";
	Date.minutes = "mins";
	
	$.dpText = {
		TEXT_PREV_YEAR		:	'Año previo',
		TEXT_PREV_MONTH		:	'Mes previo',
		TEXT_NEXT_YEAR		:	'Año siguiente',
		TEXT_NEXT_MONTH		:	'Mes siguiente',
		TEXT_CLOSE			:	'Cerrar',
		TEXT_CHOOSE_DATE	:	'Elegir fecha',
		HEADER_FORMAT		:	'mmmm yyyy'
	};
}

jsLoaded=true;
