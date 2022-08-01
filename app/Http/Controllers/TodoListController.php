<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TodoListController extends Controller
{
    public function index(): JsonResponse
    {
        return self::successJsonWithItems();
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'text' => ['string', 'required', 'max:255'],
        ]);

        if ($validator->fails()) return response()->json(self::error($validator->errors()->all()));

        $data = $validator->validated();
        $data['code'] = $this->getCodeByText($data['text']);

        $todo = TodoList::create($data);

        if (!$todo->save()) return response()->json(self::error('Не удалось сохранить элемент. Попробуйте еще раз.'));

        return self::successJsonWithItems();
    }

    public function destroy(string $code): JsonResponse
    {
        if (!($item = TodoList::where('code', $code)->first())) return response()->json(self::error('Элемент не найден.'));
        if (!$item->delete()) return response()->json(self::error('Не удалось удалить элемент. Попробуйте еще раз.'));

        return self::successJsonWithItems();
    }

    public function update(string $code, Request $request): JsonResponse
    {
        if (!($item = TodoList::where('code', $code)->first())) return response()->json(self::error('Элемент не найден.'));

        $validator = Validator::make($request->all(), [
            'text' => ['string', 'max:255'],
            'isDone' => ['boolean'],
            'isUrgent' => ['boolean'],
        ]);
        if ($validator->fails()) return response()->json(self::error($validator->errors()->all()));

        $data = $validator->validated();
        $itemArray = $item->toArray();

        foreach ($data as $fieldName => $fieldValue) {
            if ($itemArray[$fieldName] == $fieldValue) unset($data[$fieldName]);
        }

        if (isset($data['text'])) $data['code'] = $this->getCodeByText($data['text']);

        if (!empty($data) && !$item->update($data)) return response()->json(self::error('Не удалось обновить элемент. Попробуйте еще раз.'));

        return self::successJsonWithItems();
    }

    private function getCodeByText(string $text): string
    {
        return self::makeUniqueCode(array_keys(TodoList::withTrashed()->get(['code'])->groupBy('code')->toArray()), $text);
    }

    private function successJsonWithItems(): JsonResponse
    {
        return response()->json(self::success(['items' => TodoList::all(['text', 'code', 'isUrgent', 'isDone'])->sortBy('updated_at')->toArray()]));
    }

    //методы ниже добавил в отдельный хелпер, локально все работало, но на heroku падала ошибка, что он не знает такие функции, поэтому пришлось перенести сюда...
    public static function translit(string $value, string $delimiter = '-'): string
    {
        $converter = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ь' => '', 'ы' => 'y', 'ъ' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        ];

        $value = mb_strtolower($value);
        $value = strtr($value, $converter);
        $value = mb_ereg_replace('[^-0-9a-z]', $delimiter, $value);
        $value = mb_ereg_replace('[-]+', $delimiter, $value);
        $value = trim($value, $delimiter);

        return $value;
    }

    public static function makeCode(string $name, string $delimiter = '-', int $maxLength = 10): string
    {
        $newCode = self::translit((empty($name) ? 'no-name' : $name), $delimiter);
        if (mb_strlen($newCode) > $maxLength) $newCode = mb_substr($newCode, 0, $maxLength);

        return $newCode;
    }

    public static function makeUniqueCode(array $existCodes, string $name, string $delimiter = '-', int $maxTextLength = 10): string
    {
        $newCode = self::makeCode($name, $delimiter, $maxTextLength);

        if (!in_array($newCode, $existCodes)) {
            $newCodeName = $newCode;
        } else {
            $kk = 2;
            do {
                $newCodeName = $newCode . $delimiter . $kk++;
            } while (in_array($newCodeName, $existCodes));
        }

        return $newCodeName;
    }

    private function success($data): array
    {
        return ['success' => true, 'data' => $data];
    }

    private function error($data): array
    {
        return ['success' => false, 'data' => $data];
    }
}
