<?php

namespace App\References;

use App\Models\ListAgencies;
use App\Models\ListCourse;
use App\Models\ListReferences;
use App\Models\ListRole;
use App\Models\ListRoutes;
use App\Models\SchoolCampuses;
use App\Models\Schools;
use Illuminate\Support\Facades\Auth;

class ListClass
{
    public function getRoles(Bool $main, $search = null)
    {
        if ($main) {
            return ListRole::where('is_delete', false)->when($search, function ($query) {
                $search = strtolower(request('search'));

                $query->where(function ($query) use ($search) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
                });
            })->with('users')->orderBy('id')->paginate(10);
        } else {
            return
                ListRole::where('is_active', true)->where('is_delete', false)->get()->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                    ];
                });
        }
    }

    public function getAgencies(Bool $main, $search = null)
    {
        if ($main) {
            return ListAgencies::where('is_delete', false)->when($search, function ($query) {
                $search = strtolower(request('search'));

                $query->where(function ($query) use ($search) {
                    $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                        ->orWhereRaw('LOWER(slug) LIKE ?', ["%{$search}%"]);
                });
            })->orderBy('id', 'desc')->paginate(10);
        } else {
            return
                ListAgencies::where('is_active', true)->where('is_delete', false)->get()->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                    ];
                });
        }
    }


    public function getRefs(string $main, $search = null, $type = null, $classification = null)
    {
        switch ($main) {
            case 'option':

                return
                    ListReferences::where('is_active', true)->where('is_delete', false)
                    ->when($type, function ($query) use ($type) {
                        $query->where('type', $type);
                    })->when($classification, function ($query) use ($classification) {
                        $query->where('classification', $classification);
                    })
                    ->get()
                    ->map(function ($q) {
                        return [
                            'id' => $q->id,
                            'name' => $q->name,
                        ];
                    });
                break;
            case 'distinct':

                return
                    ListReferences::where('is_active', true)->where('is_delete', false)
                    ->select($type . ' as name')
                    ->distinct()
                    ->get()
                    ->map(function ($q) {
                        return [
                            'id' => $q->name,
                            'name' => $q->name,
                        ];
                    });
                break;


            default:
                return ListReferences::where('is_delete', false)->when($search, function ($query) {
                    $search = strtolower(request('search'));

                    $query->where(function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(classification) LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(type) LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(others) LIKE ?', ["%{$search}%"]);
                    });
                })->orderBy('id', 'desc')->paginate(10);
                break;
        }
    }

    public function getCourses(string $typemenu = 'table', $search = null)
    {
        switch ($typemenu) {
            case 'option':
                return ListCourse::where('is_active', true)->where('is_delete', false)->get()->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                    ];
                });

                break;

            case 'suggestion':
                return ListCourse::select('field')
                    ->distinct()
                    ->get()
                    ->map(function ($q) {
                        return [
                            'id' => $q->field,
                            'name' => $q->field,
                        ];
                    });
                break;
            default:
                return ListCourse::where('is_delete', false)->when($search, function ($query) {
                    $search = strtolower(request('search'));
                    $query->where(function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(field) LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(abbreviation) LIKE ?', ["%{$search}%"]);
                    });
                })->orderBy('id', 'desc')->paginate(10);
                break;
        }
    }


    public function getSchools(string $typemenu = 'table', $search = null)
    {
        switch ($typemenu) {
            case 'option':
                return Schools::where('is_active', true)->where('is_delete', false)->get()->map(function ($q) {
                    return [
                        'id' => $q->id,
                        'name' => $q->name,
                    ];
                });
                break;
            default:

                return
                    SchoolCampuses::where([
                        'is_delete' => false,
                        'is_active' => true,
                    ])
                    ->with([
                        'school',
                        'school.reference',
                        'school.campuses',
                        'courses' => function ($q) {
                            $q->where('is_delete', false);
                        },
                        'grades' => function ($q) {
                            $q->where('is_delete', false)
                                ->orderBy('grade', 'asc');
                        },
                        'courses.subjects' => function ($q) {
                            $q->where('is_delete', false);
                        },
                        'address',
                        'term',
                        'grading',
                        'agency'
                    ])
                    ->paginate(10);
                break;
        }
    }


    public function getMenu(string $typemenu = 'table', $search = null)
    {
        switch ($typemenu) {
            case 'option':
                return ListRoutes::where('is_delete', false)->where('is_active', true)->where('is_submenu', false)->whereNull('route')->get()->map(function ($route) {
                    return [
                        'id' => $route->id,
                        'name' => $route->label,
                    ];
                });
                break;
            case 'sidebar':
                return ListRoutes::where('is_delete', false)->where('is_submenu', false)->where('is_active', true)
                    ->whereRaw("
                        EXISTS (
                            SELECT 1 
                            FROM json_array_elements(roles) elem 
                            WHERE (elem->>'id')::int = ?
                        )
                    ", [Auth::user()->role_id ?? null])
                    ->with(['children' => function ($q) {
                        $q->where('is_delete', false);
                    }])
                    ->orderBy('order_no')
                    ->get()->map(function ($route) {
                        return [
                            'key'   => (string) $route->id,
                            'icon'  => $route->icon,
                            'label' => $route->label,
                            'slug'  => $route->slug,
                            'route' => $route->route,
                            'component' => $route->component,
                            'is_active' => $route->is_active,
                            'items' => $route->children
                                ->filter(fn($child) => $child->is_active)
                                ->map(function ($child, $index) use ($route) {

                                    return [
                                        'key' => "{$route->id}-{$index}",
                                        'icon' => $child->icon,
                                        'label' => $child->label,
                                        'slug'  => $child->slug,
                                        'route' => $child->route,
                                        'subItem' => $child->is_submenu,
                                        'component' => $child->component,
                                        'is_active' => $child->is_active,
                                    ];
                                })->values()->toArray(),
                        ];
                    });
                break;
            default:
                return ListRoutes::where('is_delete', false)->where('is_submenu', false)
                    ->with(['children' => function ($q) {
                        $q->where('is_delete', false);
                    }])
                    ->orderBy('order_no', 'ASC')
                    ->when($search, function ($query) {
                        $search = strtolower(request('search'));
                        $query->where(function ($query) use ($search) {
                            $query->whereRaw('LOWER(label) LIKE ?', ["%{$search}%"])
                                ->orWhereRaw('LOWER(route) LIKE ?', ["%{$search}%"]);
                        });
                    })
                    ->get()->map(function ($route) {
                        return [
                            'id' => $route->id,
                            'key' => (string) $route->id,
                            'icon' => $route->icon,
                            'name' => $route->label,
                            'slug'  => $route->slug,
                            'created_by' => $route->created_by,
                            'roles' => $route->roles,
                            'is_active' => $route->is_active,
                            'is_submenu' =>  $route->is_submenu,
                            'component' => $route->component,
                            'data' => [
                                'name' => $route->label,
                                'route' => $route->route,
                                'created_by' => $route->created_by,

                            ],
                            'children' => $route->children->map(function ($child, $index) use ($route) {
                                $child->orderBy('order_no');
                                return [
                                    'id' => $child->id,
                                    'key' => "{$route->id}-{$index}",
                                    'icon' => $child->icon,
                                    'name' => $child->label,
                                    'created_by' => $child->created_by,
                                    'slug'  => $child->slug,
                                    'roles' => $child->roles,
                                    'is_active' => $child->is_active,
                                    'is_submenu' =>  $child->is_submenu,
                                    'component' => $child->component,
                                    'main_id' => [
                                        'id'    => $child->main_id,
                                        'name' => $route->label
                                    ],
                                    'data' => [
                                        'name' => $child->label,
                                        'route' => $child->route,
                                        'created_by' => $child->created_by,

                                    ],
                                ];
                            })->toArray(),
                        ];
                    });
                break;
        }
    }
}
