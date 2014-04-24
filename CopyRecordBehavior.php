<?php

/**
 * CopyRecordBehavior is behavior for CActiveRecord, which copies the current record
 * according to certain rules
 *
 * @author Oleg Pustovalov <oleg@portalnews.ru>
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @version 1.0
 */
class CopyRecordBehavior extends CActiveRecordBehavior{
    /**
     * @var array configuration for copy record
     * if array of configuration is empty, then copy all field other then the primaryKey 
     * else elements specifies each field for copy, which has the following format:
     * <pre>
     * array(
     *     //field name to copy values
     *     'id_customer',
     *     //field name and the PHP expression for the new value                                
     *     'date_order'=>'strftime("%Y-%m-%d %H:%M:%S")',
     *     //field name and the PHP expression for the new value
     *     //(field value is used) 
     *     'comment_order'=>'$data[comment_order]." (copy)"', 
     * )
     * </pre>
     */
    public $configFieldsForCopy=array();
    /**
     * @var array fields for copy
     */
    private $fieldsForCopy=array();
    /**
     * copying model
     */
    public function copy(){
        if(empty($this->configFieldsForCopy)){
            //массив конфигураци пустой
            //array of configuration is empty
            foreach ($this->owner->attributes as $name => $value) {
                if($name !== $this->owner->tableSchema->primaryKey)
                    $this->fieldsForCopy[$name] = $value;
            }    
        }
        else{
            //массив конфигурации не пустой
            //array of configuration is not empty            
            foreach ($this->configFieldsForCopy as $key => $value) {
                if(is_integer($key)){
                    //если ключ integer, то $value - имя поля
                    //if key is integer, then value - filed name                    
                    $this->fieldsForCopy[$value] = $this->owner->getAttribute($value);
                }
                else{
                    //ключ - имя поля, значение - выражение php
                    //key - field name, value - php expression                  
                    $data = $this->owner->attributes;
                    $this->fieldsForCopy[$key] = $this->evaluateExpression($value, array('data'=>$data));
                }
            }
        }
        $newRecord = new $this->owner;
        $newRecord->setAttributes($this->fieldsForCopy);
        $newRecord->save();

    }      
}
