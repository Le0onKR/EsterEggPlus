<?php

namespace AidenKR\EsterEggPlus\form;

use AidenKR\EsterEggPlus\EsterEgg;
use AidenKR\EsterEggPlus\EsterEggPlus;
use AidenKR\EsterEggPlus\queue\EsterEggQueue;
use AidenKR\ServerCore\ServerCore;
use pocketmine\form\Form;
use pocketmine\player\Player;

class EsterEggCreateForm implements Form
{
    public function __construct(
        protected EsterEggPlus $plugin
    ) {}

    public function jsonSerialize(): array
    {
        return [
            "type" => "custom_form",
            "title" => "§l이스터에그 생성",
            "content" => [
                [
                    "type" => "input",
                    "text" => "§r§e- §f이스터에그 이름을 입력해주세요."
                ]
            ]
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if (trim($data[0] ?? "") === "") {
            return;
        }

        if ($this->plugin->getEsterEgg($data[0]) instanceof EsterEgg) {
            $player->sendMessage(ServerCore::getPrefix() . "데이터가 존재합니다.");
            return;
        }
        EsterEggQueue::$queue[$player->getName()] = ["mode" => "on", "name" => $data[0]];
        $player->sendMessage(ServerCore::getPrefix() . "설정할 블럭을 파괴해주세요.");
    }
}