<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Задачи по рассылке
 */

/**
 * @property string type
 *  'once' - одиночная рассылка; 
 *  'daily' - ежедневная; 
 *  'weekly' - раз в неделю; 
 *  'monthly' - раз в месяц;
 *  'birthday' - ко дню рождения
 * 
 * @property integer time - интерпритация значения зависит от поля "type"
 *  'once' - unix time рассылки, 0 - немедленно; 
 *  'daily' - количество часов до рассылки от начала дня; 
 *  'weekly' - количество часов до рассылки от начала недели; 
 *  'monthly' - количество часов до рассылки от начала месяца;
 *  'birthday' - за какое количество часов перед днем рождения;
 *
 * @property boolean active - активна ли задача по рассылке
 * 
 * @property string text - текст рассылки
 */
class Task extends Model
{
    use HasFactory;

    protected $fillable = ['time', 'active', 'type', 'text'];

    protected $casts = ['active' => 'boolean'];

    public function segment(): BelongsTo
    {
        return $this->belongsTo(Segment::class);
    }
}
