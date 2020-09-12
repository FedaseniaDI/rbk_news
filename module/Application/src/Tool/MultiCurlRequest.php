<?php
namespace Application\Tool;

class MultiCurlRequest {
    public static array $options = [
        CURLOPT_PORT              => 80,
        CURLOPT_RETURNTRANSFER    => 1,
        //CURLOPT_BINARYTRANSFER    => 1,
        CURLOPT_CONNECTTIMEOUT    => 60,
        CURLOPT_TIMEOUT           => 120,
        CURLOPT_USERAGENT         => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36',
        CURLOPT_VERBOSE           => 2,
        CURLOPT_HEADER            => 0,
        CURLOPT_FOLLOWLOCATION    => 1,
        CURLOPT_MAXREDIRS         => 15,
        CURLOPT_AUTOREFERER       => 1,
        CURLOPT_HTTPHEADER        => [
            'Expect:',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
            'Accept-Charset: utf-8;q=0.7,*;q=0.5'
        ],
    ];

    public static function run($urls, array $options = null, callable $callback = null)
    {
        $mh = curl_multi_init();
        if ( $mh === false ) return false;

        $urls = (array) $urls;
        $options = $options ?? self::$options;
        $resourcesUrls = [];
        foreach ($urls as $url) {
            $ch = curl_init();
            $resourcesUrls[(string) $ch] = $url;
            curl_setopt_array($ch, $options);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_multi_add_handle($mh, $ch);
        }

        $results = [];
        $prev_running = $running = null;
        do {
            curl_multi_exec($mh, $running);

            if ( $running != $prev_running ) {
                $info = curl_multi_info_read($mh);

                if (is_array($info) && ($ch = $info['handle'])) {
                    $content = curl_multi_getcontent( $ch);
                    $origUrl = $resourcesUrls[(string) $ch];

                    if ($callback) {
                        $callback($origUrl, $content, $info['result'], $ch);
                    }
                    else {
                        $results[$origUrl] = [
                            'content' => $content,
                            'status' => $info['result'],
                            'status_text' => curl_error( $ch )
                        ];
                    }

                    curl_multi_remove_handle( $mh, $ch );
                    curl_close( $ch );
                }
                $prev_running = $running;
            }
        } while ($running > 0);

        curl_multi_close($mh);
        return $callback ?
            true :
            (
                count($results) == 1 ?
                $results[array_key_first($results)] :
                $results
            );
    }
}