<?php

class ItemSingleton {
	private $name = 'alien weapon';
	private static $item = null;
	private static $isFound = false;
	
	private function __construct() {
    }
	
	static function findItem() {
      if (self::$isFound == false) {
        if (self::$item == null) {
           self::$item = new ItemSingleton();
        }
        self::$isFound = true;
        return self::$item;
      } else {
        return null;
      }
    }
	
	function loseItem(ItemSingleton $itemLost) {
        self::$isFound = false;
    }
	
	function getItemName() { return $this->name}
}

class ItemOwner {
    private $ownedItem;
    private $haveItem = false;

    function __construct() {
    }

    function getItemName() {
      if ($this->haveItem == true) {
        return $this->ownedItem->getItemName();
      } else {
        return "I don't have the item";
      }
    }

    function findItem() {
      $this->ownedItem = ItemSingleton::findItem();
      if ($this->ownedItem == null) {
        $this->haveItem = false;
      } else {
        $this->haveItem = true;
      }
    }

    function loseItem() {
		$this->ownedItem->loseItem($this->ownedItem);
	}
}
  
/* singleton demonstration */  
  
	writeln('demonstruojamas singleton veikimas (pagrindas)');
	writeln('');

	$itemOwner1 = new ItemOwner();
	$itemOwner2 = new ItemOwner();

	$itemOwner1->findItem();
	writeln('itemOwner1: searching for item');
	writeln('itemOwner1: my item name: ');
	writeln($itemOwner1->getItemName());
	writeln('');

	$itemOwner2->findItem();
	writeln('itemOwner2: searching for item');
	writeln('itemOwner2: my item name: ');
	writeln($itemOwner2->getItemName());
	writeln('');

	$itemOwner1->loseItem();
	writeln('itemOwner1: i lost the item');
	writeln('');

	$itemOwner2->findItem();
	writeln('itemOwner2: my item name: ');
	writeln($itemOwner1->getItemName());
	writeln('');

	writeln('demonstravimas baigiamas');

	function writeln($text) {
		echo $text.'<br/>';
	}