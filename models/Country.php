<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property string $Code
 * @property string $Name
 * @property string $Continent
 * @property string $Region
 * @property double $SurfaceArea
 * @property int $IndepYear
 * @property int $Population
 * @property double $LifeExpectancy
 * @property double $GNP
 * @property double $GNPOld
 * @property string $LocalName
 * @property string $GovernmentForm
 * @property string $HeadOfState
 * @property int $Capital
 * @property string $Code2
 *
 * @property City[] $cities
 * @property Countrylanguage[] $countrylanguages
 */
class Country extends \yii\db\ActiveRecord
{
    protected $transacao; //atributo persistente, ou seja, só existe na aplicação e não no banco de dados
                          //existe atributo persistente, do banco de dados e

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    public function beforeDelete()
    {
        $this->transacao = self::getDb()->beginTransaction();

        //Countrylanguage::deleteAll("CountryCode = '".$this->Code."'")
        try {
            Countrylanguage::deleteAll(
                ['CountryCode'=>$this->Code]
            );

            City::deleteAll(
                ['CountryCode'=>$this->Code]
            );

            if (!parent::beforeDelete()) {
                $this->transacao->rollBack();
                return false;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            $this->transacao->rollBack();
            return false;
        }


    }

    public function afterDelete () {
        $this->transacao->commit();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Code'], 'required'],
            [['Continent'], 'string'],
            /*[['Name'], 'email', 'checkDNS' => true],
            [['Name'], 'filter', 'filter' => function($value){
                return strtolower($value);
            }],*/
            [['SurfaceArea', 'LifeExpectancy', 'GNP', 'GNPOld'], 'number'],
            [['IndepYear', 'Population', 'Capital'], 'integer'],
            [['Code'], 'string', 'max' => 3],
            [['Name'], 'string', 'max' => 52],
            [['Region'], 'string', 'max' => 26],
            [['LocalName', 'GovernmentForm'], 'string', 'max' => 45],
            [['HeadOfState'], 'string', 'max' => 60],
            [['Code2'], 'string', 'max' => 2],
            [['Code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Code' => Yii::t('app', 'Code'),
            'Name' => Yii::t('app', 'Name'),
            'Continent' => Yii::t('app', 'Continent'),
            'Region' => Yii::t('app', 'Region'),
            'SurfaceArea' => Yii::t('app', 'Surface Area'),
            'IndepYear' => Yii::t('app', 'Indep Year'),
            'Population' => Yii::t('app', 'Population'),
            'LifeExpectancy' => Yii::t('app', 'Life Expectancy'),
            'GNP' => Yii::t('app', 'Gnp'),
            'GNPOld' => Yii::t('app', 'Gnpold'),
            'LocalName' => Yii::t('app', 'Local Name'),
            'GovernmentForm' => Yii::t('app', 'Government Form'),
            'HeadOfState' => Yii::t('app', 'Head Of State'),
            'Capital' => Yii::t('app', 'Capital'),
            'Code2' => Yii::t('app', 'Code2'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['CountryCode' => 'Code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountrylanguages() //OS atributos de cima não são dinâmicos. Esse atributo é dinâmico. Atributos dînâmicos começam com get
    {
        return $this->hasMany(Countrylanguage::className(), ['CountryCode' => 'Code']);
    }

    public function getCapitalObj() {
        return $this->hasOne(City::className(), ['ID' => 'Capital']);
    }

    public function getShortName() {
        return substr($this->Name,0,3);
    }

    public function getTotalCity() {
        return CountrySearch::totalCity($this->Code);
    }
}
