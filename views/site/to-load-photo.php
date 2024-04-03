<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'to-load-photo';
$this->params['breadcrumbs'][] = $this->title;
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

<script>
    if (document.location.hash === '#success') {
        let p = document.createElement("div");
        p.textContent = document.location.hash;
        p.classList.add('alert-success');

        try {
            let row = document.getElementsByClassName("row")[0];
            row.appendChild(p);
        } catch (e) {
            console.warn(e);
        }
    }
</script>