<?php

namespace AidenKR\EsterEggPlus\form\manage;

use AidenKR\EsterEggPlus\EsterEgg;
use AidenKR\IntellectUtils\IntellectItems;
use AidenKR\IntellectUtils\IntellectPosition;
use AidenKR\ServerCore\ServerCore;
use pocketmine\form\Form;
use pocketmine\player\Player;

class EsterEggManageForm implements Form
{
    public function __construct(
        protected EsterEgg $esteregg
    ) {}
    
    private function getContent(): string
    {
        $content = PHP_EOL;
        $content .= "§r§a- §f장소 : " . $this->esteregg->getLocate() . PHP_EOL;
        $content .= "§r§a- §f유저 : " . count($this->esteregg->getUsers()) . PHP_EOL;
        $content .= "§r§a- §f보상 갯수 : " . count($this->esteregg->getRewards());
        $content .= str_repeat(PHP_EOL, 2);
        return $content;
    }

    public function jsonSerialize(): array
    {
        return [
            "type" => "form",
            "title" => "§l{$this->esteregg->getName()}",
            "content" => $this->getContent(),
            "buttons" => [
                ["text" => "§l장소 이동\n§r§0- 이스터에그 설정한 장소로 이동합니다. -"],
                ["text" => "§l찾은 유저\n§r§0- 이스터에그를 찾은 유저의 목록을 확인합니다. -"],
                ["text" => "§l보상 등록\n§r§0- 이스터에그 보상을 등록합니다. -"],
                ["text" => "§l보상 목록\n§r§0- 이스터에그 보상목록을 확인합니다. -"],
                ["text" => "§l보상 제거\n§r§0- 이스터에그 보상을 제거합니다. -"],
            ]
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if (!isset($data)) return;

        switch ($data) {
            case 0:
                $locate = IntellectPosition::posByStr($this->esteregg->getLocate());
                $player->teleport($locate);
                break;

            case 1:
                $player->sendForm(new EsterEggFindUserForm($this->esteregg));
                break;

            case 2:
                $item = $player->getInventory()->getItemInHand();

                if ($item->isNull()) {
                    $player->sendMessage(ServerCore::getPrefix() . "공기는 등록불가능힙니다.");
                    return;
                }
                $this->esteregg->addReward($item);
                $player->sendMessage(ServerCore::getPrefix() . "등록을 완료했습니다.");
                break;

            case 3:
                $player->sendForm(new EsterEggListDeleteForm($this->esteregg, 'info'));
                break;

            case 4:
                $player->sendForm(new EsterEggListDeleteForm($this->esteregg, 'delete'));
                break;
        }
    }
}