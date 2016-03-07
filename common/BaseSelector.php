<?php
namespace app\common;

use Yii;
use yii\base\Object;
use app\common\SelectorInterface;

class BaseSelector extends Object implements SelectorInterface {    
    private $selectorFunc;
    private $data;
    private $params;
    
    public function setData(array $data) {
        $this->data = $data;
    }
    
    /**
     * 
     * @param \Closure $selectorFunc function for choose
     */
    public function setSelectorFunc(\Closure $selectorFunc) {
        $this->selectorFunc = $selectorFunc;
    }
    
    /**
     * @param mixed $var The var for compare
     */
    public function setParams($params) {
        $this->params = $params;
    }
    
    /**
     * returns selected object
     */
    public function select() {
        $selectorFunc = $this->selectorFunc;
        $func = $selectorFunc($this->data, $this->params);
        return $func;
    }
}