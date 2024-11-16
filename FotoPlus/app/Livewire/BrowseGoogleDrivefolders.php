<?php

namespace App\Livewire;

use App\Models\GoogleToken;
use App\Services\GoogleService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class BrowseGoogleDrivefolders extends Component
{
    public $arquivos = [];
    public $idPasta = 'root';
    public $historicoPastas = [];
    public $caminhoPasta = '';
    public $redirectUrl = null;
    public $retonarRota;

    // Função construtora do componente "PastaDownloadServidor".
    public function mount($retonarRota = 'dashboard')
    {
        $this->retonarRota = $retonarRota;
        $this->listarArquivos();
        $this->caminhoPasta = $this->obterCaminhoAtualPasta();
    }

    // Função render do componente "PastaDownloadServidor".
    public function render()
    {
        return view('livewire.browse-google-drivefolders', [
            'arquivos' => $this->arquivos,
        ]);
    }

    // Função responsavel em listar todos arquivos da pasta selecionada
    // do Google Drive.
    public function listarArquivos()
    {
        $googleServico = new GoogleService();
        $cliente = $googleServico->getClient();
        $googleToken = GoogleToken::where('user_id', Auth::id())->first();

        if ($googleToken) {
            $cliente->setAccessToken($googleToken->toArray());
            $drive = $googleServico->getDriveService();

            $query = "'{$this->idPasta}' in parents and (mimeType contains 'application/vnd.google-apps.folder' or mimeType contains 'image/jpeg' or mimeType contains 'image/png' or mimeType contains 'image/gif')";
            $resultados = $drive->files->listFiles([
                'q' => $query,
                'fields' => 'files(id, name, mimeType, webViewLink)'
            ]);

            $this->arquivos = array_map(fn($arquivo) => [
                'id' => $arquivo->getId(),
                'nome' => $arquivo->getName(),
                'tipoMime' => $arquivo->getMimeType(),
                'linkVisualizacao' => $arquivo->getWebViewLink(),
            ], $resultados->getFiles());
        } else {
            return redirect($cliente->createAuthUrl());
        }
    }

    public function obterCaminhoAtualPasta()
    {
        $googleServico = new GoogleService();
        $cliente = $googleServico->getClient();
        $googleToken = GoogleToken::where('user_id', Auth::id())->first();
        if ($googleToken) {
            $cliente->setAccessToken($googleToken->toArray());
            $drive = $googleServico->getDriveService();

            $caminhoPasta = '';
            $idPasta = $this->idPasta;
            while ($idPasta != 'root') {
                $pasta = $drive->files->get($idPasta, ['fields' => 'id, name, parents']);
                $caminhoPasta = $pasta->name . '/' . $caminhoPasta;
                $idPasta = $pasta->parents[0] ?? 'root';
            }
            return '/' . trim($caminhoPasta, '/');
        } else {
            return redirect($cliente->createAuthUrl());
        }
    }

    public function alterarPasta($idPasta)
    {
        array_push($this->historicoPastas, $this->idPasta);
        $this->idPasta = $idPasta;
        $this->listarArquivos();
        $this->caminhoPasta = $this->obterCaminhoAtualPasta();
    }

    // Função responsavel em selecionar a pasta com todos arquivos do
    // Google Drive, para depois realizar o download da pasta para
    // realizar a rotina python.
    public function selecionar()
    {
        session()->put('caminhoPastaGoogleDrive',  $this->idPasta);
        return redirect()->route($this->retonarRota);
    }

    // Função responsavel em voltar uma pasta da pasta atual para
    // realizar a navegação entre pastas.
    public function voltar()
    {
        if (!empty($this->historicoPastas)) {
            $this->idPasta = array_pop($this->historicoPastas);
            $this->listarArquivos();
            $this->caminhoPasta = $this->obterCaminhoAtualPasta();
        }
    }
}

