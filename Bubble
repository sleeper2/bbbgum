<?php

/**
 * This is the model class for table "{{bubble}}".
 *
 * The followings are the available columns in table '{{bubble}}':
 * @property integer $id
 * @property integer $frame_id
 * @property string $text
 * @property string $top
 * @property string $left
 * @property string $height
 * @property string $width
 *
 * The followings are the available model relations:
 * @property Frame $frame
 */
class Bubble extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{bubble}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('frame_id, top, left', 'required'),
            array('frame_id', 'numerical', 'integerOnly'=>true),
            array('frame_id', 'availableFrameValidator', 'range' => array(Yii::app()->params['editableObject']), 'on' => 'insert'),
            array('top, left, height, width', 'length', 'max'=>255),
            array('text, point_top, point_left', 'safe'),
            array('frame_id, object_id', 'unsafe', 'on'=>'update'),
        );
    }

    public function availableFrameValidator($attribute,$params)
    {
        $range = isset($params['range']) ? $params['range'] : array();
        $message = isset($params['message']) ? $params['message'] : 'Object is not in the list.';
        $frame_id = $this->$attribute;
        $id = Yii::app()->db->createCommand()
            ->select('object_id')
            ->from(Frame::model()->tableSchema->name)
            ->where('id = :id', array(':id' => $frame_id))
            ->queryScalar();
        if (!$id || !in_array($id, $range)) {
            $this->addError($attribute, $message);
        }
        $this->object_id = $id;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'frame' => array(self::BELONGS_TO, 'Frame', 'frame_id'),
            'object' => array(self::BELONGS_TO, 'Object', 'object_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'frame_id' => 'Frame',
            'text' => 'Text',
            'top' => 'Top',
            'left' => 'Left',
            'height' => 'Height',
            'width' => 'Width',
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Bubble the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function scopes()
    {
        return array(
            'iCanView' => array(
                'condition' => 't.object_id = :allowed',
                'params' => array(':allowed' => Yii::app()->params['viewableObject']),
            ),
            'iCanEdit' => array(
                'condition' => 't.object_id = :allowed',
                'params' => array(':allowed' => Yii::app()->params['editableObject']),
            ),
        );
    }

    public function withId($id)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.id = :id',
            'params' => array(
                ':id' => $id,
            ),
        ));
        return $this;
    }

    public function withFrameId($id)
    {
        if ($id) {
            $this->getDbCriteria()->mergeWith(array(
                'condition' => 't.frame_id = :frame_id',
                'params' => array(
                    ':frame_id' => $id,
                ),
            ));
        }
        return $this;
    }

    public static function cloneFrom($oldModel, $frame)
    {
        $model = new static();


        $model->frame_id = $frame->id;
        $model->object_id = $frame->object_id;
        $model->text = $oldModel->text;
        $model->top = $oldModel->top;
        $model->left = $oldModel->left;
        $model->point_top = $oldModel->point_top;
        $model->point_left = $oldModel->point_left;
        $model->height = $oldModel->height;
        $model->width = $oldModel->width;

        $model->save(false);
    }
}
