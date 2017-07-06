<?php
$userRolesColumnFilter = ['type' => []];
foreach ($userRoles as $userRole) {
    $userRolesColumnFilter['role'][] = [
        'value' => '=' . $userRole['slug'],
        'display' => studly_case($userRole['slug'])
    ];
}
?>
@include('wmcms::crud.datatable-filter', [
    'daterangepicker' => Route::getCurrentRoute()->getActionMethod() == 'recycle' ? 'deleted_at' : 'updated_at',
     'columnFilters' => [
        'HEADER:Columns',
        'ID',
        'email',
        'name',
        'phone',
        'address',
        'SEPARATOR',
        'HEADER:User Roles',
        $userRolesColumnFilter
     ]
])