<?php
namespace Volante\SkyBukkit\Common\Tests\General\FlightController;

use Volante\SkyBukkit\Common\Src\General\FlightController\IncomingPIDTuningStatusMessage;
use Volante\SkyBukkit\Common\Src\General\FlightController\PIDTuningMessageFactory;
use Volante\SkyBukkit\Common\Src\General\FlightController\PIDTuningStatusCollection;
use Volante\SkyBukkit\Common\Src\General\FlightController\PIDTuningStatusMessageFactory;

/**
 * Class PIDTuningStatusMessageFactoryTest
 *
 * @package Volante\SkyBukkit\Common\Tests\General\FlightController
 */
class PIDTuningStatusMessageFactoryTest extends PIDTuningMessageFactoryTest
{
    /**
     * @return string
     */
    protected function getMessageType(): string
    {
        return PIDTuningStatusCollection::TYPE;
    }

    /**
     * @return PIDTuningMessageFactory
     */
    protected function createFactory(): PIDTuningMessageFactory
    {
        return new PIDTuningStatusMessageFactory();
    }

    /**
     * @return string
     */
    protected function getExpectedMessageClass(): string
    {
        return IncomingPIDTuningStatusMessage::class;
    }
}