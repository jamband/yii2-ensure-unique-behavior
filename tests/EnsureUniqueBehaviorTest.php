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

namespace tests;

use jamband\behaviors\EnsureUniqueBehavior;
use PHPUnit\Framework\TestCase;
use tests\behaviors\LooseEnsureUniqueBehavior;
use tests\models\Foo;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Connection;

class EnsureUniqueBehaviorTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        Yii::$app->set('db', [
            'class' => Connection::class,
            'dsn' => 'sqlite::memory:',
        ]);
    }

    public function setUp(): void
    {
        Yii::$app->db->createCommand()
            ->createTable('foo', ['id' => 'string primary key NOT NULL'])
            ->execute();
    }

    public function tearDown(): void
    {
        Yii::$app->db->close();
    }

    public function testLengthException(): void
    {
        $this->expectException(InvalidConfigException::class);

        Foo::$behaviors['ensure-unique'] = [
            'class' => EnsureUniqueBehavior::class,
            'length' => 1,
        ];

        (new Foo())->save(false);
    }

    public function testLengthOne(): void
    {
        Foo::$behaviors['ensure-unique'] = [
            'class' => LooseEnsureUniqueBehavior::class,
            'length' => 1, // [A-Za-z0-9_-]{1}
        ];

        foreach (range(1, 64) as $i) {
            (new Foo())->save(false);
        }

        $this->assertSame(64, (int)Foo::find()->select('id')->distinct()->count());
    }

    public function testLengthTwo(): void
    {
        Foo::$behaviors['ensure-unique'] = [
            'class' => LooseEnsureUniqueBehavior::class,
            'length' => 2, // [A-Za-z0-9_-]{2}
        ];

        foreach (range(1, 64 ** 2) as $i) {
            (new Foo())->save(false);
        }

        $this->assertSame(64 ** 2, (int)Foo::find()->select('id')->distinct()->count());
    }
}
