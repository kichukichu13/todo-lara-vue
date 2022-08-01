<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoListController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => ['items' => TodoList::all(['text', 'code', 'isUrgent', 'isDone'])->toArray()]]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => ['string', 'required', 'max:255'],
        ]);

        if ($validator->fails()) return response()->json(['success' => false, 'data' => $validator->errors()->all()]);

        $data = $validator->validated();
        $data['code'] = makeUniqueCode(array_keys(TodoList::withTrashed()->get(['code'])->groupBy('code')->toArray()), $data['text']);

        $todo = TodoList::create($data);

        if (!$todo->save()) return response()->json(['success' => false, 'data' => 'Не удалось сохранить элемент. Попробуйте еще раз.']);

        return response()->json(['success' => true, 'data' => ['items' => TodoList::all(['text', 'code', 'isUrgent', 'isDone'])->toArray()]]);
    }

    public function destroy($code)
    {
        if (!($item = TodoList::where('code', $code)->first())) return response()->json(['success' => false, 'data' => 'Элемент не найден.']);
        if (!$item->delete()) return response()->json(['success' => false, 'data' => 'Не удалось удалить элемент. Попробуйте еще раз.']);

        return response()->json(['success' => true, 'data' => ['items' => TodoList::all(['text', 'code', 'isUrgent', 'isDone'])->toArray()]]);
    }

    public function update($code, Request $request)
    {
        if (!($item = TodoList::where('code', $code)->first())) return response()->json(['success' => false, 'data' => 'Элемент не найден.']);

        $validator = Validator::make($request->all(), [
            'text' => ['string', 'max:255'],
            'isDone' => ['boolean'],
            'isUrgent' => ['boolean'],
        ]);
        if ($validator->fails()) return response()->json(['success' => false, 'data' => $validator->errors()->all()]);

        $data = $validator->validated();
        $itemArray = $item->toArray();

        foreach ($data as $fieldName => $fieldValue) {
            if ($itemArray[$fieldName] == $fieldValue) unset($data[$fieldName]);
        }

        if (isset($data['text'])) {
            $data['code'] = makeUniqueCode(array_keys(TodoList::withTrashed()->get(['code'])->groupBy('code')->toArray()), $data['text']);
        }

        if (!empty($data) && !$item->update($data)) return response()->json(['success' => false, 'data' => 'Не удалось обновить элемент. Попробуйте еще раз.']);

        return response()->json(['success' => true, 'data' => ['items' => TodoList::all(['text', 'code', 'isUrgent', 'isDone'])->toArray()]]);
    }
}
