<?php

use TelegramBot\Classes\TelegramAdmin;

require_once(__DIR__ . '/bootstrap.php');

// create a user
$entityManager = getEntityManager();
// $user = new User("Programster", "123","","","");
// $entityManager->persist($user);
// $entityManager->flush();
// $users = $entityManager->getRepository("User")->find(2);


// $admin = new TelegramAdmin("1234");
// $admin->setAdminFlag($user);
// echo "Created User with ID " . $user->getId() . PHP_EOL;

// List all users:
$user = $entityManager->getRepository("User")->find(1);
// print "Users: " . print_r($users) . PHP_EOL;

// $user->setIsAdmin("yes");
// $entityManager->flush();

// $user = $users[1];
// $admin = new TelegramAdmin("1234");
// $admin->setAdminFlag($user);
// // echo "Created User with ID " . $user->getId() . PHP_EOL;


$users = $entityManager->getRepository("User")->findAll();
print "Users: " . print_r($users, true) . PHP_EOL;