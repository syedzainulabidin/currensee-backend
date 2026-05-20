<?php

namespace App\Console\Commands;

use App\Models\RateAlert;
use App\Services\ExchangeRateService;
use App\Services\PushNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckRateAlerts extends Command
{
    protected $signature   = 'alerts:check';
    protected $description = 'Check active rate alerts and fire push notifications when thresholds are hit';

    public function __construct(
        protected ExchangeRateService     $rates,
        protected PushNotificationService $push,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        // Load all active, untriggered alerts with their user
        $alerts = RateAlert::active()->with('user')->get();

        if ($alerts->isEmpty()) {
            return;
        }

        // Group by base currency to minimise API calls (one call per base)
        $grouped = $alerts->groupBy('from_currency');

        foreach ($grouped as $baseCurrency => $group) {
            try {
                $ratesData = $this->rates->getRates($baseCurrency);
                $allRates  = $ratesData['rates'] ?? [];
            } catch (\Throwable $e) {
                Log::error("CheckRateAlerts: failed to fetch rates for {$baseCurrency}: " . $e->getMessage());
                continue;
            }

            foreach ($group as $alert) {
                $target  = $alert->to_currency;
                $current = (float) ($allRates[$target] ?? 0);

                if ($current === 0.0) continue;

                $triggered = match ($alert->direction) {
                    'above' => $current >= $alert->target_rate,
                    'below' => $current <= $alert->target_rate,
                    default => false,
                };

                if (!$triggered) continue;

                // Mark as triggered first to avoid duplicate notifications
                $alert->update([
                    'triggered_at' => now(),
                    'is_active'    => false,
                ]);

                // Send push notification
                $direction = $alert->direction === 'above' ? 'risen above' : 'fallen below';
                $title     = "Rate Alert: {$alert->from_currency}/{$alert->to_currency} 🔔";
                $body      = "{$alert->from_currency}/{$alert->to_currency} has {$direction} your target of {$alert->target_rate}. Current rate: " . round($current, 4);

                try {
                    $this->push->sendToUser($alert->user_id, $title, $body, [
                        'type'          => 'rate_alert',
                        'from'          => $alert->from_currency,
                        'to'            => $alert->to_currency,
                        'current_rate'  => (string) round($current, 4),
                        'target_rate'   => (string) $alert->target_rate,
                    ]);
                } catch (\Throwable $e) {
                    Log::error("CheckRateAlerts: push failed for alert #{$alert->id}: " . $e->getMessage());
                }
            }
        }
    }
}
