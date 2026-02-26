<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'module_name',
        'report_type',
        'other',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function targetPost()
    {
        $modelClass = '\\App\\Models\\' . str_replace(' ', '', ucwords(str_replace('-', ' ', $this->module_name)));
        if (class_exists($modelClass)) {
            return $modelClass::find($this->post_id);
        }
        return null;
    }

    public function getPostNameAttribute()
    {
        $post = $this->targetPost();
        if (!$post) return 'N/A';

        if ($this->module_name == 'job') return $post->title ?? 'N/A';
        if ($this->module_name == 'advertisement') return $post->offer_name ?? 'N/A';
        if (isset($post->name)) return $post->name;
        if (isset($post->title)) return $post->title;
        if (isset($post->brand_name)) return $post->brand_name;

        return 'N/A';
    }

    public function getPostUrlAttribute()
    {
        $module = strtolower($this->module_name);

        $routeMap = [
            'tourism' => 'tourism-business.show',
            'franchise' => 'franchise-business.show',
            'job' => 'job.show',
            'product' => 'product.show',
            'artist' => 'artist.show',
            'on-demand' => 'on-demand-service.show',
            'whole-sell' => 'whole-sell-product.show',
            'business' => 'businesses.show',
            'advertisement' => 'advertisement.show',
            'property' => 'propertyes.show',
        ];

        $postRoute = null;
        foreach ($routeMap as $key => $route) {
            if (str_contains($module, $key)) {
                $postRoute = $route;
                break;
            }
        }

        if ($postRoute) {
            try {
                return route($postRoute, $this->post_id);
            } catch (\Exception $e) {
                // Ignore if route generation fails
            }
        }

        return null;
    }
}
