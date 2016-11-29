<?php

class SiteTreeCheckboxSetField extends CheckboxSetField
{

    /**
     *
     * @var Int
     */
    protected $siteTreeParentID = 0;

    /**
     *
     * @var Array
     */
    protected $arrayOfAllowedIDs = array();

    /**
     *
     * @var array
     */
    protected $classNamesForItems = array("SiteTree");

    /**
     *
     * @var Array
     */
    protected $siteTreeParentAllChildren = array();

    /**
     * Creates a new dropdown field.
     *
     * @param string $name The field name
     * @param string $title The field title
     * @param array $source An map of the dropdown items
     * @param string|array $value You can pass an array of values or a single value like a drop down to be selected
     * @param int $size Optional size of the select element
     * @param form The parent form
     */
    public function __construct($name, $title = '', $source = array(), $value='', $form=null, $emptyString=null)
    {
        Requirements::css('sitetreeformfields/css/SiteTreeCheckboxSetField.css');
        parent::__construct($name, $title, $source, $value, $form, $emptyString);
    }

    /**
     *
     * @param Int $id
     */
    public function setSiteTreeParentID($id)
    {
        $this->siteTreeParentID = $id;
    }

    /**
     *
     * @param Int $id
     * @param Int | string $array
     */
    public function setSiteTreeParentAndChildClassNames($id, $array)
    {
        $this->siteTreeParentID = $id;
        $this->setClassNamesForItems($array);
    }

    /**
     *
     * @param Array | str $array
     */
    public function setClassNamesForItems($array)
    {
        unset($this->classNamesForItems);
        if (is_array($array)) {
            $this->classNamesForItems = $array;
        } else {
            $this->classNamesForItems = array($array);
        }
        $this->source = $this->getSource();
    }

    /**
     *
     * @param String $str
     */
    public function addClassNameForItems($str)
    {
        $this->classNamesForItems[] = $str;
    }


    /**
     *
     * @return Array
     */
    public function getSource()
    {
        $source = parent::getSource();
        //debug::log("original source count ".implode($this->classNamesForItems)." ".$this->siteTreeParentID.": ".count($source));
        if ($this->siteTreeParentID) {
            $arrayItems = array();
            $source = $this->getAllChildrenForSiteTreeParent($this->siteTreeParentID);
        }
        return $source;
    }

    /**
     * @param Int
     * @return Array
     */
    private function getAllChildrenForSiteTreeParent($parentID)
    {
        $children = SiteTree::get()->filter(array("ParentID" => $parentID));
        if ($children && $children->count()) {
            foreach ($children as $child) {
                foreach ($this->classNamesForItems as $matchingCLassName) {
                    //debug::log("has child");
                    if ($child instanceof $matchingCLassName) {
                        //debug::log("we now have ".count($this->siteTreeParentAllChildren)." children");
                        $this->siteTreeParentAllChildren[$child->ID] = $child->MenuTitle;
                    }
                }
                $this->getAllChildrenForSiteTreeParent($child->ID);
            }
        }
        return $this->siteTreeParentAllChildren;
    }
}
