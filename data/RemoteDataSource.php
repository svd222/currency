<?php
namespace app\data;

use app\helpers\UrlContentHelper;
use yii\base\Object;

class RemoteDataSource extends Object implements IDataSource {
    
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
     * Load data from remote source 
     * @return boolean 
     */
    public function load() {
        $content = UrlContentHelper::getContent($this->route);
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
