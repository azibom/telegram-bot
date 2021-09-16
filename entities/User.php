<?php

/**
 * @Entity @Table(name="user")
 **/

class User
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="string", nullable=true) **/
    protected $name;

    /** @Column(type="string", nullable=true) **/
    protected $telegramId;

    /** @Column(type="string", nullable=true) **/
    protected $isAdmin;

    /** @Column(type="string", unique=true) **/
    protected $chatId;

    /** @Column(type="string", nullable=true) **/
    protected $currentMenuName;

    public function __construct(string $name, string $chatId, string $currentMenuName, string $telegramId)
    {
        $this->name = $name;
        $this->chatId = $chatId;
        $this->currentMenuName = $currentMenuName;
        $this->telegramId = $telegramId;
    }

    # Accessors
    public function getId() : int { return $this->id; }
    public function getName() : string { return $this->name; }
    public function getChatId() : string { return $this->chatId; }
    public function getCurrentMenuName() : string { return $this->currentMenuName; }
    public function getTelegramId() : string { return $this->telegramId; }
    public function getIsAdmin() : string { return $this->isAdmin; }

    public function setName(string $name) {$this->name = $name;}
    public function setChatId(string $chatId) {$this->chatId = $chatId;}
    public function setCurrentMenuName(string $currentMenuName) {$this->currentMenuName = $currentMenuName;}
    public function setTelegramId(string $telegramId) {$this->telegramId = $telegramId;}
    public function setIsAdmin(string $isAdmin) {$this->isAdmin = $isAdmin;}
}
