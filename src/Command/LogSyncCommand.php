<?php

namespace App\Command;

use App\Entity\LogSync;
use App\Repository\LogSyncRepository;
use DateTime;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Log Sync Command
 */
#[AsCommand(
    name: 'app:log-sync',
    description: 'To parse and insert logs to database',
    hidden: false
 )] 
class LogSyncCommand extends Command 
{
    
    protected static  $defaultName        = 'app:log-sync';
    protected static  $defaultDescription = 'To parse and insert logs to database';
    
    /** @var LogSyncRepository $logSyncRepository */
    private $logSyncRepository;

    /**
     * Constructor Function
     *
     * @param LogSyncRepository $logSyncRepository
     */
    public function __construct(LogSyncRepository $logSyncRepository)
    {
        parent::__construct();
        $this->logSyncRepository = $logSyncRepository;
    }
    /**
     * Configure Function
     *
     * @return void
     * */                 
    protected function configure()
    {
        $this->setHelp("To parse and insert logs to database")
            ->addArgument('filename', InputArgument::REQUIRED, 'Log file to be parsed from log folder');        
    }

    /**
     * Execute Function
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');
        if(!file_exists('var/log/'.$filename)){
            $io->error("app/var/log/".$filename." not exists!!");
            return Command::FAILURE;
        }

        $handle = fopen('var/log/'.$filename, 'r');
        $insertLines = 0;
        $lineNumber = 1;
                

        while(!feof($handle)){
            $stringtext = fgets($handle);
            //request_name
            $request_name = explode(' -', $stringtext)[0];
            
            //DateTime
            $array_pos_start = strpos($stringtext, "[") + 1;
            $array_pos_end = strpos($stringtext, "]", $array_pos_start) - 1;            
            $request_date = new DateTime();
            $request_date->setTimestamp(strtotime(
                substr($stringtext, 
                    $array_pos_start, 
                    ($array_pos_end - $array_pos_start)
                )
            ));

            //Request type, method, result cod
            $quotes_pos_start = strpos($stringtext, '"') + 1;
            $quotes_pos_end = strpos($stringtext, '"', $quotes_pos_start) - 1;
            $quotes_string_array = explode(" ", 
                substr($stringtext, 
                    $quotes_pos_start, 
                    ($quotes_pos_end-$quotes_pos_start)
                )
            );
            $request_type = $quotes_string_array[0];
            $request_url = $quotes_string_array[1];            
            $result_code = (int) substr($stringtext, $quotes_pos_end+2);


            //check data availble
            if (is_null($this->logSyncRepository->findOneBy([
                'request_name' => $request_name,
                'request_date' => $request_date,
                'request_type' => $request_type,
                'request_url'  => $request_url,
                'result_code'  => $result_code
            ]))) {
                //save LogSync
                $logSyncEntity  = new LogSync();
                $logSyncEntity->setRequestName($request_name);
                $logSyncEntity->setRequestDate($request_date);
                $logSyncEntity->setRequestType($request_type);
                $logSyncEntity->setRequestUrl($request_url);
                $logSyncEntity->setResultCode($result_code);
                $this->logSyncRepository->add($logSyncEntity, true);
                $insertLines++;
            }
            $lineNumber++;        
        }        

        $io->info(sprintf("Total lines %s", $lineNumber));
        $io->info(sprintf("Inserted lines %s", $insertLines));
        $io->info(sprintf("Skipped lines %s", $lineNumber - $insertLines));
        return Command::SUCCESS;
    }
}