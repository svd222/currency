<?php
namespace app\data;

/**
 * represent data source
 */
interface IDataSource {
    
    /**
     * loads data from source
     */
    public function load();
    
    /**
     * returns loaded data
     */
    public function getData();
}
