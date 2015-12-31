<?php

class SiteTreeListboxField extends ListboxField
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
    public function __construct($name, $title = '', $source = array(), $value = '', $size = null)
    {
        parent::__construct($name, $title, $source, $value, $size, true);
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
     * @param Array | SS_Map
     */
    public function setSource($source)
    {
        if ($source instanceof SS_Map) {
            $source = $source->toArray();
        }
        if ($source) {
            $hasCommas = array_filter(
                array_keys($source),
                create_function('$key', 'return strpos($key, ",") !== FALSE;')
            );
            if ($hasCommas) {
                throw new InvalidArgumentException('No commas allowed in $source keys');
            }
        }
        parent::setSource($source);
        return $this;
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
            $allChildrenForSiteTreeParent = $this->getAllChildrenForSiteTreeParent($this->siteTreeParentID);
            //debug::log("new source count: ".count($allChildrenForSiteTreeParent));
            $finalSource = array();
            foreach ($source as $sourceKey => $sourceValue) {
                if (isset($allChildrenForSiteTreeParent[$sourceKey])) {
                    $finalSource[$sourceKey] = $sourceValue;
                }
            }
        } else {
            $finalSource = $source;
        }
        //debug::log("final source count: ".count($finalSource));
        return $finalSource;
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
                        $this->siteTreeParentAllChildren[$child->ID] = $child->ID;
                    }
                }
                $this->getAllChildrenForSiteTreeParent($child->ID);
            }
        }
        return $this->siteTreeParentAllChildren;
    }
}
