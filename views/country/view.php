<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Country */

$this->title = $model->Name;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//registra o uso do arquivo css/paises.css
$this->registerCssFile('@web/css/paises.css');
?>
<div class="country-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->Code], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->Code], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'Code',
            'Name',
            'Continent',
            'Region',
            'SurfaceArea',
            'IndepYear',
            'Population',
            'LifeExpectancy',
            'GNP',
            'GNPOld',
            'LocalName',
            'GovernmentForm',
            'HeadOfState',
            'capitalObj.Name',
            'Code2',
        ],
    ]) ?>

    <h2>Lista de cidades</h2>
    <div class="paises">
        <ul>
<?php
foreach ($listOfCities as $city) {
    echo "<li>".$city->Name."</li>";
}

?>
        </ul>
    </div>

</div>
