<?php
namespace Volante\SkyBukkit\Common\Src\General\FlightController;

/**
 * Class PIDTuningStatusMessage
 *
 * @package Volante\SkyBukkit\Common\Src\General\FlightController
 */
class PIDTuningStatusCollection extends PIDTuningCollection
{
    const TYPE = 'pidTuningStatus';

    /**
     * @var string
     */
    protected $type = self::TYPE;

    /**
     * @var string
     */
    protected $messageTitle = 'PID tuning status';
}