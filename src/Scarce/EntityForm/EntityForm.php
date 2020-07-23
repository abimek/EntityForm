<?php

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace Scarce\EntityForm;

use JsonSerializable;
use pocketmine\entity\Entity;
use pocketmine\Player;
use Scarce\EntityForm\Entries\Button;

class EntityForm implements JsonSerializable {

    public const CLASS_LOCATION_NBT = "EntityFormClassLocation";

    public static $data = [];
    public static $form_listeners = [];

    private static $entity = null;
    public static $buttons = [];

    public static $entity_damageable = false;
    public static $is_data_cleared_on_restart = false;


    public function __construct(string $title, bool $entity_damageable = false, bool $clear_data_on_restart = false)
    {
        self::$data["title"] = "";
        self::$data["content"] = "";
        self::$buttons = [];
        $this->setTitle($title);
        $this->setEntityDamageable($entity_damageable);
        self::$is_data_cleared_on_restart = $clear_data_on_restart;
    }


    public function setTitle(string $title): void {
        self::$data["title"] = $title;
    }

    public function getTitle(): string {
        return self::$data["title"];
    }

    public function setContent(string $content): void {
        self::$data["content"] = $content;
    }

    public function getContent(): string {
        return self::$data["content"] ?? "";
    }

    public function setEntityDamageable(bool $value): void {
        self::$entity_damageable = $value;
    }

    public function isEntityDamageable(): bool{
        return self::$entity_damageable;
    }

    public function getEntity(): ?Entity{
        return self::$entity;
    }


    public function addButton(Button $button, ?callable $callable = null){
        self::$buttons[] = $button;
        if ($callable === null){
            return;
        }
        self::$form_listeners[array_key_last(self::$buttons)] = $callable;
    }

    //Credits To Giant Quartz for this method!
    public static function linkWithEntity(Entity $entity){
         self::$entity = $entity;
    }

    final public function handleResponse(Player $player, ?int $data){
        if ($data === null){
            $this->onClose($player);
        }
        if (isset(self::$form_listeners[$data])){
            (self::$form_listeners[$data])($player, self::$buttons[$data], $data);
            return;
        }
        $this->onButtonInteract($player,self::$buttons[$data], $data);
    }

    //Should be used for buttons without callables
    public function onButtonInteract(Player $player, Button $button, int $data){
    }

    //Called When Form is Closed
    public function onClose(Player $player){
    }

    //Called When Form is Opened
    public function onOpen(Player $player){}


    final public function jsonSerialize()
    {
        return self::$data["buttons"];
    }
}
