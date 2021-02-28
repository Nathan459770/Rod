<?php

namespace Nathan45;

use pocketmine\entity\Entity;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use Nathan45\rod\Hook;
use Nathan45\rod\rod;
use pocketmine\utils\Config;

/**
 * Class Main
 * @package Nathan45
 */
class Main extends PluginBase
{

    /**
     * @var Main
     */
    public $string;
    private static $main;
    private static $fishing = [];

    public function onEnable()
    {
        $this->getLogger()->info('' . PHP_EOL .
            'WEBSITE : https://github.com/Nathan459770' . PHP_EOL .
            'DISCORD : [ Nathan ]#6078' . PHP_EOL
        );
        if (!file_exists($this->getDataFolder() . "Config.yml")) {
            $this->saveResource("Config.yml");
            $config = new Config($this->getDataFolder() . "Config.yml", Config::YAML);
            $config->set('x', 0.4);
            $config->set('y', 0.4);
            $config->set('z', 0.4);
            $config->save();
        }
        ItemFactory::registerItem(new rod(), true);
        Entity::registerEntity(Hook::class, false, ["FishingHook", "minecraft:fishinghook"]);
    }

    public static function getInstance(): Main
    {
        return self::$main;
    }

    public function onLoad()
    {
        self::$main = $this;
    }

    public static function getFishingHook(Player $player): ?Hook{
        return self::$fishing[$player->getName()] ?? null;
    }

    public static function setFishingHook(?Hook $fish, Player $player){
        self::$fishing[$player->getName()] = $fish;
    }
}