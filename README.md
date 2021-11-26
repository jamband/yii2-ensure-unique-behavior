# yii2-ensure-unique-behavior

[![Build Status](https://github.com/jamband/yii2-ensure-unique-behavior/workflows/tests/badge.svg)](https://github.com/jamband/yii2-ensure-unique-behavior/actions?workflow=tests) [![Latest Stable Version](https://img.shields.io/packagist/v/jamband/yii2-ensure-unique-behavior)](https://packagist.org/packages/jamband/yii2-ensure-unique-behavior) [![Total Downloads](https://img.shields.io/packagist/dt/jamband/yii2-ensure-unique-behavior)](https://packagist.org/packages/jamband/yii2-ensure-unique-behavior)

Insert unique identifier automatically for the Yii 2 framework.

## Requirements

* PHP 7.4 or later
* Yii 2.x

## Installation

```
composer require jamband/yii2-ensure-unique-behavior
```

## Examples

Creates a post table:

```sql
CREATE TABLE `post` (
    `id` CHAR(11) COLLATE utf8_bin NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `created_at` INT(11) NOT NULL,
    `updated_at` INT(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_unicode_ci;
```

Settings EnsureUniqueBehavior in Model:

```php
namespace app\models;

use jamband\behaviors\EnsureUniqueBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Post extends ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => EnsureUniqueBehavior::class,
                'attribute' => 'id', // default
                'length' => 11, // default
            ],
        ];
    }
}
```

And saves a new model:

```php
$model = new \app\models\Post();
$model->title = 'title';
$model->content = 'content';
$model->save();

// This value is eusure uniqueness
var_dump($model->id); // string(11) "-ZRLSS-4vl_"
```

## License

This extension is licensed under the MIT license.
