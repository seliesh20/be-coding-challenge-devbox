<?php 

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class LogsCount 
{

    public function countItems(Request $request)
    {
        return [            
            'counter' => 0,            
            'request' => $request->query->get('serviceName')
        ];
    }    
}