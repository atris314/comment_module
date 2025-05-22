<?php

namespace Modules\Comment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Comment\Database\Factories\CommentFactory;

class Comment extends Model
{
    use HasFactory;


    protected $fillable = ['name','email','mobile','parent_id','user_id','name','email','mobile','text','status'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected static function newFactory()
    {
        return \Modules\Base\Database\factories\CommentFactory::new();
    }

    public $statuses = [
        'new' => '<span class="badge bg-primary">جدید</span>',
        'accepted' => '<span class="badge bg-success">تایید شد</span>',
        'rejected' => '<span class="badge bg-danger">رد شد</span>',
    ];
    public function getHtmlStatusAttribute()
    {
        return $this->statuses[$this->status] ?? '';
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->hasOne(Comment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
