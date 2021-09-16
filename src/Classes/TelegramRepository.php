<?php

namespace TelegramBot\Classes;

use Doctrine\ORM\EntityManager;
use User;
use Config;

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
    }

    public function getConfigByKey($key)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('C')
            ->from('Config', 'C')
            ->where('C.key = :key')
            ->setParameter(':key', $key);
            
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function addNewConfig($key, $value)
    {
        $config = new Config($key, $value);
        $this->entityManager->persist($config);
        $this->entityManager->flush();

        return $config;
    }

    public function updateConfig(Config $config)
    {
        $this->entityManager->flush();
        return $config;
    }

    public function getUserByChatId($chatId)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('U')
            ->from('User', 'U')
            ->where('U.chatId = :chatId')
            ->setParameter(':chatId', $chatId);
            
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function addNewUser($name, $chatId, $currentMenuName = "")
    {
        $user = new User($name, $chatId, $currentMenuName, "", "");
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function updateUser(User $user, $name = null, $currentMenuName = null, $isAdmin = null)
    {
        $user = $this->entityManager->getRepository("User")->find($user->getId());

        if ($name != null) {
            $user->setName($name);
        }

        if ($currentMenuName != null) {
            $user->setCurrentMenuName($currentMenuName);
        }

        if ($isAdmin != null) {
            $user->setIsAdmin($isAdmin);
        }

        $this->entityManager->flush();
        return $user;
    }
}