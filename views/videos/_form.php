<?php
/* @var $this yii\web\View */
/* @var $model app\models\Video */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Tag;

?>
<div class="video-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
        <?php
        // prepare list of tags for multi-select (sorted alphabetically)
        $tagsList = Tag::find()->select(['name'])->indexBy('id')->orderBy(['name' => SORT_ASC])->column();
        echo $form->field($model, 'tagIds')->dropDownList($tagsList, ['multiple' => true, 'size' => 6, 'id' => 'video-tagids']);
        ?>

        <!-- Select2 (CDN) для удобного выбора тегов -->
        <?php $this->registerCssFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'); ?>
        <?php $this->registerJsFile('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['depends' => [\yii\web\JqueryAsset::class]]); ?>
        <?php $this->registerJs("jQuery('#video-tagids').select2({placeholder: 'Выберите теги', width: '100%'});"); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
