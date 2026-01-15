<?php

namespace App\Modules\Policy\Domain\Entities;

use App\Shared\Domain\Enums\PolicyStatus;
use DomainException;

final class PolicyStatusEntity
{
    public function __construct(
        private PolicyStatus $status
    ) {}

    public function status(): PolicyStatus
    {
        return $this->status;
    }

    public function draft(): void
    {
        if ($this->status !== null) {
            throw new DomainException('Policy cannot be draft');
        }

        $this->status = PolicyStatus::DRAFT;
    }

    public function activate(): void
    {
        if (!in_array($this->status, [PolicyStatus::DRAFT, PolicyStatus::REINSTATED, PolicyStatus::RENEWED, PolicyStatus::ENDORSED, PolicyStatus::COVERAGE_UPDATED])) {
            throw new DomainException('Only draft, reinstated, renewed, endorsed or coverage updated policies can be activated');
        }

        $this->status = PolicyStatus::ACTIVE;
    }

    public function suspended(): void
    {
        if ($this->status !== PolicyStatus::DRAFT) {
            throw new DomainException('Only draft policies can be suspended');
        }

        $this->status = PolicyStatus::SUSPENDED;
    }

    public function reinstated(): void
    {
        if ($this->status !== PolicyStatus::SUSPENDED) {
            throw new DomainException('Only suspended policies can be reinstated');
        }

        $this->status = PolicyStatus::REINSTATED;
    }

    public function renewalInitiated(): void
    {
        if ($this->status !== PolicyStatus::ACTIVE) {
            throw new DomainException('Only active policies can initiate a renewal');
        }

        $this->status = PolicyStatus::RENEWAL_INITIATED;
    }

    public function renewed(): void
    {
        if ($this->status !== PolicyStatus::RENEWAL_INITIATED) {
            throw new DomainException('Only initiated renewal policies can be renewed');
        }

        $this->status = PolicyStatus::RENEWED;
    }

    public function nonRenewed(): void
    {
        if ($this->status !== PolicyStatus::RENEWAL_INITIATED) {
            throw new DomainException('Only initiated renewal policies can be non renewed');
        }

        $this->status = PolicyStatus::NON_RENEWED;
    }

    public function endorsed(): void
    {
        if ($this->status !== PolicyStatus::ACTIVE) {
            throw new DomainException('Only active policies can be endorsed');
        }

        $this->status = PolicyStatus::ENDORSED;
    }

    public function coverageUpdated(): void
    {
        if ($this->status !== PolicyStatus::ACTIVE) {
            throw new DomainException('Only active policies can be update the coverage');
        }

        $this->status = PolicyStatus::COVERAGE_UPDATED;
    }

    public function expired(): void
    {
        if (!in_array($this->status, [PolicyStatus::ACTIVE, PolicyStatus::SUSPENDED, PolicyStatus::NON_RENEWED, PolicyStatus::RENEWAL_INITIATED])) {
            throw new DomainException('Only active, suspended, non renewed or renewal initiated policies can be expired');
        }

        $this->status = PolicyStatus::EXPIRED;
    }

    public function cancelled(): void
    {
        if (!in_array($this->status, [PolicyStatus::DRAFT, PolicyStatus::ACTIVE, PolicyStatus::SUSPENDED])) {
            throw new DomainException('Only draft, active or suspended policies can be cancelled');
        }

        $this->status = PolicyStatus::CANCELLED;
    }
}
