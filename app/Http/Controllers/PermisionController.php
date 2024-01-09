<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermisionController extends Controller
{
    public function index()
    {
        return PermissionResource::collection(Permission::all()); 
    }
}
