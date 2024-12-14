<?php

return [
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ["all" => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => [
        "all" => true,
    ],
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ["all" => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ["all" => true],
    Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ["all" => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => [
        "dev" => true,
        "test" => true,
    ],
];
