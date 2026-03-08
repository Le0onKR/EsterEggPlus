<?php

namespace AidenKR\EsterEggPlus\form;

use AidenKR\EsterEggPlus\EsterEggPlus;
use pocketmine\form\Form;
use pocketmine\player\Player;

class EsterEggMainForm implements Form
{
    public function __construct(
        protected EsterEggPlus $plugin
    ) {}

    public function jsonSerialize(): array
    {
        return [
            "type" => "form",
            "title" => "§l이스터에그 관리",
            "content" => "",
            "buttons" => [
                ["text" => "§l이스터에그 생성\n§r§0- 이스터에그 데이터를 생성합니다. -"],
                ["text" => "§l이스터에그 제거\n§r§0- 이스터에그 데이터를 제거합니다. -"],
                ["text" => "§l이스터에그 관리\n§r§0- 이스터에그 데이터를 관리합니다. -"],
                ["text" => "§l창 닫기\n§r§0- UI를 종료합니다. -"]
            ]
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if (!isset($data)) return;

        $player->sendForm(match ($data) {
            0 => new EsterEggCreateForm($this->plugin),
            1 => new EsterEggDeleteForm($this->plugin),
            2 => new EsterEggManageMainForm($this->plugin)
        });
    }
}