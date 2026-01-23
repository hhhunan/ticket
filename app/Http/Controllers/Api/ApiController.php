<?php

namespace App\Http\Controllers\Api;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
#[\OpenApi\Attributes\Info(
    version: "1.0.0",
    description: "API documentation for the Ticket system",
    title: "Ticket API",
)]
class ApiController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}