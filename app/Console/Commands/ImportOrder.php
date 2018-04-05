<?php

namespace App\Console\Commands;


use App\Models\Entities\SystemSetting;
use App\Models\Entities\V6Quote;
use App\Models\Repositories\CpmOrderRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

/**
 * Not used any more
 * Changed to import the order on the fly
 * Class ImportOrder
 * @package App\Console\Commands
 */
class ImportOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ImportOrder:createCPMActivities {--location=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import new orders from V6 and create sequencing activities';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            // not used any more
            // importing begins
            $this->info('Start importing new orders at [' . Carbon::now()->format('Y-m-d H:i:s.u') . '].');
            // 1) get new orders from V6 based on the last sync time
            // 2) if there are new orders, then import them and create CPM activities for them
            //    then update last sync time
            // 3) if no, return
            $locations = $this->option('location');
            $lastImportedTime = '2017-06-03 00:00:00';
            $newOrders = $this->getNewOrders($locations, $lastImportedTime);
            $orderActivities = $this->createCPMActivities($newOrders);
            $this->info(count($newOrders) . ' orders have been imported successfully!');
            // importing ends
            $this->info('End importing orders at [' . Carbon::now()->format('Y-m-d H:i:s.u') . '].');
        }
        catch (Exception $ex)
        {
            $this->info($ex->getTraceAsString());
        }
    }
    private function getNewOrders($locations, $lastImportedTime = '')
    {
        if (!trim($lastImportedTime))
        {
            $lastImportedTime = SystemSetting::getSettings(SystemSetting::LAST_IMPORTED_TIME);
        }
        // use which field? QUOTE_DATE? LAST_MODIFY_DATE? DOWNLOAD_DATE? or ???
        // $query = V6Quote::where('LAST_MODIFY_DATE', '>=', $lastImportedTime);
        $query = V6Quote::where('QUOTE_NUM', '41218') // for test
                ->join('locations', 'locations.abbreviation', '=', 'V_V6_QUOTE.QUOTE_NUM_PREF', 'left outer')
                ->with('location');
        if (count($locations) > 0)
        {
            $query->wherein('QUOTE_NUM_PREF', $locations);
        }
        $newOrders = $query->get();
        return $newOrders;
    }
    private function createCPMActivities($newOrders)
    {
        $orderActivities = [];
        $bar = $this->output->createProgressBar(count($newOrders));
        $baseStartDate = Carbon::today();
        foreach($newOrders as $newOrder)
        {
            $orderActivities[] = CpmOrderRepository::generateCPMActivities($baseStartDate, $newOrder->QUOTE_ID, $newOrder->location->id, $newOrder->UDF1);
            // update last imported time
            SystemSetting::addSettings(SystemSetting::LAST_IMPORTED_TIME, $newOrder->LAST_MODIFY_DATE);
            $bar->advance();
        }
        $bar->finish();
        $this->info(''); // insert new line
        return $orderActivities;
    }
}
