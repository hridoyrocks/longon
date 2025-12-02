<?php
// app/Http/Controllers/ResellerController.php
namespace App\Http\Controllers;

use App\Models\ResellerApplication;
use App\Models\ResellerCustomer;
use App\Models\CommissionPayment;
use App\Models\CommissionTier;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResellerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        if (!$user->isReseller()) {
            return redirect()->route('reseller.apply');
        }

        $stats = [
            'total_customers' => $user->customers()->count(),
            'active_customers' => $user->customers()->where('is_active', true)->count(),
            'monthly_sales' => $user->getMonthlySales(),
            'commission_balance' => $user->commission_balance,
            'total_commission_earned' => $user->total_commission_earned,
        ];

        $recentCommissions = $user->commissionPayments()
            ->with('customer')
            ->latest()
            ->limit(10)
            ->get();

        $currentTier = $user->getCurrentCommissionTier();

        return view('reseller.dashboard', compact('stats', 'recentCommissions', 'currentTier'));
    }

    public function apply()
    {
        $user = auth()->user();
        
        if ($user->isReseller()) {
            return redirect()->route('reseller.dashboard');
        }

        $existingApplication = $user->resellerApplication;

        return view('reseller.apply', compact('existingApplication'));
    }

    public function submitApplication(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'business_address' => 'required|string',
            'nid_number' => 'required|string|max:20',
            'bank_account' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'bank_branch' => 'required|string|max:255',
            'mobile_banking_number' => 'required|string|max:20',
            'mobile_banking_type' => 'required|in:bkash,nagad,rocket',
            'experience_description' => 'required|string',
            'expected_monthly_sales' => 'required|numeric|min:0',
            'nid_front' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'nid_back' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'bank_statement' => 'required|file|mimes:pdf,jpeg,png,jpg|max:5120',
        ]);

        $user = auth()->user();

        // Check if application already exists
        if ($user->resellerApplication) {
            return redirect()->back()->with('error', 'আপনি ইতিমধ্যে আবেদন করেছেন।');
        }

        // Upload documents
        $documents = [];
        if ($request->hasFile('nid_front')) {
            $documents['nid_front'] = $request->file('nid_front')->store('reseller_documents', 'public');
        }
        if ($request->hasFile('nid_back')) {
            $documents['nid_back'] = $request->file('nid_back')->store('reseller_documents', 'public');
        }
        if ($request->hasFile('bank_statement')) {
            $documents['bank_statement'] = $request->file('bank_statement')->store('reseller_documents', 'public');
        }

        ResellerApplication::create([
            'user_id' => $user->id,
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'business_address' => $request->business_address,
            'nid_number' => $request->nid_number,
            'bank_account' => $request->bank_account,
            'bank_name' => $request->bank_name,
            'bank_branch' => $request->bank_branch,
            'mobile_banking_number' => $request->mobile_banking_number,
            'mobile_banking_type' => $request->mobile_banking_type,
            'experience_description' => $request->experience_description,
            'expected_monthly_sales' => $request->expected_monthly_sales,
            'documents' => $documents,
        ]);

        UserActivityLog::log($user->id, 'reseller_application', 'Submitted reseller application');

        return redirect()->route('reseller.apply')->with('success', 'আপনার আবেদন সফলভাবে জমা দেওয়া হয়েছে। ৭ দিনের মধ্যে অনুমোদিত হবে।');
    }

    public function customers()
    {
        $user = auth()->user();
        
        if (!$user->isApprovedReseller()) {
            return redirect()->route('reseller.apply');
        }

        $customers = $user->customers()
            ->with('customer')
            ->latest()
            ->paginate(20);

        return view('reseller.customers', compact('customers'));
    }

    public function commissions()
    {
        $user = auth()->user();
        
        if (!$user->isApprovedReseller()) {
            return redirect()->route('reseller.apply');
        }

        $commissions = $user->commissionPayments()
            ->with('customer', 'paymentRequest')
            ->latest()
            ->paginate(20);

        return view('reseller.commissions', compact('commissions'));
    }

    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|in:bkash,nagad,rocket,bank',
            'account_number' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        if (!$user->isApprovedReseller()) {
            return redirect()->back()->with('error', 'আপনি অনুমোদিত রিসেলার নন।');
        }

        if ($user->commission_balance < $request->amount) {
            return redirect()->back()->with('error', 'পর্যাপ্ত কমিশন ব্যালেন্স নেই।');
        }

        // Create payout request (you can create a separate table for this)
        UserActivityLog::log($user->id, 'payout_request', 'Requested payout of ৳' . $request->amount, [
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'account_number' => $request->account_number,
        ]);

        return redirect()->back()->with('success', 'পেআউট রিকোয়েস্ট সফলভাবে জমা দেওয়া হয়েছে।');
    }

    public function generateReferralLink()
    {
        $user = auth()->user();
        
        if (!$user->isApprovedReseller()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $referralLink = route('register', ['ref' => $user->referral_code]);
        
        return response()->json(['referral_link' => $referralLink]);
    }
}