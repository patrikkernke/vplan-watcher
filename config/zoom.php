<?php

return [
    'congregation' => [
        'service_meeting' => [
            'id' => (explode('|', env('ZOOM_CONGREGATION_SERVICE_MEETING', 'id|pass|link')))[0],
            'password' => (explode('|', env('ZOOM_CONGREGATION_SERVICE_MEETING', 'id|pass|link')))[1],
            'link'=> (explode('|', env('ZOOM_CONGREGATION_SERVICE_MEETING', 'id|pass|link')))[2]
        ],
    ],
    'field_service_group' => [
        'irlich' => [
            'id' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_IRLICH', 'id|pass|link')))[0],
            'password' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_IRLICH', 'id|pass|link')))[1],
            'link'=> (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_IRLICH', 'id|pass|link')))[2]
        ],
        'bendorf_1' => [
            'id' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_BENDORF_1', 'id|pass|link')))[0],
            'password' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_BENDORF_1', 'id|pass|link')))[1],
            'link'=> (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_BENDORF_1', 'id|pass|link')))[2]
        ],
        'bendorf_2' => [
            'id' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_BENDORF_2', 'id|pass|link')))[0],
            'password' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_BENDORF_2', 'id|pass|link')))[1],
            'link'=> (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_BENDORF_2', 'id|pass|link')))[2]
        ],
        'niederbieber' => [
            'id' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_NIEDERBIEBER', 'id|pass|link')))[0],
            'password' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_NIEDERBIEBER', 'id|pass|link')))[1],
            'link'=> (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_NIEDERBIEBER', 'id|pass|link')))[2]
        ],
        'neuwied_1' => [
            'id' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_NEUWIED_1', 'id|pass|link')))[0],
            'password' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_NEUWIED_1', 'id|pass|link')))[1],
            'link'=> (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_NEUWIED_1', 'id|pass|link')))[2]
        ],
        'neuwied_2' => [
            'id' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_NEUWIED_2', 'id|pass|link')))[0],
            'password' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_NEUWIED_2', 'id|pass|link')))[1],
            'link'=> (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_NEUWIED_2', 'id|pass|link')))[2]
        ],
        'turkisch' => [
            'id' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_TUERKISCH', 'id|pass|link')))[0],
            'password' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_TUERKISCH', 'id|pass|link')))[1],
            'link'=> (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_TUERKISCH', 'id|pass|link')))[2]
        ],
        'turkisch_stern' => [
            'id' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_TUERKISCH_STERN', 'id|pass|link')))[0],
            'password' => (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_TUERKISCH_STERN', 'id|pass|link')))[1],
            'link'=> (explode('|', env('ZOOM_FIELD_SERVICE_GROUP_TUERKISCH_STERN', 'id|pass|link')))[2]
        ],
    ]
];
