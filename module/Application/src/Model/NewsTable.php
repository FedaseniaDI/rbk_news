<?php
namespace Application\Model;

use Application\Model\Base\Table;
use Application\Tool\RbkParser;
use Application\Tool\MultiCurlRequest;

class NewsTable extends Table
{
    public string $rbkUrl = 'http://www.rbc.ru';

    public function syncNews(string $url = null)
    {
        $newsFeedsResponse = MultiCurlRequest::run($this->rbkUrl);
        if($newsFeedsResponse && !empty($newsFeedsResponse['content'])) {
            $newsFeedsData = RbkParser::parseNewsFeeds($newsFeedsResponse['content']);
            $urls = array_column($newsFeedsData, 's_url');
            $results = MultiCurlRequest::run($urls);
            if(is_array($results) && !empty($results)) {
                foreach ($results as $url => $result) {
                    if(!empty($result['content']) && !empty($newsFeedsData[$url])) {
                        $newsData = RbkParser::parseOneNews($result['content']);
                        $newsFeedsData[$url]['text_overview'] = $newsData['text_overview'];
                        $newsFeedsData[$url]['img_src'] = $newsData['img_src'];
                        $newsFeedsData[$url]['img_description'] = $newsData['img_description'];
                        $newsFeedsData[$url]['img_author'] = $newsData['img_author'];
                        $newsFeedsData[$url]['content'] = $newsData['content'];
                        $newsFeedsData[$url]['author'] = $newsData['author'];
                        $newsFeedsData[$url]['title'] = $newsData['title'];
                    }
                }
            }
            if(!empty($newsFeedsData)) {
                $this->tableGateway->delete([]);

                foreach ($newsFeedsData as $newsFeedData) {
                    $model = new News($newsFeedData);
                    $this->saveRow($model);
                }
            }
        }

        return true;
    }
}