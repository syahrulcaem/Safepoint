<?php

namespace App\Services;

use App\Models\Cases;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Create notification for new case
     *
     * @param Cases $case
     * @return void
     */
    public function createNewCaseNotification(Cases $case)
    {
        try {
            // Log new case creation
            Log::info('New emergency case created', [
                'case_id' => $case->id,
                'case_type' => $case->case_type,
                'priority' => $case->priority,
                'latitude' => $case->latitude,
                'longitude' => $case->longitude,
                'reporter_id' => $case->reporter_user_id,
                'created_at' => $case->created_at,
            ]);

            // Get all admin and responder users who should be notified
            $notifiableUsers = User::whereIn('role', ['admin', 'responder'])
                ->where('is_active', true)
                ->get();

            // TODO: Implement actual notification logic
            // Example options:
            // 1. Send push notification via Firebase
            // 2. Send SMS via Twilio
            // 3. Send email notification
            // 4. Send in-app notification
            // 5. Trigger webhook to external system

            foreach ($notifiableUsers as $user) {
                // Example: Create in-app notification record
                // Notification::send($user, new NewEmergencyCaseNotification($case));

                Log::info('Notification queued for user', [
                    'user_id' => $user->id,
                    'case_id' => $case->id,
                ]);
            }

            // If case is HIGH or CRITICAL priority, trigger immediate alerts
            if (in_array($case->priority, ['HIGH', 'CRITICAL'])) {
                $this->sendUrgentAlert($case);
            }
        } catch (\Exception $e) {
            // Log error but don't block case creation
            Log::error('Failed to send new case notification', [
                'case_id' => $case->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Create notification for case status update
     *
     * @param Cases $case
     * @return void
     */
    public function createCaseStatusUpdateNotification(Cases $case)
    {
        try {
            // Log status change
            Log::info('Case status updated', [
                'case_id' => $case->id,
                'new_status' => $case->status,
                'updated_at' => $case->updated_at,
            ]);

            // Notify the reporter (if exists and not anonymous)
            if ($case->reporter_user_id) {
                $reporter = User::find($case->reporter_user_id);
                if ($reporter) {
                    // TODO: Send notification to reporter
                    // Notification::send($reporter, new CaseStatusUpdatedNotification($case));

                    Log::info('Status update notification sent to reporter', [
                        'user_id' => $reporter->id,
                        'case_id' => $case->id,
                        'status' => $case->status,
                    ]);
                }
            }

            // If assigned to responder, notify them
            if ($case->assigned_responder_id) {
                $responder = User::find($case->assigned_responder_id);
                if ($responder) {
                    // TODO: Send notification to assigned responder
                    // Notification::send($responder, new CaseAssignedNotification($case));

                    Log::info('Assignment notification sent to responder', [
                        'responder_id' => $responder->id,
                        'case_id' => $case->id,
                    ]);
                }
            }

            // Notify admins of status changes
            $admins = User::where('role', 'admin')
                ->where('is_active', true)
                ->get();

            foreach ($admins as $admin) {
                // TODO: Send notification to admins
                Log::info('Status update notification sent to admin', [
                    'admin_id' => $admin->id,
                    'case_id' => $case->id,
                    'status' => $case->status,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send status update notification', [
                'case_id' => $case->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send urgent alert for high priority cases
     *
     * @param Cases $case
     * @return void
     */
    protected function sendUrgentAlert(Cases $case)
    {
        try {
            Log::alert('URGENT: High priority emergency case', [
                'case_id' => $case->id,
                'case_type' => $case->case_type,
                'priority' => $case->priority,
                'latitude' => $case->latitude,
                'longitude' => $case->longitude,
                'what3words' => $case->what3words,
            ]);

            // TODO: Implement urgent alert mechanisms
            // 1. Send SMS to on-duty responders
            // 2. Trigger phone calls to emergency contacts
            // 3. Send push notifications with high priority
            // 4. Alert dashboard with sound/visual notification
            // 5. Integration with emergency dispatch system

        } catch (\Exception $e) {
            Log::error('Failed to send urgent alert', [
                'case_id' => $case->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify responders in specific area/radius
     *
     * @param Cases $case
     * @param float $radiusKm
     * @return void
     */
    public function notifyNearbyResponders(Cases $case, float $radiusKm = 5.0)
    {
        try {
            // TODO: Implement geolocation-based notification
            // 1. Find responders within radius using Haversine formula
            // 2. Send notifications to nearby available responders
            // 3. Prioritize based on distance and response time

            Log::info('Nearby responder notification triggered', [
                'case_id' => $case->id,
                'radius_km' => $radiusKm,
                'latitude' => $case->latitude,
                'longitude' => $case->longitude,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to notify nearby responders', [
                'case_id' => $case->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
