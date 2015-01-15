<?php


/**
 *
 *
 *
 *
 *
 *
 * @author nicolaas <github@sunnysideup.co.nz>
 */



class GridFieldEditOriginalPageConfig extends GridFieldConfig_RelationEditor {

	/**
	 * @param int $itemsPerPage - How many items per page should show up
	 */
	public function __construct($itemsPerPage=null) {
		parent::__construct($itemsPerPage);
		$this
			->removeComponentsByType("GridFieldEditButton")
			->removeComponentsByType("GridFieldAddNewButton")
			->addComponent(new GridFieldEditButtonOriginalPage());
	}

}

class GridFieldEditOriginalPageConfig_WithAddButton extends GridFieldEditOriginalPageConfig {

	/**
	 * @param int $itemsPerPage - How many items per page should show up
	 */
	public function __construct($itemsPerPage=null) {
		parent::__construct($itemsPerPage);
		$this
			->addComponent(new GridFieldAddNewButtonOriginalPage());
	}

}

class GridFieldEditOriginalPageConfig_WithAddButton extends GridFieldEditOriginalPageConfig {

	/**
	 * @param int $itemsPerPage - How many items per page should show up
	 */
	public function __construct($itemsPerPage=null) {
		parent::__construct($itemsPerPage);
		$this
			->addComponent(new GridFieldAddNewButtonOriginalPage());
	}

}

class GridFieldEditOriginalPageConfig_WithoutDelete extends GridFieldEditOriginalPageConfig {

	/**
	 * @param int $itemsPerPage - How many items per page should show up
	 */
	public function __construct($itemsPerPage=null) {
		parent::__construct($itemsPerPage);
		$this
			->removeComponentsByType("GridFieldDeleteAction");
	}

}
