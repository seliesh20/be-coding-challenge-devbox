<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\LogsController;
use App\Repository\LogSyncRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LogSyncRepository::class)]
#[ApiResource(    
    collectionOperations:[
        'searchLogs' => [
            'method' => 'GET',
            'pagination_enabled' => false,
            'path' => '/count',      
            'controller' => LogsController::class,
            'openapi_context' =>[
                'parameters' => [
                    [
                        "name" => 'serviceNames[]',
                        "in" => 'query',
                        "description" => 'array of service names',
                        "required" => false, 
                        "style" =>  'form',
                        "explode"  => true,
                        "schema"   => [
                            "type" => "array",
                            "items" => [
                                "type" => "string"
                            ]
                        ]
                    ],
                    [
                        "name" => 'startDate',
                        "in" => 'query',
                        "description" => 'start date',
                        "required" => false, 
                        "style" =>  'form',
                        "explode"  => true,
                        "schema"   => [
                            "type" => "string",
                            "format" => "datetime"
                        ]
                    ],
                    [
                        "name" => 'endDate',
                        "in" => 'query',
                        "description" => 'end date',
                        "required" => false, 
                        "style" =>  'form',
                        "explode"  => true,
                        "schema"   => [
                            "type" => "string",
                            "format" => "datetime"
                        ]
                    ],
                    [
                        "name" => 'statusCode',
                        "in" => 'query',
                        "description" => 'filter on request status code',
                        "required" => false, 
                        "style" =>  'form',
                        "explode"  => true,
                        "schema"   => [
                            "type" => "integer"                            
                        ]
                    ],
                    
                ],
                "responses" => [
                    "200" => [
                        "description"=> "count of matching results",
                        "content"=> [
                            "application/json" => [
                                "schema" => [
                                    "required"=>["counter"],
                                    "type" => "object",
                                    "properties" => [
                                        "counter" => [
                                            "minimum"=> 0,
                                            "type"=> "integer"                                       
                                        ]
                                    ]
                                ],                                
                            ]
                        ] 
                    ],
                    "400" => [
                        "description" => "bad input parameter"
                    ]
                ]
            ]            
        ]    
    ],
    itemOperations:[]
)]
class LogSync
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]    
    private $id;

    #[ORM\Column(type: 'string', length: 50)]    
    private $request_name;

    #[ORM\Column(type: 'datetime')]
    private $request_date;

    #[ORM\Column(type: 'string', length: 10)]
    private $request_type;

    #[ORM\Column(type: 'string', length: 255)]
    private $request_url;

    #[ORM\Column(type: 'integer', length: 255)]
    private $result_code;  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequestName(): ?string
    {
        return $this->request_name;
    }

    public function setRequestName(string $request_name): self
    {
        $this->request_name = $request_name;

        return $this;
    }

    public function getRequestDate(): ?\DateTimeInterface
    {
        return $this->request_date;
    }

    public function setRequestDate(\DateTimeInterface $request_date): self
    {
        $this->request_date = $request_date;

        return $this;
    }

    public function getRequestType(): ?string
    {
        return $this->request_type;
    }

    public function setRequestType(string $request_type): self
    {
        $this->request_type = $request_type;

        return $this;
    }

    public function getRequestUrl(): ?string
    {
        return $this->request_url;
    }

    public function setRequestUrl(string $request_url): self
    {
        $this->request_url = $request_url;

        return $this;
    }

    public function getResultCode(): ?int
    {
        return $this->result_code;
    }

    public function setResultCode(int $result_code): self
    {
        $this->result_code = $result_code;

        return $this;
    }    
}
