<?php namespace app\models;

use app\controllers\SiteController;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\web\Controller;

class Photos extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    const SEPARATOR = '____';

    /**
     * @return Connection
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDb(): Connection
    {
        return Yii::$app->get('db');
    }

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'photos';
    }

    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'edited_at',
            ]
        ];
    }

    public function rules(): array
    {
        return [
            [['unique_name_photo', 'name_file', 'date', 'time'], 'string'],
            [['unique_name_photo', 'name_file', 'date', 'time'], 'required', 'on' => self::SCENARIO_CREATE],
            ['unique_name_photo', 'unique'],
            ['unique_name_photo', 'validatorName'],
        ];
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['unique_name_photo', 'name_file', 'date', 'time'];

        return $scenarios;
    }

    public function validatorName(string $attribute): void
    {
        $encoding = mb_detect_encoding($this->$attribute);
        $tmp_attribute = mb_strtolower($this->$attribute, $encoding);
        $tmp_attribute = substr($tmp_attribute, 0,-4);

        $tmp_attribute = transliterator_transliterate('Any-Latin', $tmp_attribute);

        $this->$attribute = $this->uniqueName($tmp_attribute);
    }

    private function uniqueName(string $unique_name_photo): string
    {
        $res_name = $unique_name_photo;
        $notUnique = self::find()->where(['like', 'unique_name_photo', $unique_name_photo])->orderBy(['unique_name_photo' => SORT_DESC])->one();

        if (!!$notUnique) {
            if (str_contains($notUnique->unique_name_photo, self::SEPARATOR)) {
                $substrings = explode(self::SEPARATOR, $notUnique->unique_name_photo);
                $integer = (int)$substrings[1] + 1;
                $res_name = $substrings[0] . self::SEPARATOR . (string)$integer;
            } else {
                $res_name = $unique_name_photo . self::SEPARATOR . '1';
            }
        }
        return $res_name;
    }

    public function create(): bool
    {
        self::setScenario('create');

        if (!$this->save() || $this->hasErrors()) {
            $this->addError('', "фото не сохранено");

            return false;
        }
        return true;
    }

    public function getPhotosBySortName(): array
    {
        return self::find()->orderBy(['unique_name_photo' => SORT_ASC])->asArray()->all();
    }

    public function getPhotosBySortDateTime(): array
    {
        return self::find()->asArray()->all();
    }

    public function getPhotosById(int $id): ActiveRecord
    {
        return self::find()->where(['id' => $id])->one();
    }

    public function getGroupedPhoto(): array
    {
        $photos = self::find()->select('name_file')->groupBy('name_file')->asArray()->all();

        return $photos;
    }
}