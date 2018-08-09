<?php


/*
 * This file is part of yii2-ensure-unique-behavior
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace tests\models;

use yii\db\ActiveRecord;

class Foo extends ActiveRecord
{
    public static $behaviors;

    public static function tableName(): string
    {
        return 'foo';
    }

    public function behaviors(): array
    {
        return static::$behaviors;
    }
}
