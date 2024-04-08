<?php namespace app\models;

use DateTime;
use DateTimeZone;
use yii\base\Model;
use yii\helpers\Inflector;

class Form extends Model
{
    public array $photos = [];

    public function rules()
    {
        return [
            [['photos'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 5, 'maxSize' => 4096*4096],
        ];
    }

    public function upload(): bool
    {
        if ($this->validate('')) {

            foreach ($this->photos as $photo) {
                if (!file_exists('../web/uploads')) {
                    mkdir('../web/uploads');
                }
                $photo->saveAs('../web/uploads/' . $photo->name);

                $timezone = new DateTimeZone("Europe/Samara");
                try {
                    $tmpDateTime = new DateTime("now", $timezone);
                } catch (\Exception $e) {
                    $this->addError('exception', $e->getMessage());

                    return false;
                }

                $dataSetPhoto = [
                    'unique_name_photo' => $photo->name,
                    'name_file' => $photo->name,
                    'date' => $tmpDateTime->format('Y-m-d'),
                    'time' => $tmpDateTime->format('H:i:s'),
                ];

                $modelPhoto = new Photos();
                if ($modelPhoto->load($dataSetPhoto, '')) {
                    $result[] = $modelPhoto->create();
                    if ($modelPhoto->hasErrors()) {
                        $this->addError('unique_name_photo', $modelPhoto->getErrors());

                        return false;
                    }
                }
            }
            return isset($result) && gettype($result) === 'array';
        } else {
            return false;
        }
    }

// ONLY FOR DEBUGGING
//    public function check(): array
//    {
////        $model = new Photos();
//        $result = [];
//        foreach ($this->photos as $photo) {
//            $result['проверка1'] = substr($photo->name, 0, -4);
//            $result['проверка2'] = $photo->name;
//
//        }
//        return $result;
//    }
}