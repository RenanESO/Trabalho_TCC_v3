<?php

namespace App\Services;

class DownloadController
{
    public function download($user_id)
    {
        $zipFilePath = storage_path('app\\public\\' .$user_id .'\\resultado.zip');

        if (!file_exists($zipFilePath)) {
            abort(404, 'Arquivo ZIP não encontrado.');
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    public function downloadAndRedirect($user_id)
    {
        $zipFilePath = storage_path('app\\public\\' .$user_id .'\\resultado.zip');

        if (!file_exists($zipFilePath)) {
            abort(404, 'Arquivo ZIP não encontrado.');
        }

        // Realiza o download
        //return response()->download($zipFilePath)->deleteFileAfterSend(true)->header('Refresh', '0;url=' . route('duplicity'));
    }
}
