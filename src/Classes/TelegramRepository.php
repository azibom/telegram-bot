<?php

namespace TelegramBot\Classes;

use Doctrine\ORM\EntityManager;
use User;

require_once(__DIR__ . '/../../bootstrap.php');

class TelegramRepository {
    private $entityManager;
    private $qb;

    public function __construct(EntityManager $entityManager = null)
    {
        if ($entityManager == null) {
            $this->entityManager = getEntityManager();
        } else {
            $this->entityManager = $entityManager;
        }

        $this->qb = $this->entityManager->createQueryBuilder();
    }

    public function getUserByChatId(int $id)
    {
        $this->qb->select('U')
            ->from('User', 'U')
            ->where('U.id = :id')
            ->setParameter(':id', $id);
            
        return $this->qb->getQuery()->getOneOrNullResult();
    }

    public function addNewUser($name, $chatId, $currentMenuName)
    {
        $user = new User($name, $chatId, $currentMenuName);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function updateUser(User $user, $name = null, $currentMenuName = null)
    {
        if ($name != null) {
            $user->setName($name);
        }

        if ($currentMenuName != null) {
            $user->setCurrentMenuName($currentMenuName);
        }
        $this->entityManager->flush();
        return $user;
    }

    public function getUser($name, $chatId = null, $currentMenuName = null)
    {
        $user = $this->getUserByChatId($chatId);
        if ($user == null) {
            $user = $this->addNewUser($name, $chatId, $currentMenuName);
        } else {
            $user = $this->updateUser($user, $name, $chatId, $currentMenuName);
        }

        return $user;
    }
}