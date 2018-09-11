<?php

return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class                => ['all' => true],
    Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle::class       => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class                 => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class     => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class                  => ['all' => true],
    Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle::class            => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class            => ['dev' => true, 'test' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class                          => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class                    => ['all' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class                        => ['dev' => true, 'test' => true],
    Symfony\Bundle\MakerBundle\MakerBundle::class                        => ['dev' => true],
    Symfony\Bundle\WebServerBundle\WebServerBundle::class                => ['dev' => true],
    Broadway\Bundle\BroadwayBundle\BroadwayBundle::class                 => ['all' => true],
    League\Tactician\Bundle\TacticianBundle::class                       => ['all' => true],
    JMS\SerializerBundle\JMSSerializerBundle::class                      => ['all' => true],
    Nelmio\CorsBundle\NelmioCorsBundle::class                            => ['all' => true],
    FOS\ElasticaBundle\FOSElasticaBundle::class                          => ['all' => true],
    FOS\OAuthServerBundle\FOSOAuthServerBundle::class                    => ['all' => true],
    FOS\UserBundle\FOSUserBundle::class                                  => ['all' => true],
];
