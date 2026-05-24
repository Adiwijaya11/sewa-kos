<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value by key.
     */
    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Get dynamic gender/resident types.
     */
    public static function getGenderTypes(): array
    {
        $typesJson = self::get('gender_types');
        if ($typesJson) {
            $decoded = json_decode($typesJson, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return $decoded;
            }
        }
        return ['putra', 'putri', 'campur']; // default fallback
    }

    /**
     * Add a new gender/resident type.
     */
    public static function addGenderType(string $type): void
    {
        $type = strtolower(trim($type));
        if (empty($type)) return;
        
        $types = self::getGenderTypes();
        if (!in_array($type, $types)) {
            $types[] = $type;
            self::set('gender_types', json_encode(array_values($types)));
        }
    }

    /**
     * Remove a gender/resident type.
     */
    public static function removeGenderType(string $type): void
    {
        $type = strtolower(trim($type));
        $types = self::getGenderTypes();
        $types = array_filter($types, function($t) use ($type) {
            return $t !== $type;
        });
        
        // Prevent empty list
        if (empty($types)) {
            $types = ['putra', 'putri', 'campur'];
        } else {
            $types = array_values($types); // reset keys
        }
        
        self::set('gender_types', json_encode($types));
    }
}
