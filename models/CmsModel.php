<?php

namespace yii2mod\cms\models;

use Yii;
use yii\db\ActiveRecord;
use yii2mod\cms\models\enumerables\CmsStatus;

/**
 * This is the model class for table "Cms".
 * @property integer $id
 * @property string  $url
 * @property string  $title
 * @property string  $content
 * @property integer $status
 * @property string  $metaTitle
 * @property string  $metaDescription
 * @property string  $metaKeywords
 * @property integer $createdAt
 * @property integer $updatedAt
 */
class CmsModel extends ActiveRecord
{

    /**
     * Declares the name of the database table associated with this AR class.
     * By default this method returns the class name as the table name by calling [[Inflector::camel2id()]]
     * with prefix [[Connection::tablePrefix]]. For example if [[Connection::tablePrefix]] is 'tbl_',
     * 'Customer' becomes 'tbl_customer', and 'OrderItem' becomes 'tbl_order_item'. You may override this method
     * if the table is not named after this convention.
     *
     * @return string the table name
     */
    public static function tableName()
    {
        return 'Cms';
    }

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     * @return array validation rules
     * @see scenarios()
     */
    public function rules()
    {
        return [
            [['url', 'title', 'content', 'metaTitle'], 'required'],
            [['url'], 'match', 'pattern' => '/^[a-z0-9\/-]+$/'],
            [['content', 'metaTitle', 'metaDescription', 'metaKeywords'], 'string'],
            [['url'], 'unique'],
            [['status', 'createdAt', 'updatedAt'], 'integer'],
            [['url', 'title'], 'string', 'max' => 255]
        ];
    }

    /**
     * Returns the attribute labels.
     *
     * Attribute labels are mainly used for display purpose. For example, given an attribute
     * `firstName`, we can declare a label `First Name` which is more user-friendly and can
     * be displayed to end users.
     *
     * @return array attribute labels (name => label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'url' => Yii::t('app', 'Url'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'status' => Yii::t('app', 'Status'),
            'metaTitle' => Yii::t('app', 'Meta Title'),
            'metaDescription' => Yii::t('app', 'Meta Description'),
            'metaKeywords' => Yii::t('app', 'Meta Keywords'),
            'createdAt' => Yii::t('app', 'Date Created'),
            'updatedAt' => Yii::t('app', 'Date Updated'),
        ];
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * Child classes may override this method to specify the behaviors they want to behave as.
     *
     * @return mixed
     */
    public function behaviors()
    {
        $behaviors['CTimestampBehavior'] = [
            'class' => 'yii\behaviors\TimestampBehavior',
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['createdAt'],
                ActiveRecord::EVENT_BEFORE_UPDATE => ['updatedAt'],
            ]
        ];
        return $behaviors;
    }

    /**
     * Find page
     * @param $url
     * @return array|null|ActiveRecord
     */
    public function findPage($url)
    {
        return self::find()
            ->where(['url' => $url, 'status' => CmsStatus::ENABLED])
            ->one();
    }
}

    