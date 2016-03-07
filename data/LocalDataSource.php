<?php
namespace app\data;

namespace app\data;
use yii\base\Object;

class LocalDataSource extends Object implements IDataSource {
    
    /**
     *
     * @var string - the extracted data from source 
     */
    private $data;
    
    /**
     *
     * @var string - the route to source
     */
    private $route;
    
    public function setRoute($route) {
        $this->route = $route;
    }
            
    /**
     * Load data from local source 
     * @return boolean 
     */
    public function load() {
        $content = file_get_contents($this->route);
        if($content) {
            $this->data = $content;
            return true;
        }
        return false;
    }
    
    /**
     * Returns data
     * @return mixed The data if exist or null otherwise
     */
    public function getData() {
        return $this->data ? $this->data : null;
    }
}
