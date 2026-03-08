<?php

namespace AidenKR\EsterEggPlus\form\manage;

use AidenKR\EsterEggPlus\EsterEgg;
use pocketmine\form\Form;
use pocketmine\player\Player;

class EsterEggFindUserForm implements Form
{
    public function __construct(
        protected EsterEgg $esteregg
    ) {}

    public function jsonSerialize(): array
    {
        $arr = [];

        foreach ($this->esteregg->getUsers() as $user) {
            $arr[] = ["text" => "§l{$user}"];
        }

        return [
            "type" => "form",
            "title" => "§l이스터에그 목록",
            "content" => "",
            "buttons" => $arr
        ];
    }

    public function handleResponse(Player $player, $data): void
    {}
}