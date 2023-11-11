<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants\Target;

class TargetParent82
{
    public const PARENT_CONST = [
        'dir'               => __DIR__,
        'file'              => __FILE__,
        'function'          => __FUNCTION__,
        'class'             => __CLASS__,
        'trait'             => __TRAIT__,
        'method'            => __METHOD__,
        'namespace'         => __NAMESPACE__,
        'targetClassClass'  => TargetClass82::class,
        'targetTraitClass'  => TargetTrait82::class,
        'targetParentClass' => TargetParent82::class,
        'selfClass'         => self::class,
    ];

    public array $parentProperty = [
        'dir'               => __DIR__,
        'file'              => __FILE__,
        'function'          => __FUNCTION__,
        'class'             => __CLASS__,
        'trait'             => __TRAIT__,
        'method'            => __METHOD__,
        'namespace'         => __NAMESPACE__,
        'targetClassClass'  => TargetClass82::class,
        'targetTraitClass'  => TargetTrait82::class,
        'targetParentClass' => TargetParent82::class,
        'selfClass'         => self::class,
    ];

    public static array $parentStaticProperty = [
        'dir'               => __DIR__,
        'file'              => __FILE__,
        'function'          => __FUNCTION__,
        'class'             => __CLASS__,
        'trait'             => __TRAIT__,
        'method'            => __METHOD__,
        'namespace'         => __NAMESPACE__,
        'targetClassClass'  => TargetClass82::class,
        'targetTraitClass'  => TargetTrait82::class,
        'targetParentClass' => TargetParent82::class,
        'selfClass'         => self::class,
    ];

    public function parentMethod(): array
    {
        return [
            'dir'               => __DIR__,
            'file'              => __FILE__,
            'function'          => __FUNCTION__,
            'class'             => __CLASS__,
            'trait'             => __TRAIT__,
            'method'            => __METHOD__,
            'namespace'         => __NAMESPACE__,
            'targetClassClass'  => TargetClass82::class,
            'targetTraitClass'  => TargetTrait82::class,
            'targetParentClass' => TargetParent82::class,
            'selfClass'         => self::class,
            'staticClass'       => static::class,
        ];
    }

    public static function parentStaticMethod(): array
    {
        return [
            'dir'               => __DIR__,
            'file'              => __FILE__,
            'function'          => __FUNCTION__,
            'class'             => __CLASS__,
            'trait'             => __TRAIT__,
            'method'            => __METHOD__,
            'namespace'         => __NAMESPACE__,
            'targetClassClass'  => TargetClass82::class,
            'targetTraitClass'  => TargetTrait82::class,
            'targetParentClass' => TargetParent82::class,
            'selfClass'         => self::class,
            'staticClass'       => static::class,
        ];
    }
}
