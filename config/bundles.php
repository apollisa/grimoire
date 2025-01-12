<?php

return [
    DAMA\DoctrineTestBundle\DAMADoctrineTestBundle::class => ["test" => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ["all" => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => [
        "test" => true,
    ],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => [
        "all" => true,
    ],
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ["all" => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ["all" => true],
    Symfony\UX\Icons\UXIconsBundle::class => ["all" => true],
    Symfony\UX\StimulusBundle\StimulusBundle::class => ["all" => true],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ["all" => true],
];
