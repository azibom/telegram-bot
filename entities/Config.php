<?php

/**
 * @Entity @Table(name="config")
 **/

class Config
{
    /** @Id @Column(type="string") **/
    protected $key;

    /** @Column(type="string", nullable=true) **/
    protected $value;

    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    # Accessors
    public function getKey() : int { return $this->key; }
    public function getValue() : string { return $this->value; }

    public function setKey(string $key) {$this->key = $key;}
    public function setValue(string $value) {$this->value = $value;}
}
