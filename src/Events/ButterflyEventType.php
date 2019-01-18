<?php
/**
 * Created by Weiler.
 * User: Weiler
 * Email: weiler.china@gmail.com
 * Date: 2019/1/18
 */
namespace Weiler\Butterfly\Events;

use MyCLabs\Enum\Enum;

class ButterflyEventType extends Enum
{
    // init admin controllers event
    const InitAdminController = 'Weiler\Butterfly\Events\InitAdminController';
}