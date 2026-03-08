<?php

namespace AidenKR\EsterEggPlus\command;

use AidenKR\EsterEggPlus\EsterEggPlus;
use AidenKR\EsterEggPlus\form\EsterEggMainForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;

class EsterEggCommand extends Command
{
    public function __construct(protected EsterEggPlus $plugin)
    {
        parent::__construct("이스터에그관리", "이스터에그관리 명령어 입니다.");
        $this->setPermission(DefaultPermissions::ROOT_OPERATOR);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if ($sender instanceof Player) {
            $sender->sendForm(new EsterEggMainForm($this->plugin));
        }
    }
}