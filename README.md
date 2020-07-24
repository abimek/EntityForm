# EntityForm
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

The second parameter of the parent constructor is wether the entity should be damageable, it is recommended that you keep it false because it was incorperated because entity forms dont have damage animations but still can be damaged\

The third and final parameter of the parent constructor is wether the form data like buttons should not be kept after server restart. This field is also recommended to be false because it was also built in to fix the bug that happed where form data gets destroyed on server restart!

```php
<?php

use pocketmine\utils\TextFormat;
use Scarce\EntityForm\EntityForm;

class MyFormClass extends EntityForm{
    
    public function __construct()
    {
        parent::__construct("Quests", false, false);
    }

}
```
**All these methods are used in the constructor**

You can set the content of the entity by adding:
```php
/** @var string $content */
$form->setContent($title);
```

You can add a button to you form by Adding the folling code in your constructor but make sure to add `use Scarce\EntityForm\Button;` to your use statements! The addButton method takes two parameters, a Button instance, and a callable which will be called when its clicked, if $callable is null, the button press can be detected in the onButtonInteract method:
```php
/** @var string $name */
/** @var ?callable $callable */
$form->addButton(new Button($buttonname), $callable);
```

**These methods are added outside the constructor**

It is also possible to get the Entity thats Linked with your Form by using:
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





