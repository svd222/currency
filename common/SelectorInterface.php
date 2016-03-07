<?php
namespace app\common;

interface SelectorInterface {
    
    /**
     * select function, @see app\data\SelectorDataSource for example
     * returns mixed item from `data` dependencing of `var`
     */
    public function select();
    
    /**
     * 
     * @param mixed $var
     */
    public function setParams($params);
    
    /**
     * 
     * @param array $data
     */
    public function setData(array $data);
    
    /**
     * 
     * Sets the selector comparator
     */
    public function setSelectorFunc(\Closure $selectorFunc);
}
