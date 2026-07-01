<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'partner_id',
        'avatar', 'phone', 'bio', 'points', 'tier', 'google_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reports()
    {
        return $this->hasMany(ProductReport::class);
    }

    public function followers()
    {
        return $this->hasMany(Follower::class);
    }

    public function questions()
    {
        return $this->hasMany(ProductQuestion::class);
    }

    public function badges()
    {
        return $this->hasMany(UserBadge::class);
    }

    public function approvedUgcPhotos()
    {
        return $this->hasMany(UgcPhoto::class)->where('status', 'approved');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPartner(): bool
    {
        return $this->role === 'partner';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    // Tier badge helper
    public function getTierBadgeAttribute(): string
    {
        return match($this->tier) {
            'platinum' => '💎',
            'gold'     => '🥇',
            'silver'   => '🥈',
            default    => '🥉',
        };
    }

    public function getTierNameAttribute(): string
    {
        return match($this->tier) {
            'platinum' => 'Platinum',
            'gold'     => 'Gold',
            'silver'   => 'Silver',
            default    => 'Regular',
        };
    }

    // Gamification: add points
    public function addPoints(int $points, string $type, string $description = null, $reference = null): void
    {
        $this->increment('points', $points);
        ActivityLog::create([
            'user_id'          => $this->id,
            'activity_type'    => $type,
            'description'      => $description,
            'points_earned'    => $points,
            'referenceable_id' => $reference?->id,
            'referenceable_type'=> $reference ? get_class($reference) : null,
        ]);
        $this->checkTierUpgrade();
    }

    public function checkTierUpgrade(): void
    {
        $tiers = ['regular' => 0, 'silver' => 100, 'gold' => 500, 'platinum' => 1000];
        $newTier = 'regular';
        foreach ($tiers as $tier => $needed) {
            if ($this->points >= $needed) $newTier = $tier;
        }
        if ($newTier !== $this->tier) {
            $this->update(['tier' => $newTier]);
        }
    }

    // ─── BADGES ─────────────────────────────────────────────────────────────
    public function awardBadge(string $type, string $name, string $icon, string $criteria, $reference = null): ?UserBadge
    {
        $badge = UserBadge::firstOrCreate(
            ['user_id' => $this->id, 'badge_name' => $name],
            ['badge_type' => $type, 'badge_icon' => $icon, 'criteria' => $criteria]
        );

        if ($badge->wasRecentlyCreated) {
            $this->addPoints(25, 'badge_awarded', 'Mendapat badge: ' . $name, $reference);
        }

        return $badge;
    }

    public function checkBadges($reference = null): void
    {
        // Review badges
        $reviewCount = $this->reviews()->count();
        if ($reviewCount >= 1) {
            $this->awardBadge('review', 'Reviewer Pertama', '⭐', 'Member pertama kali memberikan review', $reference);
        }
        if ($reviewCount >= 10) {
            $this->awardBadge('review', 'Top Reviewer', '📝', 'Member dengan 10+ review', $reference);
        }

        // UGC badges
        $ugcCount = $this->approvedUgcPhotos()->count();
        if ($ugcCount >= 1) {
            $this->awardBadge('community', 'UGC Contributor', '📸', 'Mengirim foto komunitas yang disetujui', $reference);
        }
        if ($ugcCount >= 5) {
            $this->awardBadge('community', 'Top UGC Contributor', '🏆', 'Mengirim 5+ foto komunitas yang disetujui', $reference);
        }

        // Wishlist badges
        $wishlistCount = $this->wishlists()->count();
        if ($wishlistCount >= 10) {
            $this->awardBadge('collector', 'Wishlist Collector', '❤️', 'Menyimpan 10+ produk ke wishlist', $reference);
        }
        if ($wishlistCount >= 50) {
            $this->awardBadge('collector', 'Master Wishlist', '💎', 'Menyimpan 50+ produk ke wishlist', $reference);
        }

        // Question badges
        $questionCount = $this->questions()->count();
        if ($questionCount >= 1) {
            $this->awardBadge('community', 'Pembeli Penasaran', '❓', 'Mengajukan pertanyaan pertama', $reference);
        }
        if ($questionCount >= 10) {
            $this->awardBadge('community', 'Komunitas Aktif', '💬', 'Mengajukan 10+ pertanyaan', $reference);
        }

        // Follow badges
        $followCount = $this->followers()->count();
        if ($followCount >= 5) {
            $this->awardBadge('social', 'Fashion Follower', '👥', 'Mengikuti 5+ mitra', $reference);
        }
    }

    // ─── PASSWORD RESET ─────────────────────────────────────────────────────
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\CustomResetPassword($token));
    }
}
