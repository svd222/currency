<?php
namespace app\data\batch;

interface UploaderInterface {
    /**
     * return count records to be updated
     */
    public function getUpdateCount();
    
    /**
     * returns count record to be inserted
     */
    public function getInsertCount();
    
    /**
     * returns count of all records to be inserted/updated
     */
    public function getCount();
    
    /**
     * returns the prepared sql to upload
     */
    public function getPreparedSql();
    
    /**
     * prepares the query to upload
     */
    public function prepare();
    
    /**
     * upload data
     */
    public function upload();
}
