<?php

namespace AidenKR\EsterEggPlus\form\manage;

use AidenKR\EsterEggPlus\EsterEgg;
use AidenKR\IntellectUtils\IntellectItems;
use AidenKR\ServerCore\ServerCore;
use pocketmine\form\Form;
use pocketmine\player\Player;

class EsterEggListDeleteForm implements Form
{
    public function __construct(
        protected EsterEgg $esteregg,
        protected string $type
    ) {}

    public function jsonSerialize(): array
    {
        $arr = [];

        foreach ($this->esteregg->getRewards() as $item) {
            $clone = IntellectItems::cloneLore($item);
            $arr[] = ["text" => "§l{$clone->getName()}"];
        }

        return [
            "type" => "form",
            "title" => $this->type === "delete" ? "§l이스터에그 보상제거" : "§l이스터에그 보상목록",
            "content" => "",
            "buttons" => $arr
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if (!isset($data)) return;

        if ($this->type === "delete") {
            $esteregg = array_keys($this->esteregg->getRewards());

            if (!isset($esteregg[$data])) {
                return;
            }
            $this->esteregg->deleteReward($esteregg[$data]);
            $player->sendMessage(ServerCore::getPrefix() . "보상을 제거했습니다.");
        }
    }
}