<?php


namespace App\Lib\Es;


use EasySwoole\Core\Component\Di;

class EsBase
{
    public $esClient = null;

    public function __construct ()
    {
        $this -> esClient = Di ::getInstance() -> get('ES');
    }

    /**
     * description
     * @param $name
     * @return array
     */
    public function searchByName ($name)
    {
        $name = trim($name);
        if (empty($name)) {
            return [];
        }
        $params = [
            'index' => $this -> index,
            'type' => $this -> type,
            'body' => [
                'query' => [
                    $this -> type => [
                        'name' => $name
                    ]
                ]
            ]
        ];

        $res = $this -> esClient -> search($params);

        return $res;
    }
}