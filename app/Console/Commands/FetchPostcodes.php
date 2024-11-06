<?php

declare(strict_types=1);

namespace App\Console\Commands;

use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class FetchPostcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-postcodes {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to download UK Postcodes dataset';

    /**
     * Execute the console command.
     */
    //https://www.getthedata.com/downloads/open_postcode_geo.sql.zip
    public function handle(Client $client): int
    {
        $url = $this->argument('url');
        $request = new Request('GET', $url, ['headers' => ['Accept-Encoding' => 'zip']]);
        try {
            $promise = $client->sendAsync($request)->then(function ($response) {
                Storage::put('postcodes_sql.zip', $response->getBody()->getContents());
            });
            $promise->wait();
        } catch (TransferException $e) {
            logger()->error($e);
            return 1;
        }
        $zip = new ZipArchive();
        $res = $zip->open(Storage::disk('local')->path('postcodes_sql.zip'));
        if ($res !== true) {
            logger()->error('Failed to open file on disk.');
            return 2;
        }
        $res = $zip->extractTo(Storage::disk('local')->path(''));
        if ($res !== true) {
            logger()->error('Extraction unsuccesful.');
            return 3;
        }
        $zip->close();

        /**
         * To be testable, this section writing into the database could be extracted from this console command
         * into a domain command and then the domain command can be mocked - this is skipped due to time constraints
         */

        $postcodeSql = file_get_contents(Storage::disk('local')->path('open_postcode_geo.sql'));
        $statements = array_filter(array_map('trim', explode(';', $postcodeSql)));

        try {
            foreach ($statements as $stmt) {
                DB::statement($stmt);
            }
        } catch (\Exception $e) {
            logger()->error($e);
            return 4;
        }

        return 0;
    }
}
