<?php

use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\Photos $model */


$this->title = 'My Yii Application';

$rootPath = Yii::$app->basePath;
$path = '/../uploads/';

?>
<div class="site-index">
    <div class="body-content">
        <p style="margin-bottom: 5px">Сортировать по: </p>
        <p style="display: inline-flex"><a class="btn btn-outline-secondary" onclick="toSort('BySortDateTime')">дате и времени</a></p>
        <p style="display: inline-flex"><a class="btn btn-outline-secondary" onclick="toSort('BySortName')">наименованию</a></p>

        <div class="row bysort">
            <?php foreach ($model->getPhotosBySortDateTime() as $photo) {?>
                <div class="col-lg-4 mb-3">
                    <h2><?= $photo['unique_name_photo']?></h2>
                    <p><?= $photo['date']?></p>
                    <p><?= $photo['time']?></p>
                    <p style="margin-top: 20px"><a class="btn btn-outline-secondary" href="<?= Url::to('site/get-photos?id='.$photo['id']) ?>">получить json &raquo;</a></p>

                    <a href="<?='./uploads/' . $photo['name_file']?>" target="_blank">
                        <img class="img-style" src="<?= Url::to($path . $photo['name_file'], true) ?>" alt="<?= $photo['name_file']?>">
                    </a>

                    <p style="margin-top: 20px"><a class="btn btn-outline-secondary" href="#" download="<?='./uploads/' . $photo['name_file']?>">скачать &raquo;</a></p>
                </div>
            <?php } ?>
        </div>


        <div class="row bysort">
            <?php foreach ($model->getPhotosBySortName() as $photo) {?>
                <div class="col-lg-4 mb-3">
                    <h2><?= $photo['unique_name_photo']?></h2>
                    <p><?= $photo['date']?></p>
                    <p><?= $photo['time']?></p>
                    <p style="margin-top: 20px"><a class="btn btn-outline-secondary" href="<?= Url::to('site/get-photos?id='.$photo['id']) ?>">получить json &raquo;</a></p>

                    <a href="<?='./uploads/' . $photo['name_file']?>" target="_blank">
                        <img class="img-style" src="<?= Url::to($path . $photo['name_file'], true) ?>" alt="<?= $photo['name_file']?>">
                    </a>

                    <p style="margin-top: 20px"><a class="btn btn-outline-secondary" href="#" download="<?='./uploads/' . $photo['name_file']?>">скачать &raquo;</a></p>
                </div>
            <?php } ?>
        </div>

    </div>
</div>
<script>
    function toSort(style_sort) {
        let sort = document.getElementsByClassName('bysort');
        let sortByDateTime = sort[0];
        let sortByName = sort[1];

        if (style_sort === 'BySortDateTime') {
            sortByDateTime.style.display = 'flex';
            sortByName.style.display = 'none';
        } else if (style_sort === 'BySortName') {
            sortByName.style.display = 'flex';
            sortByDateTime.style.display = 'none';
        }
        console.log(style_sort);
    }
</script>
