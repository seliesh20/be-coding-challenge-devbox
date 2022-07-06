<?php

namespace App\Controller;

use App\Service\LogsCount;
use App\Repository\LogSyncRepository;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class LogsController extends AbstractController
{
    /** @var LogSyncRepository $logSyncRepository */
    private $logSyncRepository;

    /** @var LogsCount $logsCount */
    private $logsCount;

    /**
     * Constructor Function
     *
     * @param LogSyncRepository $logSyncRepository
     */
    public function __construct(
        LogSyncRepository $logSyncRepository,
        LogsCount $logsCount
    ){        
        $this->logSyncRepository = $logSyncRepository;
        $this->logsCount = $logsCount;
    }

    /*#[Route('/count', name: 'app_logs')]*/  
    /**
     * Index Function
     *
     * @param Request $request
     * @return JsonResponse
     */  
    public function index(Request $request): JsonResponse
    {
        $service_names = $request->query->get('serviceNames');
        $start_date = $request->query->get('startDate');
        $end_date = $request->query->get('endDate');
        $status_code = $request->query->get('statusCode');
        $criteria = Criteria::create();         
        if(!is_null($service_names) && is_array($service_names) && count($service_names)){
            $criteria->andWhere(Criteria::expr()->in('request_name', $service_names));
        }        
        if(!is_null($start_date) && DateTime::createFromFormat('Y-m-d H:i:s', $start_date) !== false){            
            
            $criteria->andWhere(Criteria::expr()->gte('request_date', DateTime::createFromFormat('Y-m-d H:i:s', $start_date)));            
        }   
        if(!is_null($end_date) && DateTime::createFromFormat('Y-m-d H:i:s', $end_date) !== false){
            $criteria->andWhere(Criteria::expr()->lte('request_date', DateTime::createFromFormat('Y-m-d H:i:s', $end_date)));            
        }
        if(!is_null($status_code)){
            $criteria->andWhere(Criteria::expr()->eq('result_code', $status_code));            
        }
        $count = $this->logSyncRepository->countBy($criteria);

        return $this->json([            
            'counter' => $count
        ]);
    }    

    public function __invoke(Request $request): Response
    {
        return  $this->index($request);
    }
}
