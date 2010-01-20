/*******            Resistances         ******/
var arcaneTooltip = '<span class="tooltipTitle">비전 저항력 '+ theCharacter.resistances.arcane.value + theCharacter.resistances.arcane.breakdown +'</span><span class="tooltipContentSpecial">비전 관련 공격이나 주문, 능력에 저항할 확률이 증가합니다.<br/>'+ theCharacter.level +'레벨에 대한 저항력: <span class="myWhite">'+ theCharacter.resistances.arcane.rank +'</span></span>';
var arcanePetBonus = "<span class = 'tooltipContentSpecial'>소환수의 저항력 "+ theCharacter.resistances.arcane.petBonus +"만큼 증가</span>";

var fireTooltip = '<span class="tooltipTitle">화염 저항력 '+ theCharacter.resistances.fire.value + theCharacter.resistances.fire.breakdown +'</span><span class = "tooltipContentSpecial">화염 관련 공격이나 주문, 능력에 저항할 확률이 증가합니다.<br/>'+ theCharacter.level +'레벨에 대한 저항력: <span class="myWhite">'+ theCharacter.resistances.fire.rank +'</span></span>';
var firePetBonus = "<span class = 'tooltipContentSpecial'>소환수의 저항력 "+ theCharacter.resistances.fire.petBonus +"만큼 증가</span>";

var natureTooltip = '<span class="tooltipTitle">자연 저항력 '+ theCharacter.resistances.nature.value + theCharacter.resistances.nature.breakdown +'</span><span class = "tooltipContentSpecial">자연 관련 공격이나 주문, 능력에 저항할 확률이 증가합니다.<br/>'+ theCharacter.level +'레벨에 대한 저항력: <span class="myWhite">'+ theCharacter.resistances.nature.rank +'</span></span>';
var naturePetBonus = "<span class = 'tooltipContentSpecial'>소환수의 저항력 "+ theCharacter.resistances.nature.petBonus +"만큼 증가</span>";

var frostTooltip = '<span class="tooltipTitle">냉기 저항력 '+ theCharacter.resistances.frost.value + theCharacter.resistances.frost.breakdown +'</span><span class = "tooltipContentSpecial">냉기 관련 공격이나 주문, 능력에 저항할 확률이 증가합니다.<br/>'+ theCharacter.level +'레벨에 대한 저항력: <span class="myWhite">'+ theCharacter.resistances.frost.rank +'</span></span>';
var frostPetBonus = "<span class = 'tooltipContentSpecial'>소환수의 저항력 "+ theCharacter.resistances.frost.petBonus +"만큼 증가</span>";

var shadowTooltip = '<span class="tooltipTitle">암흑 저항력 '+ theCharacter.resistances.shadow.value + theCharacter.resistances.shadow.breakdown +'</span><span class = "tooltipContentSpecial">암흑 관련 공격이나 주문, 능력에 저항할 확률이 증가합니다.<br/>'+ theCharacter.level +'레벨에 대한 저항력: <span class="myWhite">'+ theCharacter.resistances.shadow.rank +'</span></span>';
var shadowPetBonus = "<span class = 'tooltipContentSpecial'>소환수의 저항력 "+ theCharacter.resistances.shadow.petBonus +"만큼 증가</span>";

/*******            Resistances End       ******/


/*******            Base Stats       ******/

var baseStatsDisplay = "기본 능력치";

var baseStatsStrengthTitle = "힘 ";
var baseStatsStrengthAttack = "전투력 "+ theCharacter.baseStats.strength.attack +"만큼 증가 ";
var baseStatsStrengthBlock = "방패 막기 숙련도 "+ theCharacter.baseStats.strength.block +"만큼 증가<br/>";
var baseStatsStrengthDisplay = "힘: ";

var baseStatsAgilityTitle = "민첩성";
var baseStatsAgilityAttack = "전투력 "+ theCharacter.baseStats.agility.attack +"만큼 증가 ";
var baseStatsAgilityCritHitPercent = "치명타 적중률 "+ theCharacter.baseStats.agility.critHitPercent +"%만큼 증가 ";
var baseStatsAgilityArmor = "방어도"+ theCharacter.baseStats.agility.armor +"만큼 증가 ";
var baseStatsAgilityDisplay = "민첩성: ";

var baseStatsStaminaTitle = "체력";
var baseStatsStaminaHealth = "생명력 "+ theCharacter.baseStats.stamina.health +"만큼 증가 ";
var baseStatsStaminaPetBonus = "소환수의 체력 "+ theCharacter.baseStats.stamina.petBonus +"만큼 증가";
var baseStatsStaminaDisplay = "체력: ";

var baseStatsIntellectTitle = "지능 ";
var baseStatsIntellectMana = "마나 "+ theCharacter.baseStats.intellect.mana +"만큼 증가";
var baseStatsIntellectCritHitPercent = "주문 극대화 적중도 "+ theCharacter.baseStats.intellect.critHitPercent +"%만큼 증가";
var baseStatsIntellectPetBonus = "소환수의 지능 "+ theCharacter.baseStats.intellect.petBonus +"만큼 증가";
var baseStatsIntellectDisplay = "지능: ";

var baseStatsSpiritTitle = "정신력 ";
var baseStatsSpiritHealthRegen = "매초당 생명력 회복량 "+ theCharacter.baseStats.spirit.healthRegen +"만큼 증가 (전투 중이 아닐 때) ";
var baseStatsSpiritManaRegen = "매 5초당 마나 회복량 "+ theCharacter.baseStats.spirit.manaRegen +"만큼 증가 (시전 중이 아닐 때)";
var baseStatsSpiritDisplay = "정신력: ";

var baseStatsArmorTitle = "방어도 ";
var baseStatsArmorReductionPercent = "받는 물리 피해 "+ theCharacter.baseStats.armor.reductionPercent +"%만큼 감소 ";
var baseStatsArmorPetBonus = "소환수의 방어도 "+ theCharacter.baseStats.armor.petBonus +"만큼 증가 ";
var baseStatsArmorDisplay = "방어도: ";

/*******            Base Stats  End     ******/


/*******            Melee     ******/

var meleeDisplay = "근접";

var meleeMainHandTitle = "주장비";
var meleeOffHandTitle = "보조장비";

var meleeExpertiseTitle = "숙련도 "+ theCharacter.melee.weaponSkill.mainHandWeaponSkill.rating;
var meleeMainHandWeaponSkill = "공격이 회피될 확률 및 무기 막기에 막힐 확률 "+ theCharacter.melee.weaponSkill.mainHandWeaponSkill.percent + "%";
var meleeMainHandWeaponRating = "숙련도 "+ theCharacter.melee.weaponSkill.mainHandWeaponSkill.value + " (+"+ theCharacter.melee.weaponSkill.mainHandWeaponSkill.additional +" 증가)";
/*var meleeOffHandWeaponSkill = "무기 숙련 "+ theCharacter.melee.weaponSkill.offHandWeaponSkill.value;
var meleeOffHandWeaponRating = "무기 숙련도 "+ theCharacter.melee.weaponSkill.offHandWeaponSkill.rating;*/
var meleeWeaponSkillDisplay = "무기 숙련: ";

var meleeDamageMainHandAttackSpeed = "<span class='floatRight'>"+ theCharacter.melee.damage.mainHandDamage.speed +"</span>공격 속도 (초):<br/>";
var meleeDamageMainHandDamage = "<span class='floatRight'>"+ theCharacter.melee.damage.mainHandDamage.min +" - "+ theCharacter.melee.damage.mainHandDamage.max;
var meleeDamageMainHandPercent = " <span "+ theCharacter.melee.damage.mainHandDamage.effectiveColor +"> x "+ theCharacter.melee.damage.mainHandDamage.percent +"%</span>";
var meleeDamageDisplay = "공격력: ";

var meleeDamageMainHandDps = "<span class='floatRight'> "+ theCharacter.melee.damage.mainHandDamage.dps +"</span>초당 공격력:<br/>";
var meleeDamageOffHandAttackSpeed = "<span class='floatRight'>"+ theCharacter.melee.damage.offHandDamage.speed +"</span>공격 속도 (초):<br/>";
var meleeDamageOffHandDamage = "<span class='floatRight'>"+ theCharacter.melee.damage.offHandDamage.min +" - "+ theCharacter.melee.damage.offHandDamage.max;
var meleeDamageOffHandPercent = " <span "+ theCharacter.melee.damage.mainHandDamage.effectiveColor +"> x "+ theCharacter.melee.damage.offHandDamage.percent +"%</span>";
var meleeDamageOffHandDps = "<span class='floatRight'>"+ theCharacter.melee.damage.offHandDamage.dps +"</span>초당 공격력:<br/>";

var meleeSpeedTitle = "공격 속도 ";
var meleeSpeedHaste = "가속도 "+ theCharacter.melee.speed.mainHandSpeed.hasteRating +" (가속율 +"+ theCharacter.melee.speed.mainHandSpeed.hastePercent + "%)";
var meleeSpeedDisplay = "속도: ";

var meleePowerTitle = "근접 전투력 ";
var meleePowerIncreasedDps = "근접 무기의 공격력이 초당 "+ theCharacter.melee.power.increasedDps +"만큼 증가 ";
var meleePowerDisplay = "근접 전투력";

var meleeHitRatingTitle = "적중도 ";
var meleeHitRatingIncreasedHitPercent = theCharacter.level +"레벨 대상에 대한 근접 적중률 "+ theCharacter.melee.hitRating.increasedHitPercent +"%만큼 증가";
var meleeHitRatingDisplay = "적중도: ";

var meleeCritChanceTitle = "치명타율 ";
var meleeCritChanceRating = "치명타 적중도 "+theCharacter.melee.critChance.rating+" (치명타 적중률 +"+theCharacter.melee.critChance.plusPercent+"%)";
var meleeCritChanceDisplay = "치명타율: ";

/*******            Melee  End     ******/


/*******            Ranged     ******/

var rangedDisplay = "원거리";
var rangedWeaponSkillTitle = "무기 숙련 ";
var rangedWeaponSkillRating = "무기 숙련도 "+ theCharacter.ranged.weaponSkill.rating;
var rangedWeaponSkillDisplay = "무기 숙련: ";

var rangedDamageTitle = "원거리 장비";
var rangedDamageSpeed = "<span class='floatRight'>"+ theCharacter.ranged.damage.speed +"</span>공격 속도 (초):<br/>";
var rangedDamageDamage = "<span class='floatRight'>"+ theCharacter.ranged.damage.min +" - "+ theCharacter.ranged.damage.max;
var rangedDamageDamagePercent = " <span "+ theCharacter.ranged.damage.effectiveColor +"> x "+ theCharacter.ranged.damage.percent +"%</span><br/>";
var rangedDamageDisplay = "공격력: ";
var rangedDamageDps = "<span class='floatRight'>" + theCharacter.ranged.damage.dps +"</span>초당 공격력:<br/> ";

var rangedSpeedTitle = "공격 속도 "+theCharacter.ranged.speed.value;
var rangedSpeedHaste = "가속도 "+ theCharacter.ranged.speed.hasteRating +" (가속율 +"+theCharacter.ranged.speed.hastePercent+"%)";
var rangedSpeedDisplay = "속도: ";

var rangedPowerTitle = "원거리 전투력 ";
var rangedPowerIncreasedDps = "원거리 무기의 공격력이 초당 "+ theCharacter.ranged.power.increasedDps +"만큼 증가";
var rangedPowerPetAttack = "소환수의 전투력 "+ theCharacter.ranged.power.petAttack +"만큼 증가 ";
var rangedPowerPetSpell = "소환수의 주문 공격력 "+ theCharacter.ranged.power.petSpell +"만큼 증가 ";
var rangedPowerDisplay = "전투력: ";

var rangedHitRatingTitle = "적중도 ";
var rangedHitRatingIncreasedPercent = theCharacter.level +"레벨 대상에 대한 원거리 적중률 "+ theCharacter.ranged.hitRating.increasedHitPercent +"%만큼 증가";
var rangedHitRatingDisplay = "적중도: ";

var rangedCritChanceTitle = "치명타율 ";
var rangedCritChanceRating = "치명타 적중도 "+ theCharacter.ranged.critChance.rating +" (치명타 적중률 +"+ theCharacter.ranged.critChance.plusPercent +"%) ";
var rangedCritChanceDisplay = "치명타율: ";

/*******            Ranged  End     ******/


/*******            Spell     ******/

var spellDisplay = "주문";
var spellBonusDamageTitle = "공격력 증가 ";
var spellBonusDamagePetBonusFire = "<br />자신의 화염 공격력 증가로 인해<br/>소환수의 전투력이 "+ theCharacter.spell.bonusDamage.petBonusAttack +"만큼 증가하고<br/>주문 공격력이 "+ theCharacter.spell.bonusDamage.petBonusDamage +"만큼 증가<br/>";
var spellBonusDamagePetBonusShadow = "<br />자신의 암흑 공격력 증가로 인해<br/>소환수의 전투력이 "+ theCharacter.spell.bonusDamage.petBonusAttack +"만큼 증가하고<br/>주문 공격력이 "+ theCharacter.spell.bonusDamage.petBonusDamage +"만큼 증가<br/>";
var spellBonusDamageDisplay = "공격력 증가:";

var spellBonusHealingTitle = "치유량 증가 ";
var spellBonusHealingValue = "주문 치유량 최대 "+ theCharacter.spell.bonusHealing.value +"만큼 증가 ";
var spellBonusHealingDisplay = "치유량 증가: ";

var spellHitRatingTitle = "적중도 ";
var spellHitRatingIncreasedPercent = theCharacter.level +"레벨 대상에 대한 주문 적중률 "+theCharacter.spell.hitRating.increasedHitPercent +"%만큼 증가 ";
var spellHitRatingDisplay = "적중도: ";

var spellCritChanceTitle = "극대화 적중도 ";
var spellCritChanceDisplay = "극대화율: ";

var spellHasteRatingTitle = "가속도";
var spellHasteRatingDisplay = "가속도: ";
var spellHasteRatingTooltip = "주문 시전 속도 " + theCharacter.spell.hasteRating.percent + "%만큼 증가.";

var spellPenetrationTitle = "관통력";
var spellPenetrationTooltip = "자신의 주문에 대한 대상의 저항력 감소";
var spellPenetrationDisplay = "관통력: ";

var spellManaRegenTitle = "마나 회복량 ";
var spellManaRegenNotCasting = "시전 중이지 않을 때 매 5초마다 "+ theCharacter.spell.manaRegen.notCasting +"의 마나 회복";
var spellManaRegenCasting = "시전 중일 때 매 5초마다 "+ theCharacter.spell.manaRegen.casting +"의 마나 회복";
var spellManaRegenDisplay = "마나 회복량: ";

/*******            Spell  End     ******/

/*******            Defenses     ******/

var defensesDisplay = "방어";

var defensesDefenseTitle = "방어 숙련 ";
var defensesDefenseRating = "방어 숙련도 "+ theCharacter.defenses.defense.rating +" (방어 숙련 +"+ theCharacter.defenses.defense.plusDefense +")";
var defensesDefenseIncreasePercent = "회피, 방패 막기, 무기 막기 확률 "+ theCharacter.defenses.defense.increasePercent +"%만큼 증가";
var defensesDefenseDecreasePercent = "적중될 확률, 치명타에 적중될 확률 "+ theCharacter.defenses.defense.decreasePercent +"%만큼 감소";
var defensesDefenseDisplay = "방어 숙련: ";

var defensesDodgeTitle = "회피 숙련도 ";
var defensesDodgePercent = "회피 확률 "+ theCharacter.defenses.dodge.increasePercent +"%만큼 증가";
var defensesDodgeDisplay = "피함: ";

var defensesParryTitle = "무기 막기 숙련도 ";
var defensesParryPercent = "무기 막기 확률 "+ theCharacter.defenses.parry.increasePercent +"%만큼 증가";
var defensesParryDisplay ="막음: ";

var defensesBlockTitle = "방패 막기 숙련도 ";
var defensesBlockPercent = "방패 막기 확률 "+ theCharacter.defenses.block.increasePercent +"%만큼 증가";
var defensesBlockDisplay = "방어함: ";

var defensesResilienceTitle = "탄력성 ";
var defensesResilienceHitPercent = "적의 치명타 공격에 적중될 확률 "+ theCharacter.defenses.resilience.hitPercent +"%만큼 감소";
var defensesResilienceDamagePercent = "치명타 공격으로 받는 피해 "+ theCharacter.defenses.resilience.damagePercent +"%만큼 감소";
var defensesResilienceDisplay = "탄력성: ";

/*******            Defenses  End     ******/

var textNA = "-";
var textNotApplicable = "해당 없음";

var textHoly = "신성";
var textFire = "화염";
var textNature = "자연";
var textFrost = "냉기";
var textShadow = "암흑";
var textArcane = "비전";

var textHybrid = "혼성";
var textUntalented = "비전문화";

var textRating = "평점 ";
var textNotRanked = "순위 없음";
var textStandingColon = "지난 주 순위:";
var textRatingColon = "평점:";
var text2v2Arena = "2v2 투기장";
var text3v3Arena = "3v3 투기장";
var text5v5Arena = "5v5 투기장";
var textTeamNameColon = "팀 이름:";

var textFindUpgrade = "추천 아이템 찾기";

var textLoading = "로딩 중...";


var textHead = "머리";
var textNeck = "목";
var textShoulders = "어깨";
var textBack = "등";
var textChest = "가슴";
var textShirt = "속옷";
var textTabard = "휘장";
var textWrists = "손목";
var textHands = "손";
var textWaist = "허리";
var textLegs = "다리";
var textFeet = "발";
var textFinger = "손가락";
var textTrinket = "장신구";
var textMainHand = "주장비";
var textOffHand = "보조장비";
var textRanged = "원거리 장비";
var textRelic = "성물";
jsLoaded=true;