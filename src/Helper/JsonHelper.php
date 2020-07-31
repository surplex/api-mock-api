<?php

namespace ApiMock\Helper;

class JsonHelper
{
    /**
     * Transforms an array to json
     * @param array $obj
     * @return string
     */
    public static function toJSON(array $obj): string
    {
        return json_encode($obj);
    }
}
