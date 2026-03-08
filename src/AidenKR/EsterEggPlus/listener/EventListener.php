<?php

namespace AidenKR\EsterEggPlus\listener;

use AidenKR\EsterEggPlus\EsterEggPlus;
use AidenKR\IntellectUtils\IntellectPosition;
use AidenKR\ServerCore\ServerCore;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use AidenKR\EsterEggPlus\queue\EsterEggQueue;

class EventListener implements Listener
{
    public function __construct(
        protected EsterEggPlus $plugin
    ) {}

    public function onInter(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $position = IntellectPosition::strByPos($event->getBlock()->getPosition());

        $esteregg = $this->plugin->getEsterEgg($position);

        if ($esteregg !== null) {
            $esteregg->find($player);
        }
    }

    public function onBreak(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        if (isset(EsterEggQueue::$queue[$player->getName()])) {
            $event->cancel();

            if (EsterEggQueue::$queue[$player->getName()]["mode"] === "on") {
                $name = EsterEggQueue::$queue[$player->getName()]["name"];
                $this->plugin->registerEsterEgg($name, IntellectPosition::strByPos($block->getPosition()));
                $player->sendMessage(ServerCore::getPrefix() . "§a{$name}§7를 제작완료 했습니다.");
                unset(EsterEggQueue::$queue[$player->getName()]);
            }
        }
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        $player = $event->getPlayer();

        if (isset(EsterEggQueue::$queue[$player->getName()])) {
            unset(EsterEggQueue::$queue[$player->getName()]);
        }
    }
}