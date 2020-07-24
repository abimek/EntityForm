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

class EntityForm implements JsonSerializable {

    //Used to store class location in nbt tag
    public const CLASS_LOCATION_NBT = "EntityFormClassLocation";

    private $data = [];
    private $form_listeners = [];

    public $entity = null;
    public $buttons = [];

    private $entity_damageable = false;
    public $is_data_cleared_on_restart = false;


    public function __construct(string $title, bool $entity_damageable = false, bool $clear_data_on_restart = false)
    {
        $this->data["title"] = "";
        $this->data["content"] = "";
        $this->buttons = [];
        $this->setTitle($title);
        $this->setEntityDamageable($entity_damageable);
        $this->is_data_cleared_on_restart = $clear_data_on_restart;
    }


    //Sets the form title
    final public function setTitle(string $title): void {
        $this->data["title"] = $title;
    }

    //Returns the form title
    final public function getTitle(): string {
        return $this->data["title"];
    }

    //Sets the form content
    final public function setContent(string $content): void {
        $this->data["content"] = $content;
    }

    //Returns the form content
    final public function getContent(): string {
        return $this->data["content"] ?? "";
    }

    //Sets wether the entity can be damaged or not
    final public function setEntityDamageable(bool $value): void {
        $this->entity_damageable = $value;
    }

    //returns wether the entity can be damaged or not
    final public function isEntityDamageable(): bool{
        return $this->entity_damageable;
    }

    //returns the entity that this EntityForm is linked with
    final public function getEntity(): ?Entity{
        return $this->entity;
    }


    //Adds a button to this entity form
    final public function addButton(Button $button, ?callable $callable = null){
        $this->buttons[] = $button;
        if ($callable === null){
            return;
        }
       $this->form_listeners[array_key_last($this->buttons)] = $callable;
    }

    //Handles response recieved from player
    final public function handleResponse(Player $player, ?int $data){
        if ($data === null){
            $this->onClose($player);
        }
        if (isset($this->form_listeners[$data])){
            ($this->form_listeners[$data])($player, $data);
            return;
        }
        if (isset($this->buttons[$data])){
            $this->onButtonInteract($player,$this->buttons[$data], $data);
        }
    }


    //Should be used for buttons without callables
    public function onButtonInteract(Player $player, Button $button, int $data){
    }

    //Called When Form is Closed
    public function onClose(Player $player){
    }

    //Called When Form is Opend
    public function onOpen(Player $player){

    }

    //returns form data
    final public function jsonSerialize()
    {
        return array_map(function ($button){return $button->data;}, $this->buttons);
    }
}
