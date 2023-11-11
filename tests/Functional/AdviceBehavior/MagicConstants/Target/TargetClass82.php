<?php

namespace Okapi\Aop\Tests\Functional\AdviceBehavior\MagicConstants\Target;

class TargetClass82 extends TargetParent82
{
    use TargetTrait82;

    public const CONST = [
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

    public array $property = [
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

    public static array $staticProperty = [
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

    public function method(): array
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

    public static function staticMethod(): array
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
