<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        // Set your Merchant Server Key
        Config::$serverKey = config('midtrans.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = config('midtrans.is_production', false);
        // Set sanitization on (default)
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        // Set 3DS transaction for credit card to true
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    public function createTransaction($orderData)
    {
        try {
            // Check if we have valid Midtrans keys
            if (!$this->hasValidKeys()) {
                return $this->createDemoTransaction($orderData);
            }

            $snapToken = Snap::getSnapToken($orderData);
            return [
                'success' => true,
                'snap_token' => $snapToken,
                'redirect_url' => null
            ];
        } catch (\Exception $e) {
            // If Midtrans fails, fallback to demo mode
            if (strpos($e->getMessage(), 'unauthorized') !== false ||
                strpos($e->getMessage(), '401') !== false) {
                return $this->createDemoTransaction($orderData);
            }

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function hasValidKeys()
    {
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');

        return $serverKey &&
               $clientKey &&
               $serverKey !== 'SB-Mid-server-YOUR_SERVER_KEY' &&
               $clientKey !== 'SB-Mid-client-YOUR_CLIENT_KEY' &&
               !str_contains($serverKey, 'YOUR_') &&
               !str_contains($clientKey, 'YOUR_');
    }

    private function createDemoTransaction($orderData)
    {
        // Generate a demo snap token
        $demoToken = 'DEMO_' . base64_encode(json_encode([
            'order_id' => $orderData['transaction_details']['order_id'],
            'amount' => $orderData['transaction_details']['gross_amount'],
            'timestamp' => time()
        ]));

        return [
            'success' => true,
            'snap_token' => $demoToken,
            'redirect_url' => null,
            'demo_mode' => true
        ];
    }

    public function getTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);
            return [
                'success' => true,
                'data' => $status
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function buildTransactionData($transaksi, $tamu, $kamar)
    {
        return [
            'transaction_details' => [
                'order_id' => 'HOTEL-' . $transaksi->id_transaksi . '-' . time(),
                'gross_amount' => (int) $transaksi->sub_total,
            ],
            'customer_details' => [
                'first_name' => $tamu->nama_lengkap,
                'email' => $tamu->email,
                'phone' => $tamu->no_telp,
                'billing_address' => [
                    'address' => $tamu->alamat,
                ]
            ],
            'item_details' => [
                [
                    'id' => 'ROOM-' . $kamar->id_kamar,
                    'price' => (int) $kamar->harga,
                    'quantity' => $this->calculateNights($transaksi->tgl_checkin, $transaksi->tgl_checkout),
                    'name' => 'Kamar ' . $kamar->nomer_kamar . ' - ' . $kamar->tipeKamar->nama_tipe,
                ]
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
                'unfinish' => route('payment.unfinish'),
                'error' => route('payment.error'),
            ]
        ];
    }

    private function calculateNights($checkin, $checkout)
    {
        $checkinDate = \Carbon\Carbon::parse($checkin);
        $checkoutDate = \Carbon\Carbon::parse($checkout);
        return $checkinDate->diffInDays($checkoutDate);
    }

    public function handleNotification($notificationData = null)
    {
        try {
            // Create notification object from Midtrans
            $notification = new Notification($notificationData);

            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus = isset($notification->fraud_status) ? $notification->fraud_status : null;

            $status = 'pending';

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $status = 'pending';
                } else if ($fraudStatus == 'accept') {
                    $status = 'paid';
                }
            } else if ($transactionStatus == 'settlement') {
                $status = 'paid';
            } else if ($transactionStatus == 'cancel' ||
                       $transactionStatus == 'deny' ||
                       $transactionStatus == 'expire') {
                $status = 'failed';
            } else if ($transactionStatus == 'pending') {
                $status = 'pending';
            }

            return [
                'order_id' => $orderId,
                'status' => $status,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'status_code' => $notification->status_code,
                'gross_amount' => $notification->gross_amount
            ];
        } catch (\Exception $e) {
            throw new \Exception('Invalid notification: ' . $e->getMessage());
        }
    }
}
