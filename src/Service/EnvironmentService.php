<?php

namespace App\Service;

use App\Entity\Environment;

class EnvironmentService
{
    public function getCurrentEnvironment(int $environmentId): Environment
    {
        $environment = new Environment();
        $environments = $environment->getEnvironments();

        foreach ($environments as $environment) {
            if ($environment->getId() === $environmentId) {
                $currentEnvironment = $environment;
            }
        }

        return $currentEnvironment;
    }
}