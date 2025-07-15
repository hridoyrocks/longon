<?php
// app/Http/Controllers/AdminController.php - Updated with new features
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PaymentRequest;
use App\Models\CreditPackage;
use App\Models\CreditTransaction;
use App\Models\FootballMatch;
use App\Models\ResellerApplication;
use App\Models\CommissionPayment;
use App\Models\OverlayTheme;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized access');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_resellers' => User::where('user_type', 'reseller')->count(),
            'pending_payments' => PaymentRequest::where('status', 'pending')->count(),
            'pending_applications' => 0, // ResellerApplication::where('status', 'pending')->count(),
            'total_matches' => FootballMatch::count(),
            'total_revenue' => PaymentRequest::where('status', 'approved')->sum('amount'),
            'monthly_revenue' => PaymentRequest::where('status', 'approved')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'pending_commissions' => 0, // CommissionPayment::where('status', 'pending')->sum('commission_amount'),
        ];

        $recentPayments = PaymentRequest::where('status', 'pending')
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        $recentApplications = []; // ResellerApplication::where('status', 'pending')->with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentPayments', 'recentApplications'));
    }

    public function users()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function payments()
    {
        $payments = PaymentRequest::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.payments', compact('payments'));
    }

    public function approvePayment(PaymentRequest $payment)
    {
        if ($payment->status !== 'pending') {
            return redirect()->back()->with('error', 'Payment already processed');
        }

        $user = $payment->user;
        $balanceBefore = $user->credits_balance;
        
        // Add credits based on amount (200 taka = 1 credit)
        $creditsToAdd = $payment->amount / 200;
        $user->addCredits($creditsToAdd);
        $user->increment('total_spent', $payment->amount);

        $payment->update([
            'status' => 'approved',
        ]);

        // Record transaction
        CreditTransaction::create([
            'user_id' => $user->id,
            'payment_request_id' => $payment->id,
            'credits_used' => $creditsToAdd,
            'transaction_type' => 'credit',
            'balance_before' => $balanceBefore,
            'balance_after' => $user->credits_balance,
            'description' => 'Payment approved - ৳' . $payment->amount,
        ]);

        return redirect()->back()->with('success', 'Payment approved successfully');
    }

    public function rejectPayment(Request $request, PaymentRequest $payment)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);

        $payment->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return redirect()->back()->with('success', 'Payment rejected');
    }

    public function adjustCredits(Request $request, User $user)
    {
        $request->validate([
            'credits' => 'required|numeric',
            'type' => 'required|in:add,subtract',
            'reason' => 'required|string|max:255',
        ]);

        $balanceBefore = $user->credits_balance;
        $credits = abs($request->credits);

        if ($request->type === 'add') {
            $user->addCredits($credits);
            $transactionType = 'credit';
        } else {
            $user->deductCredits($credits);
            $transactionType = 'debit';
        }

        CreditTransaction::create([
            'user_id' => $user->id,
            'credits_used' => $credits,
            'transaction_type' => $transactionType,
            'balance_before' => $balanceBefore,
            'balance_after' => $user->credits_balance,
            'description' => 'Admin adjustment: ' . $request->reason,
        ]);

        return redirect()->back()->with('success', 'Credits adjusted successfully');
    }

    public function packages()
    {
        $packages = [
            (object) ['id' => 1, 'name' => 'Starter', 'credits' => 5, 'price' => 1000, 'discount' => 0],
            (object) ['id' => 2, 'name' => 'Standard', 'credits' => 10, 'price' => 2000, 'discount' => 10],
            (object) ['id' => 3, 'name' => 'Premium', 'credits' => 20, 'price' => 4000, 'discount' => 15],
        ];
        
        return view('admin.packages', compact('packages'));
    }

    public function matches()
    {
        $matches = FootballMatch::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.matches', compact('matches'));
    }

    
    public function resellerApplications()
    {
        $applications = ResellerApplication::with('user')->latest()->paginate(20);
        return view('admin.reseller-applications', compact('applications'));
    }

    public function viewApplication(ResellerApplication $application)
    {
        return view('admin.view-application', compact('application'));
    }

    public function approveApplication(ResellerApplication $application)
    {
        if ($application->status !== 'pending') {
            return redirect()->back()->with('error', 'Application already processed');
        }

        $user = $application->user;
        
        $user->update([
            'user_type' => 'reseller',
            'reseller_approved_at' => now(),
            'business_name' => $application->business_name,
            'monthly_sales_target' => $application->expected_monthly_sales,
        ]);

        if (!$user->referral_code) {
            $user->generateReferralCode();
        }

        $application->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Reseller application approved successfully');
    }

    public function commissions()
    {
        $commissions = CommissionPayment::with('reseller', 'customer', 'paymentRequest')
            ->latest()
            ->paginate(20);

        return view('admin.commissions', compact('commissions'));
    }

    public function overlayThemes()
    {
        $themes = OverlayTheme::latest()->paginate(20);
        return view('admin.overlay-themes', compact('themes'));
    }

    public function storeTheme(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'text_color' => 'required|string|max:7',
            'background_color' => 'required|string|max:7',
            'font_family' => 'required|string|max:255',
            'custom_css' => 'nullable|string',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_premium' => 'boolean',
        ]);

        $cssVariables = [
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'text_color' => $request->text_color,
            'background_color' => $request->background_color,
            'font_family' => $request->font_family,
        ];

        $previewImage = null;
        if ($request->hasFile('preview_image')) {
            $previewImage = $request->file('preview_image')->store('theme_previews', 'public');
        }

        OverlayTheme::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'css_variables' => $cssVariables,
            'custom_css' => $request->custom_css,
            'preview_image' => $previewImage,
            'is_premium' => $request->boolean('is_premium'),
        ]);

        return redirect()->route('admin.themes')->with('success', 'Theme created successfully');
    }
 

    public function systemSettings()
    {
        return view('admin.system-settings');
    }

    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve_payments,reject_payments,send_notifications',
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        switch ($request->action) {
            case 'approve_payments':
                $this->bulkApprovePayments($request->ids);
                break;
            case 'reject_payments':
                $this->bulkRejectPayments($request->ids, $request->reason);
                break;
            case 'send_notifications':
                $this->bulkSendNotifications($request->ids, $request->message);
                break;
        }

        return redirect()->back()->with('success', 'Bulk action completed successfully');
    }

    private function bulkApprovePayments($ids)
    {
        $payments = PaymentRequest::whereIn('id', $ids)
            ->where('status', 'pending')
            ->get();

        foreach ($payments as $payment) {
            $this->approvePayment($payment);
        }
    }

    private function bulkRejectPayments($ids, $reason)
    {
        PaymentRequest::whereIn('id', $ids)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'admin_notes' => $reason,
            ]);
    }

    private function bulkSendNotifications($ids, $message)
    {
        $users = User::whereIn('id', $ids)->get();
        
        foreach ($users as $user) {
            // Send notification (implement your notification system)
             //UserActivityLog::log($user->id, 'admin_notification', $message);
        }
    }
}