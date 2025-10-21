<?php
/* @var $this yii\web\View */
use yii\helpers\Html;

$this->title = 'Каналы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channels-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <pre><?= print_r($channels, true) ?></pre>
    <pre><?= print_r($tags, true) ?></pre>
    <p>Path to this view file:</p>
    <code><?= __FILE__ ?></code>
</div>