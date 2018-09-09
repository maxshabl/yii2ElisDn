<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%interview}}".
 *
 * @property integer $id
 * @property string $date
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property integer $status
 * @property string $reject_reason
 * @property integer $employee_id
 */
class Interview extends ActiveRecord
{
    const STATUS_NEW = 1;
    const STATUS_PASS = 2;
    const STATUS_REJECT = 3;

    public static function getStatusList()
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_PASS => 'Passed',
            self::STATUS_REJECT => 'Rejected',
        ];
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusList(), $this->status);
    }

    public static function create($lastName, $firstName, $email, $date)
    {
        $interview = new Interview();
        $interview->date = $date;
        $interview->last_name = $lastName;
        $interview->first_name = $firstName;
        $interview->email = $email;
        $interview->status = Interview::STATUS_NEW;
        return $interview;
    }

    public function editData($lastName, $firstName, $email)
    {
        $this->last_name = $lastName;
        $this->first_name = $firstName;
        $this->email = $email;
    }

    public function move($date)
    {
        $this->guardNotCurrentDate($date);
        $this->date = $date;
    }

    public function reject($reason)
    {
        $this->guardNotRejected();
        $this->reject_reason = $reason;
        $this->status = self::STATUS_REJECT;
    }

    public function pass($employeeId)
    {
        $this->guardNotPassed();
        $this->employee_id = $employeeId;
        $this->status = self::STATUS_PASS;
    }

    public function isRecruitable()
    {
        return $this->status == Interview::STATUS_NEW;
    }

    public static function tableName()
    {
        return '{{%interview}}';
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'status' => 'Status',
            'reject_reason' => 'Reject Reason',
            'employee_id' => 'Employee',
        ];
    }

    private function guardNotRejected()
    {
        if ($this->status == self::STATUS_REJECT) {
            throw new \DomainException('Interview is alredy rejected.');
        }
    }

    private function guardNotPassed()
    {
        if ($this->status == self::STATUS_PASS) {
            throw new \DomainException('Interview is alredy passed.');
        }
    }

    /**
     * @param $date
     * @throws \DomainException
     */
    private function guardNotCurrentDate($date)
    {
        if ($date == $this->date) {
            throw new \DomainException('Date is current.');
        }
    }
}
