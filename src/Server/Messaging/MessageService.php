<?php
namespace Volante\SkyBukkit\Common\Src\Server\Messaging;

use Volante\SkyBukkit\Common\Src\Server\Authentication\AuthenticationMessage;
use Volante\SkyBukkit\Common\Src\Server\Authentication\AuthenticationMessageFactory;
use Volante\SkyBukkit\Common\Src\Server\Network\Client;
use Volante\SkyBukkit\Common\Src\Server\Network\RawMessageFactory;
use Volante\SkyBukkit\Common\Src\Server\Role\IntroductionMessage;
use Volante\SkyBukkit\Common\Src\Server\Role\IntroductionMessageFactory;

/**
 * Class MessageService
 * @package Volante\SkyBukkit\Common\Src\Server
 */
class MessageService
{
    /**
     * @var RawMessageFactory
     */
    private $rawMessageFactory;

    /**
     * @var MessageFactory[]
     */
    private $factories = [];

    /**
     * MessageService constructor.
     * @param RawMessageFactory $rawMessageFactory
     * @param IntroductionMessageFactory $introductionMessageFactory
     * @param AuthenticationMessageFactory $authenticationMessageFactory
     */
    public function __construct(RawMessageFactory $rawMessageFactory = null, IntroductionMessageFactory $introductionMessageFactory = null, AuthenticationMessageFactory $authenticationMessageFactory = null)
    {
        $this->rawMessageFactory = $rawMessageFactory ?: new RawMessageFactory();
        $this->registerFactory($introductionMessageFactory ?: new IntroductionMessageFactory());
        $this->registerFactory($authenticationMessageFactory ?: new AuthenticationMessageFactory());
    }

    /**
     * @param Client $sender
     * @param string $message
     * @return IncomingMessage
     */
    public function handle(Client $sender, string $message) : IncomingMessage
    {
        $rawMessage = $this->rawMessageFactory->create($sender, $message);

        if (isset($this->factories[$rawMessage->getType()])) {
            return $this->factories[$rawMessage->getType()]->create($rawMessage);
        }

        throw new \InvalidArgumentException('Unable to handle message: given type <' . $rawMessage->getType() . '> is unknown');
    }

    /**
     * @param MessageFactory $factory
     */
    protected function registerFactory(MessageFactory $factory)
    {
        $this->factories[$factory->getType()] = $factory;
    }
}