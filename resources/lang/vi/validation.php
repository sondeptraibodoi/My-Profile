<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
     */

    'accepted' => 'Trường :attribute phải được chấp nhận.',
    'active_url' => 'Trường :attribute không phải là một URL hợp lệ.',
    'after' => 'Trường :attribute phải là một ngày sau ngày :date.',
    'after_or_equal' => 'Trường :attribute phải là một ngày sau hoặc bằng ngày :date.',
    'alpha' => 'Trường :attribute chỉ có thể chứa các chữ cái.',
    'alpha_dash' => 'Trường :attribute chỉ có thể chứa chữ cái, số và dấu gạch ngang.',
    'alpha_num' => 'Trường :attribute chỉ có thể chứa chữ cái và số.',
    'array' => 'Kiểu dữ liệu của trường :attribute phải là dạng mảng.',
    'before' => 'Trường :attribute phải là một ngày trước ngày :date.',
    'before_or_equal' => 'Trường :attribute phải là một ngày trước hoặc bằng ngày :date.',
    'between' => [
        'array' => 'Trường :attribute phải có từ :min - :max phần tử.',
        'file' => 'Dung lượng tập tin trong trường :attribute phải từ :min - :max kB.',
        'numeric' => 'Trường :attribute phải nằm trong khoảng :min - :max.',
        'string' => 'Trường :attribute phải từ :min - :max ký tự.',
    ],
    'boolean' => 'Trường :attribute phải là true hoặc false.',
    'confirmed' => 'Giá trị xác nhận trong trường :attribute không khớp.',
    'date' => 'Trường :attribute không phải là định dạng của ngày-tháng.',
    'date_equals' => 'Trường :attribute phải là một ngày bằng với :date.',
    'date_format' => 'Trường :attribute không giống với định dạng :format.',
    'different' => 'Trường :attribute và :other phải khác nhau.',
    'digits' => 'Độ dài của trường :attribute phải gồm :digits chữ số.',
    'digits_between' => 'Độ dài của trường :attribute phải nằm trong khoảng :min and :max chữ số.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'Trường :attribute phải là một địa chỉ email hợp lệ.',
    'ends_with' => 'Trường :attribute phải kết thúc bằng một trong những giá trị sau: :values',
    'exists' => 'Giá trị đã chọn trong trường :attribute không hợp lệ.',
    'file' => 'Trường :attribute phải là một tập tin.',
    'filled' => 'Trường :attribute không được bỏ trống.',
    'gt' => [
        'array' => 'Trường :attribute phải lớn hơn :max phần tử.',
        'file' => 'Dung lượng tập tin trong trường :attribute phải lớn hơn :max KB.',
        'numeric' => 'Trường :attribute phải lớn hơn :max.',
        'string' => 'Trường :attribute phải lớn hơn :max ký tự.',
    ],
    'gte' => [
        'array' => 'Trường :attribute phải lớn hơn hoặc bằng :max phần tử.',
        'file' => 'Dung lượng tập tin trong trường :attribute phải lớn hơn hoặc bằng :max KB.',
        'numeric' => 'Trường :attribute phải lớn hơn hoặc bằng :max.',
        'string' => 'Trường :attribute phải lớn hơn hoặc bằng :max ký tự.',
    ],
    'image' => 'Các tập tin trong trường :attribute phải là định dạng hình ảnh.',
    'in' => 'Giá trị đã chọn trong trường :attribute không hợp lệ.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'Trường :attribute phải là một số nguyên.',
    'ip' => 'Trường :attribute phải là một địa chỉa IP.',
    'ipv4' => 'Trường :attribute phải là địa chỉ IPv4 hợp lệ.',
    'ipv6' => 'Trường :attribute phải là địa chỉ IPv6 hợp lệ.',
    'json' => 'Trường :attribute phải là chuỗi JSON hợp lệ.',
    'lowercase' => 'Trường :attribute phải được viết thường toàn bộ.',
    'lt' => [
        'array' => 'Trường :attribute phải có nhỏ hơn :min phần tử.',
        'file' => 'Dung lượng tập tin trong trường :attribute phải nhỏ hơn :min KB.',
        'numeric' => 'Trường :attribute phải nhỏ hơn là :min.',
        'string' => 'Trường :attribute phải có nhỏ hơn :min ký tự.',
    ],
    'lte' => [
        'array' => 'Trường :attribute phải có nhỏ hơn hoặc bằng :min phần tử.',
        'file' => 'Dung lượng tập tin trong trường :attribute phải nhỏ hơn hoặc bằng :min KB.',
        'numeric' => 'Trường :attribute phải nhỏ hơn hoặc bằng là :min.',
        'string' => 'Trường :attribute phải có nhỏ hơn hoặc bằng :min ký tự.',
    ],
    'max' => [
        'array' => 'Trường :attribute không được lớn hơn :max phần tử.',
        'file' => 'Dung lượng tập tin trong trường :attribute không được lớn hơn :max KB.',
        'numeric' => 'Trường :attribute không được lớn hơn :max.',
        'string' => 'Trường :attribute không được lớn hơn :max ký tự.',
    ],
    'mimes' => 'Trường :attribute phải là một tập tin có định dạng: :values.',
    'mimetypes' => 'Trường :attribute phải là một tệp có định dạng là: :values.',
    'min' => [
        'numeric' => 'Trường :attribute không được bé hơn :min.',
        'file' => 'Dung lượng tập tin trong trường :attribute phải tối thiểu :min KB.',
        'string' => 'Trường :attribute phải có tối thiểu :min ký tự.',
        'array' => 'Trường :attribute phải có tối thiểu :min phần tử.',
    ],
    'not_in' => 'Giá trị đã chọn trong trường :attribute không hợp lệ.',
    'not_regex' => 'Trường :attribute định dạng không hợp lệ.',
    'numeric' => 'Trường :attribute phải là một số.',
    'password' => [
        'letters' => 'Trường :attribute phải chứa ít nhất 1 kí tự.',
        'mixed' => 'Trường :attribute phải chứa ít nhất một kí tự thường và 1 kí tự in hoa.',
        'numbers' => 'Trường :attribute phải chứa ít nhất 1 chữ số.',
        'symbols' => 'Trường :attribute phải chứa ít nhất 1 biểu tượng.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present' => 'The :attribute field must be present.',
    'regex' => 'Định dạng trường :attribute không hợp lệ.',
    'required' => 'Trường :attribute không được bỏ trống.',
    'required_if' => 'Trường :attribute không được bỏ trống khi trường :other là :value.',
    'required_if_accepted' => 'Trường :attribute field is required when :other is accepted.',
    'required_unless' => 'The :attribute không được bỏ trống trừ khi trường :other là :values.',
    'required_with' => 'Trường :attribute không được bỏ trống khi trường :values có giá trị.',
    'required_with_all' => 'The :attribute field is required when :values is present.',
    'required_without' => 'Trường :attribute không được bỏ trống khi trường :values không có giá trị.',
    'required_without_all' => 'Trường :attribute không được bỏ trống khi tất cả :values không có giá trị.',
    'same' => 'Trường :attribute và :other phải giống nhau.',
    'size' => [
        'array' => 'Trường :attribute phải chứa :size phần tử.',
        'file' => 'Dung lượng tập tin trong trường :attribute phải bằng :size kB.',
        'numeric' => 'Trường :attribute phải bằng :size.',
        'string' => 'Trường :attribute phải chứa :size ký tự.',
    ],
    'starts_with' => 'Trường :attribute phải được bắt đầu bằng một trong những giá trị sau: :values',
    'string' => 'Trường :attribute phải là một chuỗi.',
    'timezone' => 'Trường :attribute phải là một múi giờ hợp lệ.',
    'unique' => 'Trường :attribute đã có trong hệ thống.',
    'uploaded' => 'Trường :attribute không thể tải lên.',
    'uppercase' => 'Trường :attribute phải được viết hoa toàn bộ.',
    'url' => 'Trường :attribute không giống với định dạng một URL.',
    'ulid' => 'Trường :attribute không phải là một chuỗi ULID hợp lệ',
    'uuid' => 'Trường :attribute không phải là một chuỗi UUID hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
     */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
     */

    'attributes' => [],
    'not_exists' => ':attribute đã tồn tại.',
    'currentpassword' => 'Mật khẩu không đúng',
    'password' => 'Mật khẩu không đúng.',
    'user_not_active' => 'Tài khoản đã bị khóa. Liên hệ quản trị hệ thống để biết thông tin chi tiết',

];
