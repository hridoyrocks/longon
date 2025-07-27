<?php
// app/Http/Controllers/CreditController.php
namespace App\Http\Controllers;

use App\Models\CreditPackage;
use App\Models\PaymentRequest;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CreditController extends Controller
{
    public function purchase()
    {
        $packages = CreditPackage::where('is_active', true)->get();
        $user = auth()->user();
        
        return view('credits.purchase', compact('packages', 'user'));
    }

    public function submitPayment(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:credit_packages,id',
            'payment_method' => 'required|in:bkash,nagad,rocket',
            'transaction_id' => 'required|string|max:255',
            'payment_screenshot' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $package = CreditPackage::findOrFail($request->package_id);
        
        $screenshotPath = $request->file('payment_screenshot')
            ->store('payment_screenshots', 'public');

        PaymentRequest::create([
            'user_id' => auth()->id(),
            'package_id' => $package->id,
            'amount' => $package->discounted_price,
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'payment_screenshot' => $screenshotPath,
            'status' => 'pending',
        ]);

        return redirect()->route('credits.purchase')
            ->with('success', 'পেমেন্ট রিকোয়েস্ট সফলভাবে জমা দেওয়া হয়েছে। ২৪ ঘন্টার মধ্যে অনুমোদিত হবে।');
    }

    public function history()
    {
        $transactions = auth()->user()->creditTransactions()
            ->latest()
            ->paginate(20);

        return view('credits.history', compact('transactions'));
    }

    public function paymentHistory()
    {
        $payments = auth()->user()->paymentRequests()
            ->latest()
            ->paginate(20);

        return view('credits.payment-history', compact('payments'));
    }
}