<?php

/**
 * Realm.class.php
 * 
 * Small helper Class to store the attributes of a realm. Has not direct database representation.
 * Adding realms to the arsenal is done by adding them to the realms-array in config.inc.php
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/08/11
 * @package XMLArsenal
 * @subpackage classes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/


class Realm{

	public $realmId;
	public $realmType;
	public $realmName;
	public $dataGrabber;
	
	public function __construct($realmId = null, $realmType = null, $realmName = null) {
		
		$this->realmId = 	$realmId;
		$this->realmType = 	$realmType;
		$this->realmName = 	$realmName;
		
	}
	
	public function loadDataGrabber(){
		
		$realmGrabber = $this->realmType."DataGrabber";
		eval("\$obj = new \$realmGrabber();");
		$this->dataGrabber = $obj;
		
	}
	
	public function getDataGrabber(){
		
		return $this->dataGrabber;
		
	}

}

?>