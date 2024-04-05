<?php namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Connection;

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
            [['name_photo', 'date', 'time'], 'string'],
            [['name_photo', 'date', 'time'], 'required', 'on' => self::SCENARIO_CREATE],
            ['name_photo', 'unique'],
            ['name_photo', 'validatorName'],
        ];
    }

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = ['name_photo', 'date', 'time'];

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

    private function uniqueName(string $name_photo): string
    {
        $res_name_photo = $name_photo;
        $notUnique = self::find()->where(['like', 'name_photo', $name_photo])->orderBy(['name_photo' => SORT_DESC])->one();

        if (!!$notUnique) {
            if (str_contains($notUnique->name_photo, self::SEPARATOR)) {
                $substrings = explode(self::SEPARATOR, $notUnique->name_photo);
                $integer = (int)$substrings[1] + 1;
                $res_name_photo = $substrings[0] . self::SEPARATOR . (string)$integer;
            } else {
                $res_name_photo = $name_photo . self::SEPARATOR . '1';
            }
        }
        return $res_name_photo;
    }

    public function getRows(): array
    {
        return self::find()->all();
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
}