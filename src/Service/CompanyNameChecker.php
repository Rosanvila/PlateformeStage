<?php

// src/Service/CompanyNameChecker.php
namespace App\Service;

use App\Repository\CompanyRepository;

class CompanyNameChecker
{
    private CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function doesCompanyNameExist(string $name): bool
    {
        return $this->companyRepository->findOneBy(['name' => $name]) !== null;
    }
}