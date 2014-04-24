copyRecord
==========

Extension for yii. Copy the current record in CGridView according to certain rules.

## Requirements ##

 * PHP 5.3+

## Testing ##

 * Yii 1.13

## Installation ##

Copy the file CopyRecordBehaviors.php to `/protected/extensions/copyRecord` folder.

Attach the CopyRecordBehavior to your model

    public function behaviors(){
        return array(
            'copyRecord'=>array(
                'class'=>'ext.copyRecord.CopyRecordBehavior',
                'configFieldsForCopy'=>array(
                    ...
                ),
            )    
        );
    }

Example setting configFieldsForCopy

     if array of configuration is empty, then copy all field other then the primaryKey 
     else elements specifies each field for copy, which has the following format:

      'configFieldsForCopy' => array(
          //field name to copy values
          'id_customer',
          //field name and the PHP expression for the new value                                
          'date_order'=>'strftime("%Y-%m-%d %H:%M:%S")',
          //field name and the PHP expression for the new value (field value is used) 
          'comment_order'=>'$data[comment_order]." (copy)"', 
      )


Add action in the controller.

    /**
     * copying record of model
     * 
     * @param integer $id the ID of the model to be copied
     * @param string $returnUrl the action for return after copy
     */
	public function actionCopy($id, $returnUrl)
	{
        $model= $this->loadModel($id)->copy();

        $this->redirect(array($returnUrl));
	}

Add action 'copy' in the accessRules in the controller, if necessary.

	public function accessRules()
	{
		return array(
            ...
            'actions'=>array(..., 'admin','copy', ...),
                'users'=>array('admin'),
            ),
            ...
        )
    }

In the view add column

    $this->widget('zii.widgets.grid.CGridView', array(
        ...
        'columns'=>array(
            ...
            array(
                'class'=>'CLinkColumn',
                'label'=>'copy',
                'urlExpression'=>'Yii::app()->controller->createUrl("copy",array("id"=>$data->primaryKey,"returnUrl"=>Yii::app()->controller->action->id))',
            ),
            ...
        )
    )

## Usage ##

Click link copy and record must be added.