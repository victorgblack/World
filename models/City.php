<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $ID
 * @property string $Name
 * @property string $CountryCode
 * @property string $District
 * @property int $Population
 *
 * @property Country $countryCode
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Population'], 'integer'],
            [['Name'], 'string', 'max' => 35],
            [['CountryCode'], 'string', 'max' => 3],
            [['District'], 'string', 'max' => 20],
            [['CountryCode'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['CountryCode' => 'Code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('app', 'ID'),
            'Name' => Yii::t('app', 'Name'),
            'CountryCode' => Yii::t('app', 'Country Code'),
            'District' => Yii::t('app', 'District'),
            'Population' => Yii::t('app', 'Population'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountryCode()
    {
        return $this->hasOne(Country::className(), ['Code' => 'CountryCode']);
    }

    public function listOfCities($Code) {
        return self::find()->where(['CountryCode'=>$Code])->orderBy('Name ASC')->all();
    }
}
