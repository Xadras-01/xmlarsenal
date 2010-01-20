var textNone = "없음";
var textLearnMore = "자세히 보기"
var textLearnMoreHover = "캐릭터 정보를 볼 때, 창의 오른쪽 위에 있는 작은 핀모양을 눌러 캐릭터의 정보를 \"고정\"하면 해당 정보가 그 창에 저장됩니다.";

var textClickPin = "이 정보를 고정시키려면 여기를 누르세요.";
var tClickPinBreak = "이 정보를 고정시키려면<br />여기를 누르세요.";
var textViewProfile = "캐릭터창 보기";

var textSearchTheArmory = "전투정보실 검색";

var textArmory = "전투정보실";
var textSelectCategory = "--범주 선택--";
var textArenaTeams = "투기장 팀";
var textCharacters = "캐릭터";
var textGuilds = "길드";
var textItems = "아이템";

var textEnterGuildName = "길드 이름 입력";
var textEnterCharacterName = "캐릭터 이름 입력";
var textEnterTeamName = "팀 이름 입력";

var textVs2 = "2v2";
var textVs3 = "3v3";
var textVs5 = "5v5";

var textCurrentlyEquipped = "<span class = 'myGray'>현재 장착 장비</span>";

var beforeDiminishingReturns = "<span class = 'myGray'>(점감 효과 적용 전)</span>";

var textPoor = "매우 약함";
var textFair = "약함";
var textGood = "보통";
var textVeryGood = "좋음";
var textExcellent = "매우 좋음";

var tStwoChar = "검색어는 2자 이상 입력하셔야 합니다.";
var tScat = "범주를 선택하십시오.";
var tSearchAll = "통합 검색 ";

	var textHideItemFilters = "아이템 검색 범위 숨김";
	var textShowItemFilters = "아이템 검색 범위 표시";
	
	var textHideAdvancedOptions = "고급 검색 조건 숨김";
	var textShowAdvancedOptions = "고급 검색 조건 표시";
	
	var textErrorLevel = "최소 레벨이 최대 레벨보다 높게 입력되었습니다.";
	var textErrorSkill = "최소 숙련도가 최대 숙련도보다 높게 입력되었습니다.";

var tPage = "쪽";
var textOf = "중";

var tRelevance = "";
var tRelevanceKr = "정확도";

var tGuildLeader = "길드장";
var tGuildRank = "등급";

var textrace = "";
var textclass = "";

var text1race = "인간";
var text2race = "오크";
var text3race = "드워프";
var text4race = "나이트 엘프";
var text5race = "언데드";
var text6race = "타우렌";
var text7race = "노움";
var text8race = "트롤";
var text10race = "블러드 엘프";
var text11race = "드레나이";

var text1class = "전사";
var text2class = "성기사";
var text3class = "사냥꾼";
var text4class = "도적";
var text5class = "사제";
var text6class = "죽음의 기사";
var text7class = "주술사";
var text8class = "마법사";
var text9class = "흑마법사";
var text11class = "드루이드";

function printWeeks(numWeeks) {
	if (numWeeks == 1)
		return "1주";
	else
		return numWeeks + "주";
}

var tCharName = "캐릭터 이름";
var tGRank = "길드 등급";
var toBag = "보관 가방";
var tTType = "동작";
var tdBag = "이동 가방";
var tItem = "아이템"
var tDate = "날짜 및 시간";

var tItemName = "아이템 이름";
var tItemBag = "아이템 가방";
var tItemSlot = "아이템 칸";
var tItemType = "아이템 유형";
var tItemSubtype = "아이템 세부 유형";

var tenchT = "마법부여";
var tenchP = "마법부여";

var tLoading = "로딩 중";
var errorLoadingToolTip = "Error loading tooltip.";

function returnDateOrder(theMonth, theDay, theYear, theTime) {
	return theYear + theMonth + theDay + theTime; //organize the variables according to your region's custom
}

function returnDay(theDay, nospace) {
	
	if (nospace) {
	switch (theDay) {
		case 0: return '일요일';
		case 1: return '월요일';
		case 2: return '화요일';
		case 3: return '수요일';
		case 4: return '목요일';
		case 5: return '금요일';
		case 6: return '토요일';
		default: return '';
		}		
	} else {
		switch (theDay) {
	case 0: return '일&nbsp;';
	case 1: return '월&nbsp;';
	case 2: return '화&nbsp;&nbsp;';
	case 3: return '수&nbsp;';
	case 4: return '목';
	case 5: return '금&nbsp;&nbsp;&nbsp;&nbsp;';
	case 6: return '토&nbsp;';
	default: return '';
		}
	}
}

function formatDate(theDate, isSimple) {

	var newDate = new Date(theDate);
	
	var amPM;
	if (newDate.getHours() >= 12)
		amPM = "PM"
	else
		amPM = "AM"
		
	var theHour = newDate.getHours()%12;
	if (!theHour)
		theHour = 12;
		
	var theMinutes = newDate.getMinutes();
	if (!theMinutes)
		theMinutes = "00"
	if ((parseInt(theMinutes) <= 9) && (theMinutes != "00"))	
		theMinutes = "0" + theMinutes;
		
	var theYear = newDate.getFullYear();

	if (isSimple)
		var d = (theYear+"/"+newDate.getMonth() + 1) +"/"+ newDate.getDate();
	else
		var d = returnDay(newDate.getDay()) + " " + theYear +"/"+(newDate.getMonth() + 1) +"/"+ newDate.getDate()+" "+ theHour +":"+ theMinutes +" "+ amPM;

	return d;
}

function formatDateGraph(theDate) {
	
	var newDate = new Date(theDate);
	
	
	var monthArray = new Array();
	monthArray[0] = "1월";
	monthArray[1] = "2월";
	monthArray[2] = "3월";
	monthArray[3] = "4월";
	monthArray[4] = "5월";
	monthArray[5] = "6월";
	monthArray[6] = "7월";
	monthArray[7] = "8월";
	monthArray[8] = "9월";
	monthArray[9] = "10월";
	monthArray[10] = "11월";
	monthArray[11] = "12월";
	

	var amPM;
	if (newDate.getHours() >= 12)
		amPM = "PM"
	else
		amPM = "AM"
		
	var theHour = newDate.getHours()%12;
	if (!theHour)
		theHour = 12;

	var theYear = newDate.getFullYear();

	var theMinutes = newDate.getMinutes();
	if (!theMinutes)
		theMinutes = "00"
	if ((parseInt(theMinutes) <= 9) && (theMinutes != "00"))	
		theMinutes = "0" + theMinutes;
		
	var d = new Array();
	d = [theYear+" "+ monthArray[newDate.getMonth()] +" "+ newDate.getDate(), theHour +":"+ theMinutes +" "+ amPM ];
	
	//alert("d: " + d);
	
	return d;
}

function returnDateFormat() {
	return ['월', '일', '년'];
}

var gTruncItemNameContents = 70;
var gTruncItemName = 35;
var gTruncGuildRank = 18;

function printBag(bagId) {
	return tItemBag + " " + bagId;
}

var textTeam = "Team";

var textOpponent = "상대 팀명";
var textResult = "결과";
var textDate = "날짜와 시간";
var textNewRating = "새로운 평점";
var textRatingChange = "평점 변동";

var textOverallRatingChange = "전체 평점 변동";
var textW = "승";
var textWins = "승";
var textL = "패";
var textLosses = "패";
var textMP = "경기 수";
var textMatchesPlayed = "총 경기 수";
var textWinPercent = "승률";
var textAvgChange = "경기 당 평균 변동";

var textCharName = "캐릭터 명";
var textKillingBlows = "결정타 수";
var textDamageDone = "피해량&nbsp;";
var textDamageTaken = "받은 피해량&nbsp;";
var textHealingDone = "치유량&nbsp;";
var textHealingTaken = "받은 치유량&nbsp;&nbsp;";
var tRace = "종족&nbsp;";
var tClass = "직업";
var textFindGraph = "&lt;표에서 검색&gt;";
var textEmpty = "";

var textRealm = "서버";
var textTeamDeleted = "존재하지 않는 팀입니다";
var textOHistory = "해당 팀과 치른 모든 경기 내용을 요약 정보로 봅니다.";

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

function openIssues() {
window.open('/strings/ko_kr/known-issues.xml','knownissues','resizable=no,width=618,height=600,left=50,top=50,scrollbars=yes');
}

//used in datepicker
function dateLocalization(){	
	Date.dayNames = ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'];
	Date.abbrDayNames = ['일', '월', '화', '수', '목', '금', '토'];
	Date.monthNames = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];
	Date.abbrMonthNames = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
	
	Date.firstDayOfWeek = 0;
	Date.format = 'mm/dd/yyyy';
	
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
	Date.dateTimeFormat = 'mm/dd/yyyy g:i:s'
	
	Date.seconds = "초";
	Date.minutes = "분";
	
	$.dpText = {
		TEXT_PREV_YEAR		:	'년 전',
		TEXT_PREV_MONTH		:	'월 전',
		TEXT_NEXT_YEAR		:	'년 다음',
		TEXT_NEXT_MONTH		:	'월 다음',
		TEXT_CLOSE			:	'닫음',
		TEXT_CHOOSE_DATE	:	'날짜선택',
		HEADER_FORMAT		:	'mmmm yyyy'
	};
}

jsLoaded=true;


