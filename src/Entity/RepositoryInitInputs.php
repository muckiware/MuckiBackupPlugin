<?php

namespace MuckiFacilityPlugin\Entity;

class RepositoryInitInputs
{

    protected bool $active;
    protected string $name;
    protected int $forgetDaily;
    protected int $forgetWeekly;
    protected int $forgetMonthly;
    protected int $forgetYearly;
    protected string $type;
    protected string $repositoryPath;
    protected string $repositoryPassword;
    protected string $restorePath;

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getForgetDaily(): int
    {
        return $this->forgetDaily;
    }

    public function setForgetDaily(int $forgetDaily): void
    {
        $this->forgetDaily = $forgetDaily;
    }

    public function getForgetWeekly(): int
    {
        return $this->forgetWeekly;
    }

    public function setForgetWeekly(int $forgetWeekly): void
    {
        $this->forgetWeekly = $forgetWeekly;
    }

    public function getForgetMonthly(): int
    {
        return $this->forgetMonthly;
    }

    public function setForgetMonthly(int $forgetMonthly): void
    {
        $this->forgetMonthly = $forgetMonthly;
    }

    public function getForgetYearly(): int
    {
        return $this->forgetYearly;
    }

    public function setForgetYearly(int $forgetYearly): void
    {
        $this->forgetYearly = $forgetYearly;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getRepositoryPath(): string
    {
        return $this->repositoryPath;
    }

    public function setRepositoryPath(string $repositoryPath): void
    {
        $this->repositoryPath = $repositoryPath;
    }

    public function getRepositoryPassword(): string
    {
        return $this->repositoryPassword;
    }

    public function setRepositoryPassword(string $repositoryPassword): void
    {
        $this->repositoryPassword = $repositoryPassword;
    }

    public function getRestorePath(): string
    {
        return $this->restorePath;
    }

    public function setRestorePath(string $restorePath): void
    {
        $this->restorePath = $restorePath;
    }
}
