<?php
namespace App\Services\Api;

class ApiVersion
{
    private static $valid_api_versions = [
        "1.0" => 'master',
        "2.0" => 'v2',
    ];

    /**
     * Resolve the requested api version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return integer
     */
    public static function get($request) {
        return $request->header('protocol');
    }

    /**
     * Determines if a version is valid or not
     *
     * @param integer $apiVersion
     * @return bool
     */
    public static function isValid($apiVersion) {
        return in_array(
            $apiVersion,
            array_keys(self::$valid_api_versions)
        );
    }

    /**
     * Resolve namespace for a api version
     *
     * @param integer $apiVersion
     * @return string
     */
    public static function getNamespace($apiVersion)
    {
        if (!self::isValid($apiVersion)) {
            return null;
        }
        return self::$valid_api_versions[$apiVersion];
    }
}
