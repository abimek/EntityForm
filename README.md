# EntityForm
**EntityForm is a PocketMine-MP plugin that allows plugins to create and managing Npc Forms!**

### Usage
You'll first need to import the `Scarce\EntityForm\EntityForm` and `Scarce\EntityForm\EntityFormHandler` class. Those two classes should be the only classes required to create and manage NpcForms.
```php
<?php
use Scarce\EntityForm\EntityForm;
use Scarce\EntityForm\EntityFormHandler;
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
You first need to make your own class that extends `Scarce\EntityForm\EntityForm`, in this Example, that class will be called `MyFormClass`!. Once you've done that, add the add your constructor by adding the magic function `__construct`. Make sure to not add any parameters to your constructor!\
Once youve made your constructor, add your parent constructor in the constructor method by adding `parent::__construct($title, false, false);\
The first parameter of the parent constructor should be the title of your form, this can always be changed latter on in the constructor.\
The second parameter of the parent constructor is wether the entity should be damageable, it is recommended that you keep it false because it was incorperated because entity forms dont have damage animations but still can be damaged\
The third and final parameter of the parent constructor is wether the form data like buttons should not be kept after server restart. This field is also recommended to be false because it was also built in to fix the bug that happed where form data gets destroyed on server restart!
```php
<?php

use Scarce\EntityForm\EntityForm;

class MyFormClass extends EntityForm{

}
```

when creating the class, make sure to add the magic function `__construct`. 
:
```php
public function __construc
```

To set the title of the form(and the NpcEntity) use:
```php
/** @var string $title */
$this->setTitle($title);
```

You can aslo set the content of the NpcForm by using
```php
/** @var string $content */
$form->setContent($title);
```
And most importantly, you can add buttons by using:
```php
/** @var string $name */
$form->addButton($name);
```
It is also possible to get the NPC Entity that corresponds with your Form by using:
```php
$form->getEntity();
```
To spawn the NPC to a player use:
```php
/** @Player $player */
$form->spawnTo(Player $player);
```
You can also spawn the entity to all players by using
```php
$form->spawnToAll();
```
Handiling NpcForm Callables
This will show an example of how to use the callable in the NpcForm class
```php
<?php
use Scarce\NpcForm\NpcForm;

public function sendNpcForm(Player $player){
        $form = new NpcForm(function(Player $player, ?int $data){
            //$data can be an integer, 0 is the first button, 1 is the second button etc...
            if ($data === null){
                return;
            }
            $player->sendMessage("$data");
        }, $player->asPosition(), $player->yaw, $player->pitch);

        $form->setTitle("Number Selector");
        $form->setContent( "Chose a Button!");
        $form->addButton("0");
        $form->addButton("1");
        $form->addButton("2");
        $form->addButton("3");
        $form->addButton("4");
        $form->addButton("5");
        //Sending The Form entity to only $player
        $form->spawnTo($player);
    }
```
To Do
Help with any of the todo list objectives is accepted

-Save Npc Data on Server Reset[]\
-Find a way to force the client to interact with an entity[]





