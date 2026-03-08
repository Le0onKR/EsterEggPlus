<?php

namespace AidenKR\EsterEggPlus;

use AidenKR\IntellectUtils\IntellectItems;
use AidenKR\ServerCore\ServerCore;
use pocketmine\item\Item;
use pocketmine\player\Player;
use function AidenKR\ServerCore\convertName;

class EsterEgg implements \JsonSerializable
{
    public function __construct(
        protected readonly string $name,
        protected readonly string $locate,
        protected array $users,
        protected array $rewards
    ) {}

    public function jsonSerialize(): array
    {
        return [
            "name" => $this->name,
            "locate" => $this->locate,
            "users" => $this->users,
            "rewards" => $this->rewards
        ];
    }

    public static function jsonDeserialize(array $data): self
    {
        return new self($data["name"], $data["locate"], $data["users"], $data["rewards"]);
    }

    public function find(Player $player): void
    {
        if ($this->hasUsers($player)) {
            $player->sendMessage(ServerCore::getPrefix() . "이미 해당 이스터에그를 발견하셨습니다.");
            return;
        }

        if (count($this->rewards) > 1) {
            foreach ($this->rewards as $item) {
                $clone = IntellectItems::cloneLore(IntellectItems::deserialize($item));

                if ($player->getInventory()->canAddItem($clone)) {
                    $player->getInventory()->addItem($clone);
                    $player->sendMessage(ServerCore::getPrefix() . "이스터에그 §6{$this->name}§7을 발견하여 보상을 획득했습니다.");
                } else {
                    $player->sendMessage(ServerCore::getPrefix() . "인벤토리 공간이 부족합니다.");
                }
            }
        }
        $this->addUser($player);
        $player->sendMessage(ServerCore::getPrefix() . "§7이스터에그 §6{$this->name}§7를 발견했습니다.");
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLocate(): string
    {
        return $this->locate;
    }

    /**
     * @return Player[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @return Item[]
     */
    public function getRewards(): array
    {
        return $this->rewards;
    }

    public function hasUsers(Player $player): bool
    {
        return in_array(convertName($player), $this->users);
    }

    public function addUser(Player $player): void
    {
        $this->users[convertName($player)] = ["timestamp" => time()];
    }

    public function addReward(Item $item): void
    {
        $this->rewards[] = IntellectItems::serialize($item);
    }

    public function deleteReward(string $item): void
    {
        unset($this->rewards[$item]);
    }
}