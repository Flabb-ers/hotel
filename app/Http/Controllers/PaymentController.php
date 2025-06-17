<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function createPayment(Request $request, $transaksiId)
    {
        try {
            $transaksi = Transaksi::with(['tamu', 'kamar.tipeKamar'])->findOrFail($transaksiId);

            if ($transaksi->is_paid !== 'pending') {
                return redirect()->back()->with('error', 'Transaksi sudah diproses.');
            }

            $transactionData = $this->midtransService->buildTransactionData(
                $transaksi,
                $transaksi->tamu,
                $transaksi->kamar
            );

            $result = $this->midtransService->createTransaction($transactionData);

            if ($result['success']) {
                // Update transaksi dengan order_id
                $transaksi->update([
                    'midtrans_order_id' => $transactionData['transaction_details']['order_id']
                ]);

                return view('payment.checkout', [
                    'snapToken' => $result['snap_token'],
                    'transaksi' => $transaksi,
                    'clientKey' => config('midtrans.client_key'),
                    'demoMode' => $result['demo_mode'] ?? false
                ]);
            } else {
                return redirect()->back()->with('error', 'Gagal membuat pembayaran: ' . $result['message']);
            }
        } catch (\Exception $e) {
            Log::error('Payment creation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem.');
        }
    }

    public function handleNotification(Request $request)
    {
        try {
            // Pass request data to Midtrans service
            $result = $this->midtransService->handleNotification();

            // Find transaksi by order_id
            $transaksi = Transaksi::where('midtrans_order_id', $result['order_id'])->first();

            if ($transaksi) {
                $transaksi->update([
                    'is_paid' => $result['status'],
                    'midtrans_transaction_id' => $request->input('transaction_id'),
                    'midtrans_response' => json_encode($request->all())
                ]);

                // Update room status if payment is successful
                if ($result['status'] === 'paid') {
                    $transaksi->kamar->update(['status' => 'terisi']);
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Notification handling error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    public function paymentFinish(Request $request)
    {
        $orderId = $request->get('order_id');
        $transaksi = Transaksi::where('midtrans_order_id', $orderId)->first();

        // If this is demo mode, simulate successful payment
        if ($transaksi && str_starts_with($transaksi->midtrans_order_id, 'HOTEL-')) {
            $transaksi->update([
                'is_paid' => 'paid',
                'midtrans_transaction_id' => 'DEMO-' . time(),
                'midtrans_response' => json_encode(['demo_mode' => true, 'status' => 'success'])
            ]);

            // Update room status
            $transaksi->kamar->update(['status' => 'terisi']);
        }

        return view('payment.finish', compact('transaksi'));
    }

    public function paymentUnfinish(Request $request)
    {
        return view('payment.unfinish');
    }

    public function paymentError(Request $request)
    {
        return view('payment.error');
    }
}
