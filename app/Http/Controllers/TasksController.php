<?php

namespace App\Http\Controllers;

use App\Models\Tasks;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
  private function fetchData($code)
  {
    $modelClass = 'App\\Models\\' . ucfirst($code);

    if (!class_exists($modelClass)) {
      abort(404, "Model not found for code: $code");
    }

    $data = $modelClass::where('user_id', Auth::id())->latest()->paginate(10);

    return $data;
  }

  public function list(Request $request, $code)
  {
    $modelClass = 'App\\Models\\' . ucfirst($code);

    if (!class_exists($modelClass)) {
      abort(404, "Model not found for code: $code");
    }

    $modelInstance = new $modelClass();
    $query = $modelInstance::query()->where('user_id', Auth::id());
    $results = $query->get();
    $codeData = $query->paginate(10)->withQueryString();
    $listColumns = $modelInstance->getListColumns();
    $modelName = class_basename($modelClass);

    return view('crud.list', compact('codeData', 'listColumns', 'modelName', 'code', 'results'));
  }

  public function delete(Request $request, $code, $id)
  {
    $modelClass = 'App\\Models\\' . ucfirst($code);

    if (!class_exists($modelClass)) {
      abort(404, "Model not found for code: $code");
    }

    $item = $modelClass::where('user_id', Auth::id())->find($id);

    if (!$item) {
      abort(404, "Item not found with ID: $id");
    }

    $item->delete();

    return Redirect::back()->with('success', 'Item deleted successfully');
  }

  public function save(Request $request, $code, $id = null)
  {
      // Model handling
      $modelClass = 'App\\Models\\' . ucfirst($code);
      if (!class_exists($modelClass)) {
          abort(404, "Model not found for code: $code");
      }

      // Update or create logic
      $item = $id ? $modelClass::find($id) : new $modelClass();
      if (!$item) {
          abort(404, "Item not found with ID: $id");
      }

      // Assign form data to the model
      foreach ($item->getFillable() as $fillable) {
          if ($request->has($fillable)) {
              $item->$fillable = $request->input($fillable);
          }
      }

      if (Auth::check()) {
          $item->user_id = Auth::id();
      }

      // Ensure 'complete' field is handled
      if ($request->has('complete')) {
          $item->complete = $request->input('complete');
      }

      $item->save();

      return redirect()
        ->route('crud.list', ['code' => $code])
        ->with('success', 'Item ' . ($id ? 'updated' : 'created') . ' successfully');
  }

  public function edit(Request $request, $code, $id)
  {
    $modelClass = 'App\\Models\\' . ucfirst($code);

    if (!class_exists($modelClass)) {
      abort(404, "Model not found for code: $code");
    }

    $item = $modelClass::where('user_id', Auth::id())->find($id);

    if (!$item) {
      abort(404, "Item not found with ID: $id");
    }

    $modelName = class_basename(get_class($item));
    $attributes = array_diff(array_keys($item->getAttributes()), ['created_at', 'updated_at']);
    $listColumns = (new $modelClass())->getCreateColumns();

    return view('crud.crud', compact('item', 'modelName', 'code', 'attributes', 'listColumns'));
  }

  public function create(Request $request, $code)
  {
    $modelClass = 'App\\Models\\' . ucfirst($code);

    if (!class_exists($modelClass)) {
      abort(404, "Model not found for code: $code");
    }

    $codeData = $this->fetchData($code);

    if ($codeData->isNotEmpty()) {
      $modelName = class_basename(get_class($codeData->first()));
      $modelInstance = $codeData->first()->getModel();
      $listColumns = $modelInstance->getCreateColumns();
    } else {
      $modelInstance = new $modelClass();
      $listColumns = $modelInstance->getCreateColumns();
      $modelName = class_basename($modelInstance);
    }

    return view('crud.crud', compact('codeData', 'listColumns', 'modelName', 'code'));
  }
}
