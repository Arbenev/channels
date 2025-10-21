<?php

use yii\helpers\Html;

/** @var yii\web\View $this */

$this->title = 'Link repository';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4"><?= $this->title ?></h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4 mb-3">
                <h2>Channels</h2>

                <p>A repository of links to interesting channels.</p>

                <p><?= Html::a('Go ahead', ['channels/index'], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left:12px;']) ?></p>
            </div>
            <div class="col-lg-4 mb-3">
                <h2>Articles</h2>

                <p>A repository of links to interesting articles.</p>

                <p><?= Html::a('Go ahead', ['articles/index'], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left:12px;']) ?></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Tags</p>

                <p><?= Html::a('Go ahead', ['tags/index'], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left:12px;']) ?></p>
            </div>
        </div>

    </div>
</div>