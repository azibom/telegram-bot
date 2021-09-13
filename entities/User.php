<?php

/**
 * @Entity @Table(name="user")
 **/

class User
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    protected $id;

    /** @Column(type="string") **/
    protected $name;

    /** @Column(type="string") **/
    protected $chatId;


    public function __construct(string $name, string $chatId)
    {
        $this->name = $name;
        $this->chatId = $chatId;
    }

    # Accessors
    public function getId() : int { return $this->id; }
    public function getName() : string { return $this->name; }
    public function getChatId() : string { return $this->chatId; }
}
