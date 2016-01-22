<?php

/*
 * This file is part of yii2-ensure-unique-behavior
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace jamband\behaviors;

use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yii\validators\UniqueValidator;

/**
 * EnsureUniqueBehavior class file.
 */
class EnsureUniqueBehavior extends AttributeBehavior
{
    const MIN_LENGTH = 8;

    /**
     * @var string the attribute name
     */
    public $attribute = 'id';

    /**
     * @var integer length
     */
    public $length = 11;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->attribute,
            ];
        }
    }

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    protected function getValue($event)
    {
        if (self::MIN_LENGTH > $this->length) {
            throw new InvalidConfigException(self::class.'::length must be at least '.self::MIN_LENGTH.' or more.');
        }
        $value = $this->generateRandomString();
        while (!$this->isUnique($value)) {
            $value = $this->generateRandomString();
        }
        return $value;
    }

    /**
     * Whether the unique id.
     * @param string $value
     * @return bool
     */
    protected function isUnique($value)
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = clone $this->owner;
        $model->clearErrors();
        $model->{$this->attribute} = $value;
        (new UniqueValidator())->validateAttribute($model, $this->attribute);

        return !$model->hasErrors();
    }

    /**
     * Generates a random string of specified length.
     * @return string
     */
    protected function generateRandomString()
    {
        $bytes = function_exists('random_bytes')
            ? random_bytes($this->length)
            : openssl_random_pseudo_bytes($this->length);

        return strtr(substr(base64_encode($bytes), 0, $this->length), '+/', '_-');
    }
}
