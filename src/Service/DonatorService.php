<?php

namespace App\Service;

use App\Repository\DonatorRepository;

class DonatorService
{
    private DonatorRepository $donatorRepository;
    private EnvironmentService $environmentService;

    public function __construct(DonatorRepository $donatorRepository, EnvironmentService $environmentService)
    {
        $this->donatorRepository = $donatorRepository;
        $this->environmentService = $environmentService;
    }

    public function getDonatorListByEnvironmentId(int $environmentId)
    {
        $donators = $this->donatorRepository->getDonatorListByEnvironmentId($environmentId);

        return [
            'environment' => $this->environmentService->getCurrentEnvironment($environmentId),
            'donators' => $donators,
        ];
    }
}