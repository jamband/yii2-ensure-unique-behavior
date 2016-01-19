<?php

/*
 * This file is part of yii2-ensure-unique-behavior
 *
 * (c) Tomoki Morita <tmsongbooks215@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace tests\behaviors;

use yii\base\Security;
use jamband\behaviors\EnsureUniqueBehavior;

class LooseEnsureUniqueBehavior extends EnsureUniqueBehavior
{
    /**
     * @inheritdoc
     */
    protected function getValue($event)
    {
        $security = new Security();
        $value = $security->generateRandomString($this->length);
        while (!$this->isUnique($value)) {
            $value = $security->generateRandomString($this->length);
        }
        return $value;
    }
}
