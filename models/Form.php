<?php namespace app\models;

//use Dotenv\Dotenv;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\Inflector;

class Form extends Model
{
    public array $photos = [];

    public function rules()
    {
        return [
            [['photos'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 5],
//            ['translatorAndLowRegistr']
        ];
    }

    public function translatorAndLowRegistr(/*array $attributes*/)
    {
//        $dotenv = Dotenv::createImmutable(__DIR__.'/../', '.env');
//        $dotenv->load();

//        $transliterated_word = [];
//        foreach ($this->photos as $photo) {
//            $transliterated_word[] = Inflector::slug($photo->name, '-', true);
//        }

//        return json_encode([$this->photos[0]->name => $transliterated_word[0]], JSON_FORCE_OBJECT);
//        return json_encode($this->photos, JSON_FORCE_OBJECT);
//        return json_encode(['DIRNAME'=>__DIR__."\..\config", 'getenv'=>$_ENV["DB_HOST"]], JSON_FORCE_OBJECT);
    }

    public function upload()
    {
        if ($this->validate('')) {
            foreach ($this->photos as $photo) {
                if (!file_exists('../uploads')) {
                    mkdir('../uploads');
                }
                $photo->saveAs('../uploads/' . $photo->name);
            }
            return true;
        } else {
            return false;
        }
    }
}