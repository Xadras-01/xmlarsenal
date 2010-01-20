<?php

/**
 * ParentbasedTree.class.php
 * 
 * This class reperesents a tree-structure. It's based on the original php4 Tree.inc.php written by
 * Martin Weis <tree@datenroulette.de> and published at http://www.phpclasses.org/browse/package/2817.html
 * under the GPL v2 (http://www.opensource.org/licenses/gpl-license.html). Thanks.
 * The Tree-class has been widely extended and modified to be a PHP5 OOP-class and to meet the requirements
 * of the XMLArsenal achievement functionality.
 *
 * @author Amras Taralom <amras-taralom@streber24.de>
 * @version 1.0, last modified 2009/10/04
 * @package XMLArsenal
 * @subpackage classes
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License version 3 (GPLv3)
 *
*/


class ParentbasedTree{
	
	/**
	* ID of this Node
	* @var int 
	* @access public
	*/
	public $id;
	
	/**
	* level of this Node
	* @var int 
	* @access public
	* @deprecated use function {@link getLevel()}
	*/
	public $level;

	/**
	* parent node (reference)
	* @var Tree 
	* @access public
	*/
	public $parent;

	/**
	* array of children, with references
	* @var array 
	* @access public
	*/
	public $children;

	/**
	* reference to a selected node
	* @access public
	*/
	public $selectedNode;
	
	/**
	* data load of this node
	* @access public
	*/
	public $data;
	
	/**
	* number of achpoints in this category + lower categories
	* @var int 
	* @access public
	*/
	public $achPoints;
	
	/**
	* number of reached achpoints in this category + lower categories
	* @var int 
	* @access public
	*/
	public $reachedPoints;
	
	/**
	* number of achievements in this category + lower categories
	* @var int 
	* @access public
	*/
	public $numAch;
	
	/**
	* number of reached achievements in this category + lower categories
	* @var int 
	* @access public
	*/
	public $reachedAch;
	
	/**
	* used by coundDown() to store info about the node
	* @var array 
	* @access public
	*/
	public $nodeInfo;


	/**
	* default constructor
	* 
	* creates a node
	* @access public
	* @param &Tree or null, set parent or null for root node  
	* @param mixed data load
	* @return Tree
	*/
	public function __construct(&$parent = null, $data = null) {
		
		if ($parent === null){
			// this is a root node
			$this->level=0;
		}//if
		
		//initialize with zero
		$this->achPoints = $this->numAch = $this->reachedAch = $this->reachedPoints = 0;
		
		$this->data = $data;
		$this->children = array(); //initialize with no children	
		
		// for the root node parent will be null 
		$this->parent = &$parent;
		
	}//__construct()
	
	
	/**
	* add a child
	* @access public
	* @param mixed data load
	* @return Tree reference to the child
	*/
	public function &addChild($id, $data) {
		
		$this->children[$id] = &new ParentbasedTree(&$this, $data);
		$this->children[$id]->setId($id);
		$this->children[$id]->level = $this->level + 1;
		
		return $this->children[$id];
		
	}//addChild()
	
	
	/**
	* removes a child
	* @param int id
	* @return boolean success (true/false)
	*/
	public function removeChild($id) {
		
		if (!array_key_exists($id,$this->children)){
			
			//echo "removal of child with key ".$id." failed: does not exist";
			return false;
			
		}else{
			
			unset($this->children[$id]);
			return true;
			
		}//if
		
	}//removeChild()


	/**
	* delete this node
	* @return boolean success state
	*/
	public function delete(){
		
		if (!$this->isRoot){
			
			// remove using the _parent
			return $this->parent->removeChild($this->id);
			
		}else {
			
			// in the root node unset object
			unset ($this); //->children=array();
			return true;
			
		}//if
		
	}//delete()


	/**
	* get object id
	* @return int id
	*/
	public function getId(){
		
		return $this->id;
		
	}//getId()
	
	
	/**
	* set object id
	* @access private
	* @return void
	*/
	public function setId($id){
		
		$this->id=$id;
		
	}//setId()
	
	
	/**
	* get number of children
	* @return int number of children	
	*/
	public function numChildren(){
		
		return count($this->children);
		
	}//numChildren()

	
	/**
	* get IDs of the children
	* @return array of IDs
	*/
	public function getChildrenIds(){
		
		return array_keys($this->children);
		
	}//getChildrenIds
	
	
	/**
	* check if this is the root node
	* @return boolean
	*/
	public function isRoot(){
		if ($this->parent==null){
			
			return true;
			
		}else{
			return false;
		}//if
		
	}//
	
	
	/**
	* get root Node
	* @return Tree object reference to root node
	*/
	public function &getRoot(){
		
		$tmp=&$this;
		
		while (!$tmp->isRoot()){
			// iterate through parents, add IDs to (begin of) array
			$tmp=&$tmp->parent;
		}//while
		
		return $tmp;
		
	}//getRoot()
	
	
	/**
	* get the 'path' of the root node to this node (IDs)
	* @return array of ancestors IDs
	*/
	public function getPath(){
		
		$idarray=array();
		$tmp=&$this;
		
		while (!$tmp->isRoot()){
			
			// iterate through parents, add IDs to (begin of) array
			array_unshift ($idarray, $tmp->id);
			$tmp=&$tmp->parent;
			
		}//while
		
		return $idarray;
		
	}//getPath()
	
	
	/**
	* find a child by its id (if used on root level this means: search tree for a node)
	* @return mixed $node (&ParentbasedTree - the requested node OR false if there is no such id)
	*/
	public function &getChildById($childId, $startnode = null){
		
		if($startnode === null) $startnode = &$this;
		$tmp = &$this;
		$this->selectedNode = false;
		
		if($tmp->numChildren() > 0){
			
			foreach($tmp->children as $child){
				
				if($child->id == $childId){
					
					//print_r($child);die();
					$startnode->selectedNode = &$child;
					return $startnode->selectedNode;
					
				}else{
					
					$tmp = &$child;
					if($child instanceof ParentBasedTree){
						
						$tmp->getChildById($childId, $startnode);
						
					}//if
					
				}//if
				
			}//foreach
			
		}
		
		return $startnode->selectedNode;
		
	}//getChildbyId()
	
	
	/**
	* get Level
	* @return int level
	*/
	public function getLevel(){
		
		$level=0;
		$tmp=&$this;
		
		while (!$tmp->isRoot()){
			
			//echo "adding level for parent, id :".$this->id;
			$tmp=&$tmp->parent;
			$level++;
			
		}//while
		
		return $level;
		
	}//getLevel()
	
	
	/**
    * echo the structure of the subnodes of our tree/node
    * @param string prefix (used for the recursive output)
    * @return void
    */
    public function echoStructure($pre='') {
        
		for ($i=0;$i<$this->getLevel();$i++){
			
            $pre.='|';
        }
        
        echo $pre."+[".$this->id.']'.' "'.$this->data.'"';
        if (is_array($this->data)){
            foreach ($this->data as $key=>$value) {
                     echo '('.$key.'|'.$value.')';
            }
        }
        
		echo "\n";
		
        if ($this->numChildren()>0){
            
            foreach ($this->children as $child) {
                $child->echoStructure($pre);
            }
        }
    }
	
	
	/**
    * echo the structure of the categories in the tree or node
    * @return void (must be fetched with ob_start + ob_get_clean)
    */
    public function getCategoryStructure(){
        
		if($this->id != 1){
			
			if(isset($this->id)){
				
				echo '<category id="'.$this->id.'" name="'.str_replace('&', '&amp;', $this->data['categoryname']).'">'."\n";
				
			}//if
			
			if ($this->numChildren() > 0){
				
				//re-order the achievementcategories
				if($this->level == 0){
					
					uasort($this->children, array($this, 'sortByBlizz'));
					
				}//if
				
				foreach ($this->children as $child) {
					
					
					if($child instanceof ParentBasedTree){
						
						$child->getCategoryStructure();
						
					}
					
				}//foreach
				
			}//if
			
			if($this->level != 0) echo '</category>';
			
		}//if
		
		return;
		
    }//echoCategoryStructure()
	
	
	/**
     * this is a callback function for sorting achievements like blizz does
	 * @access private
	 * @param array $a
	 * @param array $b
	 * @return int
    */
	private function sortByBlizz($a, $b){
		
		//just to be safe, this shouldn't happen
		if($a->id == $b->id) return 0;
		
		if($a->id == 81) return 1;
		if($a->id == 92) return -1;
		
		if($a->id == 96){
			if($b->id == 92) return 1;
			else return -1;
		}
		
		if($a->id == 97){
			if($b->id == 92 || $b->id == 96) return 1;
			else return -1;
		}
		
		if($a->id == 95){
			if($b->id == 92 || $b->id == 96 || $b->id == 97) return 1;
			else return -1;
		}
		
		if($a->id == 168){
			if($b->id == 92 || $b->id == 96 || $b->id == 97 || $b->id == 95) return 1;
			else return -1;
		}
		
		if($a->id == 169){
			if($b->id == 92 || $b->id == 96 || $b->id == 97 || $b->id == 95 || $b->id == 168) return 1;
			else return -1;
		}
		
		if($a->id == 201){
			if($b->id == 92 || $b->id == 96 || $b->id == 97 || $b->id == 95 || $b->id == 168 || $b->id == 169) return 1;
			else return -1;
		}
		
		if($a->id == 155){
			if($b->id == 92 || $b->id == 96 || $b->id == 97 || $b->id == 95 || $b->id == 168 || $b->id == 169 || $b->id == 201) return 1;
			else return -1;
		}
		
		return 1;
		
	}//sortByRating()
	
}//class ParentBasedTree{}

?>