<?php
declare(strict_types=1);

namespace App\Model\User\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use function Webmozart\Assert\Tests\StaticAnalysis\string;

class RoleType extends StringType
{
    public const NAME = 'user_user_role';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof ArrayCollection && $value->last() instanceof Role) {
            $value = $value->last()->getName();
        }
        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Role
    {
        return !empty($value) ? new Role($value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}