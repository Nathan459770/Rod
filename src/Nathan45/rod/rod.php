<?php

namespace Nathan45\rod;

use pocketmine\item\Item;
use Nathan45\Main;
use pocketmine\entity\Entity;
use pocketmine\item\Durable;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AnimatePacket;
use pocketmine\Player;
use Nathan45\rod\Hook;

/**
 * Class rod
 * @package Nathan45\rod
 * @project rod
 */
class Rod extends Durable
{
    public function __construct($meta = 0)
    {
        parent::__construct(Item::FISHING_ROD, $meta, "Fishing Rod");
    }

    public function getMaxStackSize(): int
    {
        return 1;
    }

    public function getCooldownTicks(): int
    {
        return 5;
    }

    public function getMaxDurability(): int
    {
        return 355;
    }

    public function onClickAir(Player $player, Vector3 $directionVector): bool
    {
        if (!$player->hasItemCooldown($this)) {
            $player->resetItemCooldown($this);

            if (Main::getFishingHook($player) === null) {
                $motion = $player->getDirectionVector();
                $motion = $motion->multiply(0.5);
                Main::getInstance()->string = Entity::createBaseNBT($player->add(0, $player->getEyeHeight() - 1, 0), $motion);
                $hook = Entity::createEntity("FishingHook", $player->level, Main::getInstance()->string, $player);
                $hook->spawnToAll();
            } else {
                $hook = Main::getFishingHook($player);
                $hook->flagForDespawn();
                Main::setFishingHook(null, $player);
            }
            $player->broadcastEntityEvent(AnimatePacket::ACTION_SWING_ARM);
            return true;
        }
        return false;
    }

    public function getProjectileEntityType(): string
    {
        return "Hook";
    }

    public function getThrowForce(): float
    {
        return 0.9;
    }
}
