<?php


if (!function_exists('make_curl_request')) {
    function make_curl_request($url, $headers = [], $method = 'GET', $data = null)
    {
        $session_user_id = session()->get('session_user_id');
        $headers[] = 'Authorization1: ' . $session_user_id;
        // print_r($headers);exit;

        // Initialize cURL session
        $curl = curl_init();

        // Set cURL options common for all request methods
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true, // Follow redirects, if any
            CURLOPT_HTTPHEADER => $headers,
        ];
        // print_r($options);
        // exit;

        // Set request method-specific options
        if ($method === 'POST') {
            $options[CURLOPT_POST] = true;
            if ($data !== null) {
                $options[CURLOPT_POSTFIELDS] = $data;
            }
        } elseif ($method === 'PUT') {
            $options[CURLOPT_CUSTOMREQUEST] = 'PUT';
            if ($data !== null) {
                $options[CURLOPT_POSTFIELDS] = $data;
            }
        } elseif ($method === 'GET') {
            // GET method doesn't require additional options
        } elseif ($method === 'DELETE') {
            $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
            // GET method doesn't require additional options
        } else {
            // Handle unsupported request methods
            return ['error' => 'Unsupported request method'];
        }

        curl_setopt_array($curl, $options);

        // Execute the cURL request
        $response = curl_exec($curl);

        // Check for errors
        if ($response === false) {
            $error = curl_error($curl);
            // Handle error
            return ['error' => $error];
        }

        // Close cURL session
        curl_close($curl);
        //echo '<pre>';print_r($response);exit;
        if ($response == 'Access denied') {

            redirect()->route('user_login')->send();
            exit();
        }

        // Return the response
        return $response;
    }
}

if (!function_exists('array_column_recursive')) {
    function array_column_recursive(array $array, $column_key)
    {
        $result = [];
        foreach ($array as $sub_array) {
            if (array_key_exists($column_key, $sub_array)) {
                $result[] = $sub_array[$column_key];
            } elseif (is_array($sub_array)) {
                $result = array_merge($result, array_column_recursive($sub_array, $column_key));
            }
        }
        return $result;
    }
}
