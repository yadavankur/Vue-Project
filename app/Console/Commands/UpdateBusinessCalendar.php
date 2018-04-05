<?php

namespace App\Console\Commands;

use App\Models\Entities\BusinessCalendarHoliday;
use App\Models\Entities\State;
use App\Models\Entities\User;
use App\Models\Utils\UtilsAbstract;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use DB;
use Illuminate\Support\Facades\Auth;

class UpdateBusinessCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateBusinessCalendar:updateHolidays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get latest public holidays of each state from government site.';

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
        try
        {
            $this->info('Start updating public holidays at [' . Carbon::now()->format('Y-m-d H:i:s.u') . '].');
            $json = self::getLatestPublicHolidays();
            self::updatePublicHolidays($this, $json);
            $this->info('End updating public holidays at [' . Carbon::now()->format('Y-m-d H:i:s.u') . '].');
        }
        catch (Exception $ex)
        {
            $this->info($ex->getTraceAsString());
        }
    }

    private static function getLatestPublicHolidays()
    {
        $url  = 'http://www.australia.gov.au/about-australia/special-dates-and-events/public-holidays/xml';
        $data = UtilsAbstract::readUrl($url);
        $xml_snippet = simplexml_load_string($data);
        $json_convert = json_encode($xml_snippet);
        $json = json_decode($json_convert, true);
        return $json;
    }
    private static function updatePublicHolidays($command, $jsonData)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            if ($user instanceof User)
                $userId = $user->id;
            else
                $userId = env('APP_SYSTEM_USER_ID');

            $items = $jsonData['jurisdiction'];
            $bar = $command->output->createProgressBar(count($items));
            foreach($items as $item)
            {
                $stateName = strtoupper(trim($item['jurisdictionName']));
                $state = State::with('locations')->where('name', $stateName)->where('active', 1)->first();
                if (!$state instanceof State)
                {
                    //skip
                    continue;
                }
                // delete all first

                //$updateDate = trim($item['dateUpdated']);
                $events = $item['events']['event'];
                $publicHolidays = [];
                $years = [];
                foreach($events as $event)
                {
                    //$stateName = strtoupper(trim($event['@attributes']['jurisdiction']));
                    $year = trim($event['@attributes']['year']);
                    $years[$year] = $year;
                    $date = trim($event['date']);
                    $holidayTitle = trim($event['holidayTitle']);
                    $currentDateTime = Carbon::now();
                    foreach($state->locations as $location)
                    $publicHolidays[] = array(
                        'state_id' => $state->id,
                        'location_id' => $location->id,
                        'year' => $year,
                        'type' => BusinessCalendarHoliday::TYPE_PUBLIC_HOLIDAY,
                        'value' => $date,
                        'description' => $holidayTitle,
                        'created_at' => $currentDateTime,
                        'updated_at' => $currentDateTime,
                        'created_by' => $userId,
                        'updated_by' => $userId
                    );
                }
                // delete old ones
                self::deletePublicHolidays($state->id, $years);
                // add new ones
                self::addPublicHolidays($publicHolidays);
                $bar->advance();
            }
            $bar->finish();
            $command->info(''); // insert new line
            DB::commit();
        }
        catch (Exception $ex)
        {
            DB::rollback();
            throw $ex;
        }
    }
    public static function deletePublicHolidays($stateId, $years)
    {
        $deletedPublicHolidays = BusinessCalendarHoliday::where('state_id', $stateId)
            ->wherein('year', $years)
            ->where('type', BusinessCalendarHoliday::TYPE_PUBLIC_HOLIDAY)
            ->where('active', 1)->update(['active' => 0]);
        return $deletedPublicHolidays;
    }
    public static function addPublicHolidays($publicHolidays)
    {
        BusinessCalendarHoliday::insert($publicHolidays);
    }
}
