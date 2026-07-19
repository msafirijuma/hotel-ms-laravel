<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LogActivity
{
    /**
     * Log user activity with validation
     */
    public static function log($action, $description = null, $reference = null)
    {
        // Validation
        $validator = Validator::make([
            'action' => $action,
        ], [
            'action' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // Log error silently or throw exception
            Log::warning('Invalid audit log attempt', $validator->errors()->toArray());
            return false;
        }

        try {
            AuditLog::create([
                'user_id'     => Auth::id(),
                'activity'      => $action,
                'description'     => is_string($description) ? $description : json_encode($description),
                'reference'   => $reference,
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->header('user-agent')
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to log activity: ' . $e->getMessage());
            return false;
        }
    }
}