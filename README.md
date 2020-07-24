# EntityForm
Credit to GiantQuartz for the EntityLinking Idea!\
**EntityForm is a PocketMine-MP plugin that allows plugins to create and managing Npc Forms!**

### Usage
You'll first need to import the `Scarce\EntityForm\EntityForm`, `Scarce\EntityForm\EntityFormHandler`, `Scarce\EntityForm\Button` and class. Those three classes should be the only classes required to create and manage NpcForms.
```php
<?php
use Scarce\EntityForm\EntityForm;
use Scarce\EntityForm\EntityFormHandler;
use Scarce\EntityForm\Button;
```
**NOTE:** \
-You should register the EntitycFormEventHandler so that callables work by using\
```php
<?php
use Scarce\EntityForm\EntityFormHandler;
/** @var Plugin $plugin */
if (!EntityFormHandler::isRegistered()){
    EntityFormHandler::register($plugin);
}
```
-For the player to be able to see the form, they have to right-click on the NPC Entity. \

**Creating an EntityForm**\
Creating a EntityForm is relatively simple.
You first need to make your own class that extends `Scarce\EntityForm\EntityForm`, in this Example, that class will be called `MyFormClass`! Once you've done that, add the add your constructor by adding the magic function `__construct`. Make sure to not add any parameters in your constructor!\
Once youve made your constructor, add your parent constructor inside your constructor method by adding `parent::__construct($title, false, false);`\

The first parameter of the parent constructor should be the title of your form, this can always be changed latter on in the constructor.

The second parameter of the parent constructor is whether the entity should be damageable, it is recommended that you keep it false since it was incorporated because entity forms dont have damage animations but still can be damaged

The third and final parameter of the parent constructor is whether the form data, like buttons, should not be kept after server restart. This field is also recommended to be false because it was also built in to fix the bug that happed where form data gets destroyed on server restart!

```php
<?php

use pocketmine\Player;
use pocketmine\utils\TextFormat;
use Scarce\EntityForm\EntityForm;
use Scarce\EntityForm\Button;
use Scarce\EntityForm\EntityFormHandler;

class MyFormClass extends EntityForm{

    public function __construct()
    {
        parent::__construct(TextFormat::RED . "ElementalsPE", //<--  Title
            false, //<-- Should the entity corresponding with this form be able to take damages?
            false);//<-- Should form data like buttons not be saved after restart
        
        $this->setContent(TextFormat::RED . "Welcome to ElementalsPe");//Used to set the content of the form Similar to FormAPI


        /**the addButton method has two parameters, first being a instance of a button, and second being a callables which will be called when its clicked,
        If the callable is null, the interaction can still be delt with in onButtonInteract()
        The callable in these method takes two parameters, $player, and $index*/

        $this->addButton(new Button(TextFormat::LIGHT_PURPLE . "How do I get started"), function(Player $player, int $index){
            $player->sendMessage(TextFormat::GOLD . "You clicked button $index");
            $this->getEntity()->setScale(2); //The getEntity method can be used to get the entity that is linked to this form

        });


        $this->addButton(new Button(TextFormat::LIGHT_PURPLE . "Server Information"));
    }

    /** @param Player $player */
    /**@param Button $button */
    /**@param Int $data */

    //onButtonInteract allows you to handle buttons which dont have callables associated with them
    public function onButtonInteract(Player $player, Button $button, int $data)
    {
        if ($data === 1) {
            $player->sendMessage(TextFormat::GREEN . "You Want to see the server information?");
        }
    }

    //OnOpen is called when a player right clicks the entity
    /** @param Player $player */
    public function onOpen($player){
        $player->sendMessage(TextFormat::AQUA . "The Form Just Opened!");
    }


    //On close is called when a player hits the X at the top right of the form to close it without interacting with any buttons
    /** @param Player $player */
    public function onClose($player){
        $player->sendMessage(TextFormat::AQUA . "You Closed The Form???");
    }

}
//You can then link your form with any entity by doing
/** @var Entity $entity */
EntityFormHandler::linkWithEntity(new MyFormClass(), $entity);
```

And if you want to get the form that an entity is linked with, do:


``/** @var Entity $entity */``
``EntityFormHandler::getFormFromEntity($entity);``\
returns null if the form doesnt have a linked form








