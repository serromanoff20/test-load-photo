<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\Form $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'to-load-photo';
$this->params['breadcrumbs'][] = $this->title;


if (isset($errors)) {
    echo json_encode($errors, JSON_UNESCAPED_UNICODE);
}
?>

<div class="site-contact">
    <div class="row">
        <div id="qwerty" class="col-lg-4">

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <?= $form->field($model, 'photos[]')->fileInput(['multiple' => true, 'accept' => 'image/*'])?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>