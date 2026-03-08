<?php

namespace AidenKR\EsterEggPlus;

use AidenKR\EsterEggPlus\command\EsterEggCommand;
use AidenKR\EsterEggPlus\listener\EventListener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Filesystem;
use pocketmine\utils\SingletonTrait;

class EsterEggPlus extends PluginBase
{
    use SingletonTrait;

    /** @var EsterEgg[] */
    protected array $cache = [];

    protected function onLoad(): void
    {
        self::setInstance($this);
    }

    protected function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getServer()->getCommandMap()->register('AidenKR', new EsterEggCommand($this));

        $this->saveResource("cache.json");
        $data = json_decode(file_get_contents($this->getDataFolder() . "cache.json"), true);

        foreach ($data as $datum) {
            $class = EsterEgg::jsonDeserialize($datum);
            $this->cache[$class->getLocate()] = $class;
        }
    }

    protected function onDisable(): void
    {
        $arr = [];

        foreach ($this->cache as $locate => $class) {
            $arr[$locate] = $class->jsonSerialize();
        }
        Filesystem::safeFilePutContents($this->getDataFolder() . "cache.json", json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function getEsterEggs(): array
    {
        return $this->cache;
    }

    public function getEsterEgg(string $locate): ?EsterEgg
    {
        return $this->cache[$locate] ?? null;
    }

    public function registerEsterEgg(string $name, string $locate): void
    {
        $data = [
            "name" => $name,
            "locate" => $locate,
            "users" => [],
            "rewards" => []
        ];
        $this->cache[$locate] = EsterEgg::jsonDeserialize($data);
    }

    public function deleteEsterEgg(string $locate): void
    {
        unset($this->cache[$locate]);
    }
}