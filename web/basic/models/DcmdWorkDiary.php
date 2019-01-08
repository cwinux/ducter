<?php

namespace app\models;

use Yii;
include_once(dirname(__FILE__)."/../common/interface.php");

/**
 * This is the model class for table "dcmd_node".
 *
 */
class DcmdWorkDiary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'dcmd_work_diary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'description', 'process', 'date', 'cost_time'], 'required'],
            [['cost_time'], 'integer'],
            [['type'], 'string', 'max' => 16],
            [['description'], 'string', 'max' => 255],
            [['date'], 'string', 'max' => 32],
            [['jira_add'], 'string', 'max' => 128],
            [['process'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => 'Type',
            'description' => 'Description',
            'process' => 'Process',
            'date' => 'Date',
            'cost_time' => 'Cost Time',
            'diary_id' => 'Diary Id',
            'jira_add' => 'Jira Add',
        ];
    }
}
