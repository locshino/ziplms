<?php

return [
    'columns' => [
        'name' => 'Tên đơn vị',
        'code' => 'Mã',
        'parent' => [
            'name' => 'Đơn vị Cha',
        ],
        'organization' => [
            'name' => 'Tổ chức',
        ],
        'tags' => [
            'name' => 'Tags',
        ],
    ],
    'filters' => [
        'parent_id' => 'Lọc theo loại',
    ],
];
