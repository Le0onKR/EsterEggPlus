<?php

namespace AidenKR\EsterEggPlus\form;

use AidenKR\EsterEggPlus\EsterEggPlus;
use AidenKR\ServerCore\ServerCore;
use pocketmine\form\Form;
use pocketmine\player\Player;

class EsterEggDeleteForm implements Form
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
            "title" => "§l이스터에그 제거",
            "content" => "",
            "buttons" => $arr
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        $estereggs = $this->plugin->getEsterEggs();

        if (!isset($estereggs[$data])) {
            return;
        }
        $this->plugin->deleteEsterEgg($estereggs[$data]->getLocate());
        $player->sendMessage(ServerCore::getPrefix() . "§a{$estereggs[$data]}§7를 제거했습니다.");
    }
}