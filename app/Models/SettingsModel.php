<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['setting_key', 'setting_value', 'setting_type'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getValue(string $key, string $default = ''): string
    {
        $row = $this->where('setting_key', $key)->first();
        return $row ? $row['setting_value'] : $default;
    }

    public function getBulk(string $key): string
    {
        $row = $this->where('setting_key', $key)->first();
        return $row ? $row['setting_value'] : '';
    }

    public function getAll(): array
    {
        $settings = $this->findAll();
        $result = [];
        foreach ($settings as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }
        return $result;
    }

    public function setValue(string $key, string $value): bool
    {
        $row = $this->where('setting_key', $key)->first();
        if ($row) {
            return $this->update($row['id'], ['setting_value' => $value]);
        } else {
            return (bool) $this->insert([
                'setting_key' => $key,
                'setting_value' => $value,
            ]);
        }
    }
}
