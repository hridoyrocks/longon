<?php
// app/Services/CommissionService.php
namespace App\Services;

use App\Models\User;
use App\Models\PaymentRequest;
use App\Models\CommissionPayment;
use App\Models\ResellerCustomer;
use App\Models\CommissionTier;

class CommissionService
{
    public function calculateCommission(PaymentRequest $payment)
    {
        $customer = $payment->user;
        $referrer = $customer->referrer;

        if (!$referrer || !$referrer->isApprovedReseller()) {
            return;
        }

        $this->processLevel1Commission($payment, $referrer);
        $this->processLevel2Commission($payment, $referrer);
        $this->updateResellerCustomer($customer, $referrer, $payment);
    }

    private function processLevel1Commission(PaymentRequest $payment, User $reseller)
    {
        $tier = $reseller->getCurrentCommissionTier();
        
        if (!$tier) {
            return;
        }

        $commissionAmount = ($payment->amount * $tier->level_1_commission) / 100;

        CommissionPayment::create([
            'reseller_id' => $reseller->id,
            'customer_id' => $payment->user_id,
            'payment_request_id' => $payment->id,
            'purchase_amount' => $payment->amount,
            'commission_amount' => $commissionAmount,
            'commission_percentage' => $tier->level_1_commission,
            'commission_level' => 1,
            'status' => 'pending',
        ]);

        $reseller->addCommission($commissionAmount);
    }

    private function processLevel2Commission(PaymentRequest $payment, User $level1Reseller)
    {
        $level2Reseller = $level1Reseller->referrer;

        if (!$level2Reseller || !$level2Reseller->isApprovedReseller()) {
            return;
        }

        $tier = $level2Reseller->getCurrentCommissionTier();
        
        if (!$tier) {
            return;
        }

        $commissionAmount = ($payment->amount * $tier->level_2_commission) / 100;

        CommissionPayment::create([
            'reseller_id' => $level2Reseller->id,
            'customer_id' => $payment->user_id,
            'payment_request_id' => $payment->id,
            'purchase_amount' => $payment->amount,
            'commission_amount' => $commissionAmount,
            'commission_percentage' => $tier->level_2_commission,
            'commission_level' => 2,
            'status' => 'pending',
        ]);

        $level2Reseller->addCommission($commissionAmount);
    }

    private function updateResellerCustomer(User $customer, User $reseller, PaymentRequest $payment)
    {
        $resellerCustomer = ResellerCustomer::updateOrCreate(
            [
                'reseller_id' => $reseller->id,
                'customer_id' => $customer->id,
            ],
            [
                'referrer_id' => $reseller->referrer_id,
                'last_purchase_at' => now(),
            ]
        );

        $resellerCustomer->increment('total_purchases', $payment->amount);
        
        if (!$resellerCustomer->first_purchase_at) {
            $resellerCustomer->update(['first_purchase_at' => now()]);
        }
    }
}