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
            <div class="col-lg-3 mb-3">
                <h2>Channels</h2>

                <p>A repository of links to interesting channels.</p>

                <p><?= Html::a('Go ahead', ['/channels/'], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left:12px;']) ?></p>
            </div>
            <div class="col-lg-3 mb-3">
                <h2>Articles</h2>

                <p>A repository of links to interesting articles.</p>

                <p><?= Html::a('Go ahead', ['/articles/'], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left:12px;']) ?></p>
            </div>
            <div class="col-lg-3 mb-3">
                <h2>Videos</h2>

                <p>A repository of links to interesting articles.</p>

                <p><?= Html::a('Go ahead', ['/videos/'], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left:12px;']) ?></p>
            </div>
            <div class="col-lg-3">
                <h2>Tags</h2>

                <p>Tags</p>

                <p><?= Html::a('Go ahead', ['/tags/'], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left:12px;']) ?></p>
            </div>
        </div>

    </div>
</div>