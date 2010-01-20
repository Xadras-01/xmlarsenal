<?php

/**
 * Item.class.php
 * 
 * An instance of this class is the representation of an item. It stores all attributes concerning
 * (i.e. sockets and enchants). Items are only stored within the character-object.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/08/25
 * @package XMLArsenal
 * @subpackage classes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/

class Item{
	
	/**
	 * the item-id
     * @access public
     * @var int
     */
	public $itemId;
	
	/**
	 * the item slot (i.e. 0 => head)
     * @access public
     * @var int
     */
	public $itemSlot;
	
	/**
	 * permanent enchant
     * @access public
     * @var int
     */
	public $enchant;
	
	/**
	 * random enchant, i.e. "of the whale", "of the owl" etc.
     * @access public
     * @var int
     */
	public $randomEnchantId;
	
	/**
	 * socket1
     * @access public
     * @var int
     */
	public $socket1;
	
	/**
	 * socket2
     * @access public
     * @var int
     */
	public $socket2;
	
	/**
	 * socket3
     * @access public
     * @var int
     */
	public $socket3;
	
	/**
	 * socket4
     * @access public
     * @var int
     */
	public $socket4;
	
	/**
	 * socket5
     * @access public
     * @var int
     */
	public $socket5;
	
	/**
	 * the items durability
     * @access public
     * @var int
     */
	public $currentDurability;
	
	
	/**
     * Constructor sets up the item
	 * @access public
	 * @param array $itemarray
	 * @param int $slot
	 * @return void
    */
	public function __construct($slot, $itemarray) {
		
		$this->itemId = $itemarray['id'];
		$this->itemSlot = $slot;
		$this->enchant = $itemarray['ench'];
		$this->socket1 = $itemarray['socket1'];
		$this->socket2 = $itemarray['socket2'];
		$this->socket3 = $itemarray['socket3'];
		$this->socket4 = 0; //not used for now
		$this->socket5 = 0; //not used for now
		$this->currentDurability = $itemarray['dur'];
		$this->randomEnchantId = $itemarray['randomEnchant'];
		
	}
	
}

?>