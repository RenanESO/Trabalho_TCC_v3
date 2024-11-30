<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Exception;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class Duplicity extends Component 
{ 
    public $login_id_usuario;

    public $caminho_deteccao_python_exe;
    public $caminho_compilador_python;
    public $caminho_deteccao_python;

    public $caminho_pasta_public;
    public $caminho_arquivo_log;
    public $caminho_resultado;

    public $filtro_data_inicial;
    public $filtro_data_final;
    public $filtro_copiar_recortar;

    public $habilitar_data; 

    public $nome_botao_log; 

    public $achouArquivoZip = False;

    // Função construtora da pagina no blade "Duplicidade".
    public function mount()
    {
        // Definindo a variavel com o ID Usuario que esta logado.
        $this->login_id_usuario = Auth::id();
        
        // Definindo as variaveis com os caminhos do compilador e aplicação Python.
        $this->caminho_deteccao_python_exe = storage_path('app/public/deteccao/dist/principal'); 
        $this->caminho_deteccao_python = storage_path('app/public/deteccao/principal.py'); 
        
        // Definindo as variaveis com os caminhos dos arquivos e diretórios.
        $this->caminho_pasta_public = storage_path('app/public');
        $this->caminho_arquivo_log = storage_path('app/public/' .$this->login_id_usuario .'/log.txt');
        $this->caminho_resultado = storage_path('app/public/' .$this->login_id_usuario .'/resultado'); 

        // Definindo as variaveis para realizar a rotina de duplicidade.       
        $this->filtro_data_inicial = now()->toDateString();
        $this->filtro_data_final = now()->toDateString();
        $this->filtro_copiar_recortar = 'copiar';

        // Definindo as variaveis referentes aos status dos edits dos filtros.
        $this->habilitar_data = 'disabled';

        // Definindo a variavel com o nome do botão do resultado da rotina de duplicidade.
        $this->nome_botao_log = 'Leia mais';

        // Definindo a variavel se encontrou algum arquivo de resultado para zipar.
        $this->achouArquivoZip = False;
    }

    // Função render da pagina no blade "Duplicidade".
    public function render() 
    {
        return view('livewire.duplicity');   
    }

    // Função responsavel em mostrar a mensagem do arquivo log maximizado.
    public function mostrarLogMaximizado() 
    {
        try {
            if (file_exists($this->caminho_arquivo_log))  {
                $texto_completo_log = implode(" | ", file($this->caminho_arquivo_log)); 
                $this->nome_botao_log = 'Leia menos'; 
                session()->flash('log', $texto_completo_log);
            } else {
                session()->flash('log', 'O arquivo log.txt sem resposta ou não existe. Caminho: ' .$this->caminho_arquivo_log);
            }  
                    
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "mostrarLogMaximizado". Erro: ' .$e->getMessage());
            return redirect()->route('duplicity');   
        }
    }   

    // Função responsavel em mostrar a mensagem do arquivo log minimizado.
    public function mostrarLogMinimizado() 
    {
        try {
            if (file_exists($this->caminho_arquivo_log)) {
                $texto_completo_log = file($this->caminho_arquivo_log); 
                if (count($texto_completo_log) >= 2) {  
                    $texto_penultima_linha_log = $texto_completo_log[count($texto_completo_log) - 1];
                } else {
                    $texto_penultima_linha_log = $texto_completo_log;
                }
                $this->nome_botao_log = 'Leia mais'; 
                session()->flash('log', $texto_penultima_linha_log);  
            } else {
                session()->flash('log', 'O arquivo log.txt sem resposta ou não existe. Caminho: ' .$this->caminho_arquivo_log);
            }  
            
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "mostrarLogMinimizado". Erro: ' .$e->getMessage());
            return redirect()->route('duplicity');    
        }
    }   

    // Função responsavel em alterar o tamanho da mensagem do arquivo log.
    public function alterarTamanhoLog() 
    {
        try {
            if ($this->nome_botao_log == 'Leia mais') {            
                $this->mostrarLogMaximizado();
            } else {          
                $this->mostrarLogMinimizado();
            }
            
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "alterarTamanhoLog". Erro: ' .$e->getMessage());
            return redirect('duplicity');   
        }
    } 
    
    // Função responsavel em alterar o status do campo "Data" 
    // para habilitar|desabilitar o edit.
    public function alterarStatusData() {
        try {
            if ($this->habilitar_data == '') {
                $this->habilitar_data = 'disabled';    
            } else {
                $this->habilitar_data = '';     
            }

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "alterarStatusData". Erro: ' .$e->getMessage());
            return redirect()->route('organizar'); 
        }
    }  

    // Função responsavel em realizar a verificação de duplicidade no conjunto de fotos  
    // selecionado, retornando as possiveis fotos iguais em outra pasta.
    public function verificaDuplicidade() 
    {
        try {
            session()->forget(['log', 'error', 'debug']);

            // Verifica se ID do usuário está definido.
            if (!$this->login_id_usuario) {
                session()->flash('error', 'Não foi encontrado o ID do usuário logado.');
                return redirect()->route('duplicity');   
            }

            // Verifica se o caminho da pasta do Google Drive está definido.
            if (!session('caminhoPastaGoogleDrive')) {
                session()->flash('error', 'Não foi encontrado a pasta selecionada do Google Drive.');
                return redirect()->route('duplicity');                  
            }

            // Verifica se o diretório do usuário existe. 
            if (!Storage::disk('public')->exists($this->login_id_usuario)) {
                Storage::disk('public')->makeDirectory($this->login_id_usuario);
            }

            // Verifica se foi preenchido os campos de parâmetros.
            if ($this->habilitar_data == '') {
                $this->filtro_data_inicial = date('d/m/Y', strtotime($this->filtro_data_inicial));
                $this->filtro_data_final = date('d/m/Y', strtotime($this->filtro_data_final));
            } else {
                $this->filtro_data_inicial = 'None';
                $this->filtro_data_final = 'None';
            }

            $parametros = [
                'duplicidade',                      // Parametro referente a rotina de duplicidade que será realizada no python.
                $this->caminho_pasta_public,        // Parametro referente ao caminho da pasta public.
                $this->login_id_usuario,            // Parametro referente ao ID do usuario logado.
                session('caminhoPastaGoogleDrive'), // Parametro referente ao ID Pasta Google Drive
                $this->filtro_data_inicial,         // Parametro referente a data inicial do conjunto das fotos. 
                $this->filtro_data_final,           // Parametro referente a data final do conjunto das fotos. 
                $this->filtro_copiar_recortar       // Parametro referente se as fotos devem ser copiadas ou recortadas.
            ];

            // Chamada externa do python para realizar a organização das fotos referente 
            // aos filtros selecionados.
            $comando = $this->caminho_deteccao_python_exe .' ' .implode(' ', $parametros);
            //session()->flash('debug', 'Comando: ' .$comando); 
            
            $comando = escapeshellcmd($comando);
            $cmdResult = shell_exec($comando);

            // Mostra o conteudo do arquivo log minimizado.
            $this->mostrarLogMinimizado();

            $this->criarZip();

            if ($this->achouArquivoZip == True) {
                // Redireciona para a rota de download
                return redirect()->route('download-zip', ['user_id' => $this->login_id_usuario]); 
            }
            else {
                session()->flash('error', 'Não foi encontrado nenhuma foto como resultado.');
                session()->put('caminhoPastaGoogleDrive',  '');
                return redirect()->route('duplicity');
            }

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "verificaDuplicidade". Erro: ' .$e->getMessage());
            session()->put('caminhoPastaGoogleDrive',  '');
            return redirect()->route('duplicity');
        }
    }  

    public function criarZip()
    {
        $this->achouArquivoZip = False;
        $folderPath = $this->caminho_resultado; 
        $zipFilePath = storage_path('app/public/' .$this->login_id_usuario .'/resultado.zip');

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath));
            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $this->achouArquivoZip = True;
                    $relativePath = substr($file->getRealPath(), strlen($folderPath) + 1);                  
                    $zip->addFile($file->getRealPath(), $relativePath);
                }
            }
            $zip->close();
        } else {
            session()->flash('error', 'Não foi possível criar o arquivo ZIP');
            session()->put('caminhoPastaGoogleDrive',  '');
            return redirect()->route('duplicity');
        }
    }

}



