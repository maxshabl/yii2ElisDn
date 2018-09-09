<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%recruit}}".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $employee_id
 * @property string $date
 *
 * @property Employee $employee
 * @property Order $order
 */
class Recruit extends ActiveRecord
{
    public static function create($employeeId, $orderId, $date)
    {
        $recruit = new self();
        $recruit->employee_id = $employeeId;
        $recruit->order_id = $orderId;
        $recruit->date = $date;
        return $recruit;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%recruit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'employee_id', 'date'], 'required'],
            [['order_id', 'employee_id'], 'integer'],
            [['date'], 'date', 'format' => 'php:Y-m-d'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order',
            'employee_id' => 'Employee',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
