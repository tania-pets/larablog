<?php

/**
* @SWG\Swagger(
*     schemes={"http"},
*     basePath="/api",
* 		produces={"application/json"},
* 		consumes={"application/json"},
*     @SWG\Info(
*         version="1.0.0",
*         title="Blog API",
*         description="Api specification",
*         termsOfService="",
*         @SWG\Contact(
*             email="tania.pets@gmail.com"
*         )
*     ))
*    @SWG\Definition(
*       definition="Error",
*       @SWG\Property(property="message", type="string"),
*       ),
*/


namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
