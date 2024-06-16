<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Tasks extends Model
{
  use HasFactory;

  protected $table = 'tasks';
  protected $primaryKey = 'id';
  protected $fillable = ['title','is_completed','user_id'];


  public function User()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function getListColumns(Request $request = null)
  {
    $listColumns = [
     'is_completed' => "Completed",
      'title' => "Title",

    ];

    return $listColumns;
  }

  public function listButtons()
  {
      $code = strtolower(class_basename($this));

      return [
          'Edit' => route('crud.edit', ['code' => $code, 'id' => $this->id]),
          'Delete' => route('crud.delete', ['code' => $code, 'id' => $this->id]),
      ];
  }


  public function getCreateColumns(Request $request = null)
  {
    $listColumns = [
      'title' => [
        'display_name' => 'Title',
        'data_type' => 'string',
        'length' => '255',
      ],

    //   'is_completed' => [
    //     'display_name' => 'Completed',
    //     'data_type' => 'radio',
    //     'length' => '255',
    //   ],


    ];
    return $listColumns;
  }


}
