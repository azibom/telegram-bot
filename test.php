<?php

require_once(__DIR__ . '/bootstrap.php');

// create a user
$entityManager = getEntityManager();
$user = new User("Programster", "programster@programster.org");
$entityManager->persist($user);
$entityManager->flush();

echo "Created User with ID " . $user->getId() . PHP_EOL;

// List all users:
$users = $entityManager->getRepository("User")->findAll();
print "Users: " . print_r($users, true) . PHP_EOL;
