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

namespace tests\behaviors;

use jamband\behaviors\EnsureUniqueBehavior;
use yii\base\Event;

class LooseEnsureUniqueBehavior extends EnsureUniqueBehavior
{
    /**
     * @param Event $event
     * @return mixed
     */
    protected function getValue($event)
    {
        $value = $this->generateRandomString();

        while (!$this->isUnique($value)) {
            $value = $this->generateRandomString();
        }

        return $value;
    }
}
