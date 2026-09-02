<?php

namespace App\Policies;

use App\Models\FeaturePrice;
use App\Models\User;

class FeaturePricePolicy
{
    /**
     * Only admins can view cost price data.
     * Selling price is visible to all authenticated users separately via the API.
     */
    public function viewCostPrice(User $user, FeaturePrice $featurePrice): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can create feature prices.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can update feature prices.
     */
    public function update(User $user, FeaturePrice $featurePrice): bool
    {
        return $user->isAdmin();
    }

    /**
     * Only admins can delete feature prices.
     */
    public function delete(User $user, FeaturePrice $featurePrice): bool
    {
        return $user->isAdmin();
    }
}
