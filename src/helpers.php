<?php
if (!function_exists('dataRecodes')) {
    function dataRecodes($title, $data, $path)
    {
        $handler = fopen(dirname(dirname(__FILE__)) . '/logs/' . $path . '/oauth' . date('Y-m-d', time()) . '.log', 'a+');
        $content = "================" . $title . "===================\n";
        if (is_string($data) === true) {
            $content .= $data . "\n";
        }
        if (is_array($data) === true) {
            forEach ($data as $k => $v) {
                if (is_array($v)) {
                    $v = json_encode($v);
                }
                $content .= "key: " . $k . " value: " . $v . "\n";
            }
        }
        if (is_bool($data) === true) {
            if ($data) {
                $content .= "true\n";
            } else {
                $content .= "false\n";
            }
        }
        $flag = fwrite($handler, $content);
        fclose($handler);

        return $flag;
    }
}

/*
 * 解析头部信息;
 */
if (!function_exists('http_parse_headers')) {
    function http_parse_headers($raw_headers)
    {
        $headers = [];
        $key     = ''; // [+]

        foreach (explode("\n", $raw_headers) as $i => $h) {
            $h = explode(':', $h, 2);

            if (isset($h[1])) {
                if (!isset($headers[$h[0]])) {
                    $headers[$h[0]] = trim($h[1]);
                } elseif (is_array($headers[$h[0]])) {
                    // $tmp = array_merge($headers[$h[0]], array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge($headers[$h[0]], [trim($h[1])]); // [+]
                } else {
                    // $tmp = array_merge(array($headers[$h[0]]), array(trim($h[1]))); // [-]
                    // $headers[$h[0]] = $tmp; // [-]
                    $headers[$h[0]] = array_merge([$headers[$h[0]]], [trim($h[1])]); // [+]
                }

                $key = $h[0]; // [+]
            } else {
                // [+]
                // [+]
                if (substr($h[0], 0, 1) == "\t") {
                    // [+]
                    $headers[$key] .= "\r\n\t" . trim($h[0]);
                } // [+]
                elseif (!$key) {
                    // [+]
                    $headers[0] = trim($h[0]);
                }
                trim($h[0]); // [+]
            } // [+]
        }

        return $headers;
    }
}