<?php

namespace AidenKR\EsterEggPlus\form;

use AidenKR\EsterEggPlus\EsterEggPlus;
use AidenKR\EsterEggPlus\form\manage\EsterEggManageForm;
use AidenKR\ServerCore\ServerCore;
use pocketmine\form\Form;
use pocketmine\player\Player;

class EsterEggManageMainForm implements Form
{
    public function __construct(
        protected EsterEggPlus $plugin
    ) {}

    public function jsonSerialize(): array
    {
        $arr = [];

        foreach ($this->plugin->getEsterEggs() as $data) {
            $arr[] = ["text" => "§l{$data->getName()}\n§r§0- {$data->getLocate()} -"];
        }

        return [
            "type" => "form",
            "title" => "§l이스터에그 관리",
            "content" => "",
            "buttons" => $arr
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        $estereggs = array_keys($this->plugin->getEsterEggs());

        if (!isset($estereggs[$data])) {
            return;
        }
        $esteregg = $this->plugin->getEsterEgg($estereggs[$data]);
        $player->sendForm(new EsterEggManageForm($esteregg));
    }
}