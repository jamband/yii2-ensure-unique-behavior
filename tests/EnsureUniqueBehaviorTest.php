<?php

/*
 * This file is part of yii2-ensure-unique-behavior
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests;

use jamband\behaviors\EnsureUniqueBehavior;
use tests\behaviors\LooseEnsureUniqueBehavior;
use tests\models\Foo;
use Yii;
use yii\db\Connection;

class EnsureUniqueBehaviorTest extends \PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        Yii::$app->set('db', [
            'class' => Connection::class,
            'dsn' => 'sqlite::memory:',
        ]);
    }

    public function setUp()
    {
        parent::setUp();

        Yii::$app->db->createCommand()
            ->createTable('foo', ['id' => 'string primary key NOT NULL'])
            ->execute();

        Yii::$app->db->createCommand()
            ->truncateTable('foo')
            ->execute();
    }

    public function tearDown()
    {
        Yii::$app->db->close();
        parent::tearDown();
    }

    /**
     * @expectedException \yii\base\InvalidConfigException
     */
    public function testLengthException()
    {
        Foo::$behaviors['ensure-unique'] = [
            'class' => EnsureUniqueBehavior::class,
            'length' => 1,
        ];
        (new Foo())->save(false);
    }

    public function testLengthOne()
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

    /**
     * to skipped because takes time.
    public function testLengthTwo()
    {
        Foo::$behaviors['ensure-unique'] = [
            'class' => LooseEnsureUniqueBehavior::class,
            'length' => 2, // [A-Za-z0-9_-]{2}
        ];
        foreach (range(1, 4096) as $i) {
            (new Foo())->save(false);
        }
        $this->assertSame(4096, (int)Foo::find()->select('id')->distinct()->count());
    }
     */
}
