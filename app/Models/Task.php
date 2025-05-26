<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'attachment_url'
    ];
    
    public function rules()
    {
        return [
            "title" => "required|max:255",
            "description" => "nullable",
            "attachment_url" => "nullable|file|mimes:pdf,docx,doc,txt,jpg,jpeg,png"
        ];
    }

    public function feedback() 
    {
        return [
            "required" => "O campo :attribute é obrigatório!",
            "title.max" => "Este campo suporta no maximo 255 caracteres!",
            "attachment_url.mimes" => "São permitidos apenas aquivos PDF, de texto(DOCX, DOC, TXT) e imagens(JPG, JPEG, PNG)!"
        ];
    }

    public function user()
    {
        // uma task pertence a um usuario
        return $this->belongsTo(User::class);
    }
}