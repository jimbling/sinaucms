<?php

namespace Modules\Formulir\Services;

use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_Spreadsheet;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_ClearValuesRequest;
use Modules\Formulir\Models\Form;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GoogleSheetService
{
    protected function getClient($user)
    {
        $client = new \Google_Client();
        $client->setApplicationName('SinauCMS Formulir Integration');
        $client->setAccessType('offline');
        $client->setScopes(['https://www.googleapis.com/auth/spreadsheets']);

        //  set langsung client id & secret (bukan JSON)
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));

        //  set access token saja
        $client->setAccessToken([
            'access_token' => $user->google_token,
            'expires_in'   => 3600,
            'created'      => time() - 3600
        ]);

        //  refresh token pakai method khusus
        if ($user->google_refresh_token) {
            $client->refreshToken($user->google_refresh_token);
        }

        //  cek & refresh jika expired
        if ($client->isAccessTokenExpired()) {
            $newToken = $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
            if (!empty($newToken['access_token'])) {
                $user->google_token = $newToken['access_token'];
                $user->save();
            }
        }

        return $client;
    }







    public function connect(Form $form)
    {
        $user = Auth::user();

        // 🚨 CEK apakah user sudah menghubungkan akun Google
        if (!$user->google_id || !$user->google_token) {
            return redirect()->back()->with('error', 'Silakan hubungkan akun Google Anda terlebih dahulu sebelum menghubungkan Google Sheet.');
        }

        //  Jika sudah terhubung, baru lanjut proses Google Sheet
        $client = $this->getClient($user);
        $service = new Google_Service_Sheets($client);

        // Buat Spreadsheet Baru
        $spreadsheet = new Google_Service_Sheets_Spreadsheet([
            'properties' => ['title' => 'Formulir - ' . $form->title]
        ]);
        $createdSheet = $service->spreadsheets->create($spreadsheet);

        $form->google_sheet_id = $createdSheet->spreadsheetId;
        $form->save();

        // Header Pertanyaan
        $headers = $form->questions->pluck('question_text')->toArray();
        $headerRow = new Google_Service_Sheets_ValueRange([
            'values' => [array_merge(['Tanggal Submit'], $headers)]
        ]);
        $service->spreadsheets_values->update(
            $form->google_sheet_id,
            'Sheet1!A1',
            $headerRow,
            ['valueInputOption' => 'RAW']
        );

        $this->syncResponses($form, $service);

        return redirect()->back()->with('success', 'Google Sheet berhasil dihubungkan.');
    }


    public function sync(Form $form)
    {
        $user = Auth::user();
        $client = $this->getClient($user);
        $service = new Google_Service_Sheets($client);

        $sheetHeaders = $service->spreadsheets_values->get($form->google_sheet_id, 'Sheet1!A1:Z1')->getValues()[0] ?? [];
        $expectedHeaders = array_merge(['Tanggal Submit'], $form->questions->pluck('question_text')->toArray());

        if ($sheetHeaders !== $expectedHeaders) {
            $service->spreadsheets_values->clear(
                $form->google_sheet_id,
                'Sheet1',
                new Google_Service_Sheets_ClearValuesRequest()
            );

            $headerRow = new Google_Service_Sheets_ValueRange([
                'values' => [$expectedHeaders]
            ]);
            $service->spreadsheets_values->update(
                $form->google_sheet_id,
                'Sheet1!A1',
                $headerRow,
                ['valueInputOption' => 'RAW']
            );
        }

        $this->syncResponses($form, $service);
    }

    protected function syncResponses(Form $form, Google_Service_Sheets $service)
    {
        $form->load(['responses.answers']);

        $rows = [];

        foreach ($form->responses as $response) {
            $row = [$response->created_at->format('Y-m-d H:i:s')];

            foreach ($form->questions as $question) {
                $answer = $response->answers
                    ->where('question_id', $question->id)
                    ->pluck('answer')
                    ->map(function ($a) {
                        $decoded = json_decode($a, true);
                        return is_array($decoded) ? implode(', ', $decoded) : $a;
                    })
                    ->implode(', ');

                $row[] = $answer;
            }

            $rows[] = $row;
        }

        if (!empty($rows)) {
            $range = 'Sheet1!A2:' . chr(65 + count($form->questions)) . (count($rows) + 1);
            $dataBody = new Google_Service_Sheets_ValueRange([
                'values' => $rows
            ]);
            $service->spreadsheets_values->update(
                $form->google_sheet_id,
                $range,
                $dataBody,
                ['valueInputOption' => 'RAW']
            );
        }

        Log::debug('Jumlah responses disinkronkan: ' . $form->responses->count());
    }
}
