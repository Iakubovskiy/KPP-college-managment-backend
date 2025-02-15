<?php
namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessListener
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->logger->error('=== AuthenticationSuccessListener constructed ==='); // Змінив на error для кращої видимості в логах
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $this->logger->error('=== onAuthenticationSuccessResponse started ===');

        $data = $event->getData();
        $this->logger->error('Initial data: ' . json_encode($data));

        $user = $event->getUser();
        $this->logger->error('User class: ' . get_class($user));

        if (!$user instanceof UserInterface) {
            $this->logger->error('User is not an instance of UserInterface');
            return;
        }

        $userId = $user->getId();
        $this->logger->error('User ID: ' . $userId);

        $data['data'] = array(
            'id' => $userId,
        );

        $this->logger->error('Modified data: ' . json_encode($data));

        $event->setData($data);
        $this->logger->error('=== onAuthenticationSuccessResponse finished ===');
    }
}
