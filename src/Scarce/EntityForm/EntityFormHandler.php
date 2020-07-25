<?php

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace Scarce\EntityForm;

use pocketmine\entity\Entity;
use pocketmine\plugin\Plugin;
use InvalidArgumentException;

class EntityFormHandler{

    public static $registrant = null;

    public static $linked_entities = [];


    public static function isRegistered(){
        if (self::$registrant instanceof Plugin){
            return true;
        }
        return false;
    }

    public static function register(Plugin $plugin){
        if (self::isRegistered()){
            throw new InvalidArgumentException("Tried Registering " . self::class . " twice!");
        }else{
            self::$registrant = $plugin;
            $plugin->getServer()->getPluginManager()->registerEvents(new EntityFormEventHandler(), $plugin);
        }
    }

    public static function registerLinkedEntity(Entity $entity, EntityForm $form): void{
        self::$linked_entities[$entity->getId()] = $form;
    }

    public static function getFormFromEntity(Entity $entity): ?EntityForm{
        $entityid = $entity->getId();
        if (isset(self::$linked_entities[$entityid])){
            return self::$linked_entities[$entityid];
        }
        return null;
    }


    public static function linkWithEntity(EntityForm $form, Entity $entity){
        $form->entity = $entity;
        $entity->namedtag->setString(EntityForm::CLASS_LOCATION_NBT, get_class($form));
        $entity->getDataPropertyManager()->setByte(Entity::DATA_HAS_NPC_COMPONENT, true);
        if (isset($form::$data)){
            $entity->setNameTag($form->getTitle());
            $entity->getDataPropertyManager()->setString(Entity::DATA_INTERACTIVE_TAG, $form->getContent());
        }
        $entity->getDataPropertyManager()->setString(Entity::DATA_NPC_ACTIONS, json_encode(array_map(function ($button){return $button->data;}, $form->buttons), JSON_UNESCAPED_UNICODE));
        self::registerLinkedEntity($entity, $form);
    }




}
