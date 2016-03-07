<?php
namespace app\data\batch\mysql;

use Yii;
use app\data\batch\UploaderInterface;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\base\InvalidConfigException;
use yii\base\Configurable;

class Uploader extends Component implements UploaderInterface, Configurable {
    
    public $data;
    public $dataTable;
    public $fields = [];
    public $keyField;
    
    private $tablePrefix;
    
    private $_preparedSql = '';
    
    private $dataKeys;
    private $dataTableTemp;
    private $dataIndexByKeyField;
    
    private $_updateCount = 0;
    private $_insertCount = 0;
    private $_count = 0;
    
    public function __construct($config = array()) {
        parent::__construct($config);
    }
    
    /**
     * sets the init variables and call validateRequired method to validate.
     */
    public function init() {
        $this->tablePrefix = Yii::$app->db->tablePrefix;
        $this->dataTable = $this->tablePrefix . $this->dataTable;
        $this->dataTableTemp = $this->tablePrefix . $this->dataTable.'_temp';
        $this->validateRequired();
        parent::init();
    }

    public function setData($data) {
        if(!is_array($data)) {
            throw new InvalidParamException('`data` must be an array');
        }
        $this->data = $data;
        $pKey = $this->keyField;
        $that = $this;
        $this->dataKeys = array_map(function($obj) use($that, $pKey) {
            return $obj->$pKey;
        }, $data);
        $kF = $this->keyField;
        foreach($this->data as $k=>$v) {
            $this->dataIndexByKeyField[$v->$kF] = $v;
        }
        $this->_count = count($data);
    }
    
    public function getUpdateCount() {
        return $this->_updateCount;
    }
    
    public function getInsertCount() {
        return $this->_insertCount;
    }
    
    public function getCount() {
        return $this->_count;
    }
    
    public function getPreparedSql() {
        return $this->_preparedSql;
    }
    
    private function validateRequired() {
        $params = ['dataTable','keyField','fields'];
        foreach($params as $k) {
            if(!isset($this->$k)) {
                throw new InvalidConfigException(sprintf('`%s` is not set', $k));
            }
        }
        if(!is_array($this->fields)) {
            throw new InvalidParamException('`fields` must be an array');
        }
    }
    
    public function prepare() {
        $insert = $update = [];
        $insertSql = $updateSql = '';
        $update = Yii::$app->db->createCommand("SELECT * FROM ".$this->dataTable." WHERE ".$this->keyField." IN('".join("','",  $this->dataKeys)."')")->queryAll();
        $this->_updateCount = count($update);
        if(!empty($update)) {
            $updateSql = " 
                CREATE TEMPORARY TABLE `".$this->dataTableTemp."` (
                    `symbol` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
                    `rate` decimal(19,4) NOT NULL,
                    PRIMARY KEY (`symbol`),
                    UNIQUE KEY `symbol` (`symbol`)
                  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;\n

                INSERT INTO `".$this->dataTableTemp."`(`symbol`,`rate`) VALUES 
            ";
            foreach($update as $k=>$v) {
                $updateSql .= "('".$v['symbol']."',". $this->dataIndexByKeyField[$v['symbol']]->rate."),";
            }
            $updateSql = substr($updateSql,0,strlen($updateSql) - 1).";\n";
            $updateSql .= "
                UPDATE ".$this->dataTable." INNER JOIN "
                    .$this->dataTableTemp." ON ".$this->dataTable.".symbol = ".$this->dataTableTemp.".symbol ".
                    "SET ".$this->dataTable.".rate = ".$this->dataTableTemp.".rate;\n";

        }
        if(count($update) != count($this->data)) {
            foreach($this->data as $k=>$v) {
                $found = false;
                foreach($update as $kk=>$vv) {
                    if($v->symbol == $vv['symbol']) {
                        $found = true;
                        break;
                    }
                }
                if(!$found) {
                    $insert[] = $v;
                    $this->_insertCount++;
                }
            }
            $insertSql = "INSERT INTO ".$this->dataTable."(`symbol`,`rate`) VALUES ";
            foreach($insert as $k=>$v) {
                $insertSql .= "('".$v->symbol."',".$v->rate."),";
            }
            $insertSql = substr($insertSql,0,strlen($insertSql) - 1).";\n";
        } 
        $this->_preparedSql = explode(';', $updateSql.$insertSql);
        return $this;
    }
    
    public function upload() {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            foreach ($this->_preparedSql as $k=>$v) {
                if(trim($v) != '') {
                    $db->createCommand($v)->execute();
                }
            }
            $transaction->commit();
        }
        catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
