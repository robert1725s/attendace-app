<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $endTime = $this->input('end_time');
                    if ($endTime && $value > $endTime) {
                        $fail('出勤時間もしくは退勤時間が不適切な値です');
                    }
                },
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    // start_timeでメッセージを出す場合は、スキップする
                    $endTime = $this->input('end_time');
                    if ($endTime && $value > $endTime) {
                        return;
                    }

                    $startTime = $this->input('start_time');
                    if ($value < $startTime) {
                        $fail('出勤時間もしくは退勤時間が不適切な値です');
                    }
                },
            ],
            'rest.*.start' => [
                'nullable',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    if (!$value) {
                        return;
                    }

                    // 対応する休憩終了時間をチェック
                    preg_match('/rest\.(\d+)\.start/', $attribute, $matches);
                    if (isset($matches[1])) {
                        $index = $matches[1];
                        $restEnd = $this->input("rest.{$index}.end");

                        // 休憩終了時間が入力されていない場合
                        if (!$restEnd) {
                            $fail('休憩時間が不適切な値です');
                            return;
                        }
                    }

                    $startTime = $this->input('start_time');
                    $endTime = $this->input('end_time');

                    // 出勤時間より後かチェック
                    if ($startTime && $value < $startTime) {
                        $fail('休憩時間が不適切な値です');
                        return;
                    }

                    // 退勤時間より前かチェック
                    if ($endTime && $value > $endTime) {
                        $fail('休憩時間が不適切な値です');
                        return;
                    }

                    // 対応する休憩終了時間より前かチェック
                    if (isset($matches[1])) {
                        $index = $matches[1];
                        $restEnd = $this->input("rest.{$index}.end");
                        if ($restEnd && $value > $restEnd) {
                            $fail('休憩時間が不適切な値です');
                        }
                    }
                },
            ],
            'rest.*.end' => [
                'nullable',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    if (!$value) {
                        return;
                    }

                    // 対応する休憩開始時間をチェック
                    preg_match('/rest\.(\d+)\.end/', $attribute, $matches);
                    if (isset($matches[1])) {
                        $index = $matches[1];
                        $restStart = $this->input("rest.{$index}.start");

                        // 休憩開始時間が入力されていない場合
                        if (!$restStart) {
                            $fail('休憩時間が不適切な値です');
                            return;
                        }
                    }

                    $startTime = $this->input('start_time');
                    $endTime = $this->input('end_time');

                    // 出勤時間より後かチェック
                    if ($startTime && $value < $startTime) {
                        $fail('休憩時間もしくは退勤時間が不適切な値です');
                        return;
                    }

                    // 退勤時間より前かチェック
                    if ($endTime && $value > $endTime) {
                        $fail('休憩時間もしくは退勤時間が不適切な値です');
                        return;
                    }

                    // 対応する休憩開始時間より後かチェック
                    if (isset($matches[1])) {
                        $index = $matches[1];
                        $restStart = $this->input("rest.{$index}.start");
                        if ($restStart && $value < $restStart) {
                            $fail('休憩時間もしくは退勤時間が不適切な値です');
                        }
                    }
                },
            ],
            'reason' => [
                'required'
            ],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'start_time.required' => '出勤時間を記入してください',
            'end_time.required' => '退勤時間を記入してください',
            'reason.required' => '備考を記入してください',
        ];
    }
}
