<?php
use bbn\X;
$fs = new bbn\File\System();
$files = [
  BBN_LIB_PATH.'bbn/bbn/json-doc/Str.json',
  BBN_LIB_PATH.'bbn/bbn/json-doc/X.json',
  BBN_LIB_PATH.'bbn/bbn/json-doc/Mvc/Controller.json',
  BBN_LIB_PATH.'bbn/bbn/json-doc/Mvc/Model.json',
	BBN_LIB_PATH.'bbn/bbn/json-doc/Appui/Option.json',
	BBN_LIB_PATH.'bbn/bbn/json-doc/User.json',
  BBN_LIB_PATH.'bbn/bbn/json-doc/User/Permissions.json',
  BBN_LIB_PATH.'bbn/bbn/json-doc/User/Preferences.json',
  BBN_LIB_PATH.'bbn/bbn/json-doc/Cache.json',
  BBN_LIB_PATH.'bbn/bbn/json-doc/Db.json'
];
$res = [
  [
    'name' => '$model',
    'ref' => 'Model',
    'type' => 'object',
    'items' => [
      [
        'name' => 'db',
        'ref' => 'Db'
      ], [
        'name' => 'inc',
        'type' => 'object',
        'items' => [
          [
            'name' => 'user',
            'ref' => 'User'
          ], [
            'name' => 'options',
            'ref' => 'Option'
          ], [
            'name' => 'pref',
            'ref' => 'Preferences'
          ], [
            'name' => 'perm',
            'ref' => 'Permissions'
          ]
        ]
      ]
    ]
  ], [
    'name' => '$ctrl',
    'ref' => 'Controller',
    'type' => 'object',
    'items' => [
      [
        'name' => 'db',
        'ref' => 'Db'
      ], [
        'name' => 'inc',
        'type' => 'object',
        'items' => [
          [
            'name' => 'user',
            'ref' => 'User'
          ], [
            'name' => 'options',
            'ref' => 'Option'
          ], [
            'name' => 'pref',
            'ref' => 'Preferences'
          ], [
            'name' => 'perm',
            'ref' => 'Permissions'
          ]
        ]
      ]
    ]
  ]
];

foreach ($files as $f) {
  $class = basename($f, '.json');
  $strCls = $fs->decodeContents($f, 'json', true);
  $tmp = [
    'name' => $class,
    'type' => 'class',
    'items' => X::map(
      function ($meth) {
        if ($meth['visibility'] !== 'public') {
          return false;
        }

        $arguments = [];
        if (!empty($meth['arguments'])) {
          $arguments = array_map(
            function ($a) {
              return [
                'name' => $a['name'],
                'optional' => $a['has_default'],
                'default' => $a['has_default'] ? $a['default'] : null,
                'type' => $a['type']
              ];
            },
            $meth['arguments']
          );
        }

        return [
          'name' => $meth['name'],
          'type' => 'fn',
          'desc' => $meth['summary'],
          'args' => $arguments
        ];
      },
      array_values($strCls['methods'])
    )
  ];
  X::sortBy($tmp['items'], 'name');
  $res[] = $tmp;
}


// BBN constants
$cs = get_defined_constants();
foreach ($cs as $k => $c) {
  $res[] = [
    'name' => $k,
    'type' => 'var'
  ];
}

// Native
$fns = get_defined_functions();
foreach ($fns['internal'] as $i => $fn) {

  try {
    $refFunction = new ReflectionFunction($fn);
  }
  catch (\Exception $e) {
    var_dump($e->getMessage());
  }

  if ($refFunction) {
    $tmp = [
      'name' => $fn,
      'type' => 'fn'
    ];
    $parameters = $refFunction->getParameters();
    if (count($parameters)) {
      $tmp['arguments'] = [];
      foreach ($parameters as $parameter) {
        $type = $parameter->getType();
        $tmp['arguments'][] = [
          'name' => $parameter->getName(),
          'optional' => $parameter->isOptional(),
          //'default' => $parameter->getDefaultValue(),
          'type' => $type ? (string)$type : null,
          'nullable' => $type ? $type->allowsNull() : true
        ];
      }
    }
    $res[] = $tmp;
  }
}


file_put_contents(BBN_LIB_PATH.'bbn/bbn/code_ref_php.json', json_encode($res, JSON_PRETTY_PRINT));

X::hdump(count($fns['internal']), array_keys($fns), count($res));

