<?php

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model
{
    protected $table = 'ratings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['order_id', 'user_id', 'rating', 'review'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    public function getOrderRating($orderId): ?array
    {
        return $this->where('order_id', $orderId)->first();
    }

    public function getAverageRating(): float
    {
        $result = $this->select('AVG(rating) as avg_rating')->first();
        return $result ? round((float) $result['avg_rating'], 1) : 0.0;
    }

    public function getTotalReviews(): int
    {
        return $this->countAllResults();
    }

    public function getTestimonials(int $limit = 3): array
    {
        return $this->select('ratings.*, users.name as user_name, users.role as user_role')
                    ->join('users', 'users.id = ratings.user_id')
                    ->where('ratings.review IS NOT NULL')
                    ->where('ratings.review !=', '')
                    ->orderBy('ratings.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getSocialProof(): array
    {
        $avg = $this->getAverageRating();
        $total = $this->getTotalReviews();
        return [
            'average_rating' => $avg,
            'total_reviews' => $total,
            'formatted_reviews' => number_format($total, 0, '.', '.'),
        ];
    }
}
