<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use App\Models\UserModel;
use App\Libraries\JwtLibrary;
use Config\Services;


class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // echo "hii";
        //try {
            
            //session()->get('access_token');exit;
            $jwtLib = new JwtLibrary(); // Instantiate your JWT library
            $bearer_token = $jwtLib->get_bearer_token();
            $is_jwt_valid = $jwtLib->is_jwt_valid($bearer_token);

            if ($is_jwt_valid) {
                return $request;
            } else {
                $response = service('response');
                $response->setBody('Access denied');
                $response->setStatusCode(401);
                return $response;
            }
        // } catch (Exception $e) {
        //     log_message('error', 'Error in AuthFilter: ' . $e->getMessage());
        //     return redirect()->to('/error-page'); // Redirect to an error page
        // }
    }


    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
