<?php

/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

namespace Scarce\EntityForm;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\NpcRequestPacket;
use pocketmine\Player;
use pocketmine\Server;

class EntityFormEventHandler implements Listener{

    public function __construct()
    {
    }

    public static $skin = null;

    private $npc;

    public function DataPacketReceive(DataPacketReceiveEvent $event){
        $pk = $event->getPacket();
        $player = $event->getPlayer();
        if ($pk instanceof NpcRequestPacket){
            if (($entity = Server::getInstance()->findEntity($pk->entityRuntimeId)) === null){
                return;
            }
            if (($form = EntityFormHandler::getFormFromEntity($entity)) === null){
                return;
            }
            switch ($pk->requestType){
                case NpcRequestPacket::REQUEST_EXECUTE_ACTION:
                    $this->npc[$player->getName()] = $pk->actionType;
                    break;
                case NpcRequestPacket::REQUEST_EXECUTE_CLOSING_COMMANDS:;
                    if (isset($this->npc[$player->getName()])){
                        $response = $this->npc[$player->getName()];
                        unset($this->npc[$player->getName()]);
                        $form->handleResponse($player, $response);
                        break;
                    }
            }
        }
        if ($pk instanceof InventoryTransactionPacket){
            $trdata = $pk->trData;
            if ($pk->transactionType === InventoryTransactionPacket::TYPE_USE_ITEM_ON_ENTITY){
                $entityid = $trdata->entityRuntimeId;
                if (($entity = Server::getInstance()->findEntity($entityid)) === null){
                    return;
                }
                if (($form = EntityFormHandler::getFormFromEntity($entity)) === null){
                    return;
                }
                if ($trdata->actionType === InventoryTransactionPacket::USE_ITEM_ON_ENTITY_ACTION_INTERACT){
                    $form->onOpen($player);
                }
            }
        }

    }


    public function onSpawn(EntitySpawnEvent $event){
        $entity = $event->getEntity();
        if ($entity instanceof Player){
            return;
        }
        if (!$entity->namedtag->hasTag(EntityForm::CLASS_LOCATION_NBT)){
            return;
        }
        $class = $entity->namedtag->getString(EntityForm::CLASS_LOCATION_NBT);
        if (is_a($class, EntityForm::class, true) && class_exists($class)){
            if ($class === EntityForm::class){
                return;
            }
            $form = new $class();
            if ($form->is_data_cleared_on_restart === true){
                return;
            }
            EntityFormHandler::linkWithEntity($form, $entity);
        }
    }



    public function onDamage(EntityDamageEvent $event){
        $entity = $event->getEntity();
        if (($form = EntityFormHandler::getFormFromEntity($entity)) === null){
            return;
        }
        if ($form->isEntityDamageable() === true){
            return;
        }
        $event->setCancelled(true);
    }
}
