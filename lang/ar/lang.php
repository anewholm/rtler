<?php

return [
    'plugin' => [
        'name' => 'Acorn Rtler',
        'description' => 'تغيير تخطيط الواجهة الخلفية إلى RTL',
    ],
    'setting' => [
        'menu' => 'Rtler',
        'description' => 'إدارة إعدادات Acorn Rtler.',
        'category' => 'Acorn',
        'layout_mode' => 'تغيير وضع تخطيط الواجهة الخلفية',
        'editor_mode' => 'تغيير وضع محرر الكود',
        'editor_mode_comment' => 'استخدم Ctrl (Cmd على Mac) + Alt + Shift + (R | L) لجعل السطر RTL أو LTR',
        'markdown_editor_mode' => 'تغيير وضع محرر MarkDown',
        'markdown_editor_mode_comment' => 'إذا كان في وضع RTL، فإنه يغير المحرر أيضًا',
        'never' => 'أبدًا',
        'always' => 'دائمًا',
        'language_based' => 'بناءً على اللغة',
    ],
    'permissions' => [
        'tab' => 'Acorn',
        'label' => 'تغيير إعدادات Rtler'
    ]
];