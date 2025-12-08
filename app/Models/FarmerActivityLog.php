<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerActivityLog extends Model
{
    use HasFactory;

    const UPDATED_AT = null; // Only created_at

    protected $fillable = [
        'farmer_id',
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'old_values',
        'new_values',
        'metadata',
        'ip_address',
        'user_agent',
        'device_type',
        'tags',
        'severity',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'tags' => 'array',
        'created_at' => 'datetime',
    ];

    // Relationships
    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Static Helper Methods
    public static function logActivity($data)
    {
        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();
        
        return self::create(array_merge($data, [
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'device_type' => self::detectDeviceType($userAgent),
        ]));
    }

    public static function logCreate($farmer, $user, $entityType, $entityId, $description, $newValues = null)
    {
        return self::logActivity([
            'farmer_id' => $farmer->id,
            'user_id' => $user->id,
            'action' => 'created',
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'new_values' => $newValues,
            'severity' => 'info',
        ]);
    }

    public static function logUpdate($farmer, $user, $entityType, $entityId, $description, $oldValues = null, $newValues = null)
    {
        return self::logActivity([
            'farmer_id' => $farmer->id,
            'user_id' => $user->id,
            'action' => 'updated',
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'severity' => 'info',
        ]);
    }

    public static function logDelete($farmer, $user, $entityType, $entityId, $description, $oldValues = null)
    {
        return self::logActivity([
            'farmer_id' => $farmer->id,
            'user_id' => $user->id,
            'action' => 'deleted',
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'old_values' => $oldValues,
            'severity' => 'warning',
        ]);
    }

    private static function detectDeviceType($userAgent)
    {
        if (preg_match('/mobile/i', $userAgent)) {
            return 'Mobile';
        } elseif (preg_match('/tablet/i', $userAgent)) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }
}
