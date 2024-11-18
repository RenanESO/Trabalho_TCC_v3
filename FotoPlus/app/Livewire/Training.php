<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Person;
use App\Models\Face;
use Exception;

class Training extends Component
{
    use WithFileUploads, WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $login_id_usuario;

    public $caminho_deteccao_python_exe;
    public $caminho_compilador_python;
    public $caminho_deteccao_python;

    public $caminho_pasta_public;
    public $caminho_arquivo_log;

    public $caminho_imagem_treinamento; 
    public $nome_pessoa_cadastro;
    public $id_pessoa_treinamento;
    public $image_pessoa_treinamento;

    public $query_pessoas_cadastro;

    public $nome_botao_log; 

    // Função construtora da pagina no blade "Treinamento".
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

        // Definindo as variaveis para realizar a rotina de treinamento.
        $this->caminho_imagem_treinamento = '';
        $this->nome_pessoa_cadastro = '';
        $this->id_pessoa_treinamento = '';
        $this->image_pessoa_treinamento = null;
        $this->query_pessoas_cadastro = '';

        // Definindo a variavel com o nome do botão do resultado da rotina de treinamento.
        $this->nome_botao_log = 'Leia mais';
    }

    // Função principal para renderizar a pagina no blade "Treinamento".
    public function render() 
    {
        $nomeApp = "FotoPlus";  
        $listaPessoas = (new Person())->searchPeopleByName($this->query_pessoas_cadastro);
        return view('livewire.training', compact('nomeApp', 'listaPessoas'));
    }

    // Função responsavel em mostrar a mensagem do arquivo log maximizado.
    public function mostrarLogMaximizado() 
    {
        try {
            if (file_exists($this->caminho_arquivo_log)) {
                $texto_completo_log = implode(" | ", file($this->caminho_arquivo_log));  

                $this->nome_botao_log = 'Leia menos'; 
                session()->flash('log', $texto_completo_log);
            } else {
                session()->flash('log', 'O arquivo log.txt sem resposta ou não existe. Caminho: ' .$this->caminho_arquivo_log);
            }  
                    
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "mostrarLogMaximizado". Erro: ' .$e->getMessage());
            return redirect()->route('training'); 
        }
    }   

    // Função responsavel em mostrar a mensagem do arquivo log minimizado.
    public function mostrarLogMinimizado() 
    {
        try {
            if (file_exists($this->caminho_arquivo_log)) {
                $texto_completo_log = file($this->caminho_arquivo_log);   
                $texto_penultima_linha_log = $texto_completo_log[count($texto_completo_log) - 1];
                $this->nome_botao_log = 'Leia mais'; 
                session()->flash('log', $texto_penultima_linha_log);  
            } else {
                session()->flash('log', 'O arquivo log.txt sem resposta ou não existe. Caminho: ' .$this->caminho_arquivo_log);
            }  
            
        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "mostrarLogMinimizado". Erro: ' .$e->getMessage());
            return redirect()->route('training');
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
            return redirect()->route('training');
        }
    } 

    // Função responsavel em atribuir a pessoa selecionada na tabela para o treinamento.
    public function selecionarPessoa($id)
    {
        try {
            $this->id_pessoa_treinamento = $id;

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "selecionarPessoa". Erro: ' .$e->getMessage());
            return redirect()->route('training');
        }      
    }

    // Função que realiza o cadastro de uma nova pessoa e rosto referente a pessoa cadastrada. 
    public function cadastrarPessoa() 
    {
        try {
            // Limpa a mensagem flash antes de executar a rotina
            session()->forget(['log', 'error', 'debug']);

            // Verifica se ID do usuário está definido.
            if (!$this->login_id_usuario) {
                session()->flash('error', 'Não foi encontrado o ID do usuário logado.');
                return redirect()->route('training');   
            }

            // Verifica se nome da pessoa que será realizado o cadastro está definido.
            if (!$this->nome_pessoa_cadastro) {
                session()->flash('error', 'Não foi encontrado o nome da pessoa que será realizado o cadastro.');
                return redirect()->route('training');   
            }

            // Verifica se a imagem da pessoa que será realizado o cadastro está definido.
            if (!$this->image_pessoa_treinamento) {
                session()->flash('error', 'Não foi encontrado a imagem da pessoa que será realizado o cadastro.');
                return redirect()->route('training');   
            }

            // Adapte as regras de validação conforme necessário.
            $this->validate([
                'nome_pessoa_cadastro' => 'required|string|min:1|max:100',
                'image_pessoa_treinamento' => 'required|image|mimes:jpeg,png,jpg,gif|min:1|max:2048',
                'login_id_usuario' => 'required'
            ]);

            // Cadastrado uma nova pessoa na tabela.
            $pessoa_cadastrada = Person::create([
                'name' => $this->nome_pessoa_cadastro,
                'user_id' => $this->login_id_usuario
            ]);

            // Atribui na variavel o id da nova pessoa cadastrada para realizar o treinamento 
            // do rosto inserido.
            $this->id_pessoa_treinamento = $pessoa_cadastrada->id;

            // Reaaliza o treinamento do rosto da nova pessoa cadastrada.
            $this->treinarPessoa();

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "cadastrarPessoa". Erro: ' .$e->getMessage());
            return redirect()->route('training');
        } 
    }

    // Função que realiza o cadastro do novo rosto da pessoa seleciona e realiza um novo treinamento.
    public function treinarPessoa() 
    {
        try {
            // Limpa a mensagem flash antes de executar a rotina
            session()->forget(['log', 'error', 'debug']);

            // Verifica se ID do usuário está definido.
            if (!$this->login_id_usuario) {
                session()->flash('error', 'Não foi encontrado o ID do usuário logado.');
                return redirect()->route('training');   
            }

            // Verifica se a imagem da pessoa que será realizado o cadastro está definido.
            if (!$this->image_pessoa_treinamento) {
                session()->flash('error', 'Não foi encontrado a imagem da pessoa que será realizado o cadastro.');
                return redirect()->route('training');   
            }

            // Adapte as regras de validação conforme necessário.
            $this->validate([
                'id_pessoa_treinamento' => 'required',
                'image_pessoa_treinamento' => 'required|image|mimes:jpeg,png,jpg,gif|min:1|max:2048',
                'login_id_usuario' => 'required'
            ]);

            // Defina o caminho para armazenar a imagem
            $caminho = $this->login_id_usuario .'/' .'rostosCadastrados' .'/' .$this->id_pessoa_treinamento;

            $this->image_pessoa_treinamento = $this->image_pessoa_treinamento->store($caminho, 'public');
            $this->image_pessoa_treinamento = str_replace('/', '/', $this->image_pessoa_treinamento);
            $this->image_pessoa_treinamento = str_replace('public/', '', $this->image_pessoa_treinamento);
            //session()->flash('debug', 'Caminho imagem rosto banco de dados: ' .$this->image_pessoa_treinamento);  
            
            $this->caminho_imagem_treinamento = storage_path('app' .'/' .'public' .'/' . $this->image_pessoa_treinamento);       
            //session()->flash('debug', 'Caminho imagem rosto storage: ' .$this->caminho_imagem_treinamento);  
    
            // Cadastrado um novo rosto na tabela, referente a pessoa criada ou selecionada.
            Face::create([
                'id_person' => $this->id_pessoa_treinamento,
                'url_face' => $this->image_pessoa_treinamento
            ]);
            
            $parametros = [     
                'treinamento',                      // Parametro referente a rotina de treianento que será realizada no python.                   
                $this->caminho_pasta_public,        // Parametro referente ao caminho da pasta public.
                $this->login_id_usuario,            // Parametro referente ao ID do usuario logado.
                $this->caminho_imagem_treinamento,  // Parametro referente ao caminho da imagem de treinamento.
                $this->id_pessoa_treinamento        // Parametro referente ao ID da pessoa que vai realizar o treinamento do rosto.
            ];

            // Chamada externa do python para realizar o treinamento da foto da pessoa selecionada.
            $comando = $this->caminho_deteccao_python_exe .' ' .implode(' ', $parametros);           
            session()->flash('debug', 'Comando: ' .$comando);

            $comando = escapeshellcmd($comando);
            $cmdResultado = shell_exec($comando); 
            //dd($cmdResultado);

            // Mostra o conteudo do arquivo log minimizado.
            $this->mostrarLogMinimizado();         
               
            return redirect()->route('training');    

        } catch (Exception $e) {
            session()->flash('error', 'Ocorreu um erro interno, rotina "treinarPessoa" Erro: ' .$e->getMessage());
            return redirect()->route('training');
        } 
    }
}