<?php
namespace Volante\SkyBukkit\RleayServer\Tests\Messaging;

use Volante\SkyBukkit\Common\Src\Server\Authentication\AuthenticationMessage;
use Volante\SkyBukkit\Common\Src\Server\Authentication\AuthenticationMessageFactory;
use Volante\SkyBukkit\Common\Src\Server\Messaging\MessageService;
use Volante\SkyBukkit\Common\Src\Server\Network\Client;
use Volante\SkyBukkit\Common\Src\Server\Messaging\IncomingMessage;
use Volante\SkyBukkit\Common\Src\Server\Network\RawMessage;
use Volante\SkyBukkit\Common\Src\Server\Network\RawMessageFactory;
use Volante\SkyBukkit\Common\Src\Server\Role\IntroductionMessage;
use Volante\SkyBukkit\Common\Src\Server\Role\IntroductionMessageFactory;
use Volante\SkyBukkit\Common\Tests\Server\General\DummyConnection;

/**
 * Class MessageServiceIntegrationTest
 * @package Volante\SkyBukkit\RleayServer\Tests
 */
class MessageServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MessageService
     */
    private $service;

    /**
     * @var RawMessageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $rawMessageFactory;

    /**
     * @var IntroductionMessageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $introductionMessageFactory;

    /**
     * @var AuthenticationMessageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $authenticationMessageFactory;

    /**
     * @var Client
     */
    private $sender;

    protected function setUp()
    {
        $this->sender = new Client(1, new DummyConnection(), -1);
        $this->rawMessageFactory = $this->getMockBuilder(RawMessageFactory::class)->disableOriginalConstructor()->getMock();
        $this->introductionMessageFactory = $this->getMockBuilder(IntroductionMessageFactory::class)->setMethods(['create'])->disableOriginalConstructor()->getMock();
        $this->authenticationMessageFactory = $this->getMockBuilder(AuthenticationMessageFactory::class)->setMethods(['create'])->disableOriginalConstructor()->getMock();

        $this->service = new MessageService($this->rawMessageFactory, $this->introductionMessageFactory, $this->authenticationMessageFactory);
    }

    public function test_handle_rawMessageServiceCalled()
    {
        $this->rawMessageFactory->expects(self::once())
            ->method('create')
            ->with($this->sender, 'correct')
            ->willReturn(new RawMessage($this->sender, IntroductionMessage::TYPE, 'test', []));
        $this->introductionMessageFactory->method('create')->willReturn(new IntroductionMessage($this->sender, 99));

        $this->service->handle($this->sender, 'correct');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unable to handle message: given type <invalidMessageType> is unknown
     */
    public function test_handle_invalidMessageType()
    {
        $this->rawMessageFactory->expects(self::once())
            ->method('create')
            ->with($this->sender, 'correct')
            ->willReturn(new RawMessage($this->sender, 'invalidMessageType', 'test', []));

        $this->service->handle($this->sender, 'correct');
    }

    public function test_handle_introductionMessageHandledCorrectly()
    {
        $rawMessage = new RawMessage($this->sender, IntroductionMessage::TYPE, 'test', []);
        $expected = new IntroductionMessage($this->sender, 99);

        $this->rawMessageFactory->expects(self::once())
            ->method('create')
            ->with($this->sender, 'correct')
            ->willReturn($rawMessage);
        $this->introductionMessageFactory->expects(self::once())->method('create')->willReturn($expected);

        $result = $this->service->handle($this->sender, 'correct');

        self::assertInstanceOf(IntroductionMessage::class, $result);
        self::assertSame($expected, $result);
    }

    public function test_handle_authenticationMessageHandledCorrectly()
    {
        $rawMessage = new RawMessage($this->sender, AuthenticationMessage::TYPE, 'test', []);
        $expected = new AuthenticationMessage($this->sender, 'correctToken');

        $this->rawMessageFactory->expects(self::once())
            ->method('create')
            ->with($this->sender, 'correct')
            ->willReturn($rawMessage);
        $this->authenticationMessageFactory->expects(self::once())->method('create')->willReturn($expected);

        $result = $this->service->handle($this->sender, 'correct');

        self::assertInstanceOf(AuthenticationMessage::class, $result);
        self::assertSame($expected, $result);
    }
}