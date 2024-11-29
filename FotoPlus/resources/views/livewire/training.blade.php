<div>

    <!-- Inicio :: Formulario -->
    <div class="formulario">

        <!-- Inicio :: Card Principal -->
        <div class="card bg-body">

            <!-- Inicio :: Titulo - Card Principal -->
            <div class="card-header">
                <h3 class="text-center"> Realizar o treinamento do rosto de uma pessoa </h3>
            </div>
            <!-- Fim :: Titulo - Card Principal -->

            <!-- Inicio :: Conteudo - Card Principal -->
            <div class="card-body">

                <!-- Inicio :: Tela Cinza Carregamento -->
                <div class="overlay" wire:loading wire:target="image_pessoa_treinamento, treinarPessoa, cadastrarPessoa, selecionarPessoa, alterarTamanhoLog, buscarImagem"> </div>
                <!-- Fim :: Tela Cinza Carregamento -->

                <!-- Inicio :: Carregamento -->
                <div class="alert alert-primary text-center shadow-sm p-3 mx-3 mb-3 rounded"  wire:loading.grid wire:target="image_pessoa_treinamento, treinarPessoa, cadastrarPessoa, selecionarPessoa, alterarTamanhoLog, buscarImagem">
                    <i class="fas fa-spinner fa-spin"></i> <span class="alert-text"> Treinando, aguarde alguns minutos... </span>
                </div>
                <!-- Fim :: Carregamento -->

                <!-- Inicio :: Alerta -->
                @if (session('log'))
                    <div class="alert alert-light text-center shadow-sm p-3 mx-3 mb-3 rounded">
                        <i class="fa fa-cog"></i> <span class="alert-text"> {{ session('log') }} </span> <br><br>
                        <button type="button" class="btn btn-secondary" wire:click="alterarTamanhoLog"> {{ $nome_botao_log }} </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger text-center shadow-sm p-3 mx-3 mb-3 rounded">
                        <i class="fas fa-exclamation-circle"></i> <span class="alert-text"> {{ session('error') }} </span>
                    </div>
                @endif
                @if (session('debug'))
                    <div class="alert alert-primary text-center shadow-sm p-3 mx-3 mb-3 rounded">
                        <i class="fas fa-exclamation-circle"></i> <span class="alert-text"> {{ session('debug') }} </span>
                    </div>
                @endif
                <!-- Fim :: Alerta -->

                <!-- Inicio :: 1ª Linha - Card Principal -->
                <div class="row mb-3">

                    <!-- Inicio :: 1ª Coluna - Card Principal -->
                    <div class="col-lg">

                        <!-- Inicio :: Card 1º Passo -->
                        <div class="card">

                            <!-- Inicio :: Titulo - Card 1º Passo -->
                            <div class="card-header">
                                <h4 class="text"> 1º Passo: Carregar foto do rosto de uma pessoa para o treinamento </h4>
                            </div>
                            <!-- Fim :: Titulo - Card 1º Passo -->

                            <!-- Inicio :: Conteudo - Card 1º Passo -->
                            <div class="card-body">

                                <!-- Inicio :: 1ª Linha - Card 1º Passo -->
                                <div class="row">
                                    
                                    <!-- Início :: 1ª Coluna - Card 1º Passo -->
                                    <div class="col-lg">
                                        <div
                                            x-data="{
                                                isUploading: false,
                                                progress: 0,
                                                errorMessage: '',
                                                imagePreview: '',
                                                handleFileInput(event) {
                                                    const file = event.target.files[0];
                                                    this.errorMessage = '';
                                                    this.imagePreview = '';

                                                    if (file) {
                                                        const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                                                        if (!validTypes.includes(file.type)) {
                                                            this.errorMessage = 'Por favor, selecione uma imagem válida (JPEG ou PNG).';
                                                            return;
                                                        }

                                                        // Exibir pré-visualização
                                                        const reader = new FileReader();
                                                        reader.onload = (e) => {
                                                            this.imagePreview = e.target.result;
                                                        };
                                                        reader.readAsDataURL(file);

                                                        // Simulação de progresso
                                                        this.isUploading = true;
                                                        let progressInterval = setInterval(() => {
                                                            this.progress += 10;
                                                            if (this.progress >= 100) {
                                                                clearInterval(progressInterval);
                                                                this.isUploading = false;
                                                            }
                                                        }, 100);
                                                    } else {
                                                        this.errorMessage = 'Nenhum arquivo selecionado.';
                                                    }
                                                }
                                            }"
                                        >
                                            <label class="custom-file-upload">
                                                <input 
                                                    type="file" 
                                                    wire:model="image_pessoa_treinamento"
                                                    accept=".jpg,.jpeg,.png" 
                                                    required 
                                                    x-on:change="handleFileInput"
                                                >
                                                Selecione a Imagem
                                            </label>

                                            <!-- Mensagem de Erro -->
                                            <span x-text="errorMessage" class="error" style="display: block; color: red;" x-show="errorMessage"></span>
                                            
                                            <!-- Pré-visualização da Imagem -->
                                            <img 
                                                id="imagem-treinamento" 
                                                x-bind:src="imagePreview" 
                                                alt="Imagem Temporária do Treinamento" 
                                                style="display: block; max-width: 100%; margin-top: 10px;" 
                                                x-show="imagePreview"
                                            >

                                            <!-- Barra de Progresso -->
                                            <div x-show="isUploading">
                                                <div class="row p-3 mx-3">
                                                    <progress class="p-3" max="100" x-bind:value="progress"></progress>
                                                </div>
                                                <div class="row">
                                                    <span class="text-center">Carregando...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fim :: 1ª Coluna - Card 1º Passo -->                                    

                                </div>
                                <!-- Fim :: 1ª Linha - Card 1º Passo -->

                            </div>
                            <!-- Fim :: Conteudo - Card 1º Passo -->

                        </div>
                        <!-- Fim :: Card 1º Passo -->

                    </div>
                    <!-- Fim :: 1ª Coluna - Card Principal -->

                    <!-- Inicio :: 2ª Coluna - Card Principal -->
                    <div class="col-lg">

                        <!-- Inicio :: Card 2º Passo -->
                        <div class="card">

                            <!-- Inicio :: Titulo - Card 2º Passo -->
                            <div class="card-header">
                                <h4 class="text">2º Passo: Cadastrar/Treinar o rosto da pessoa</h4>
                            </div>
                            <!-- Fim :: Titulo - Card 2º Passo -->

                            <!-- Inicio :: Conteudo - Card 2º Passo -->
                            <div class="card-body">

                                <!-- Inicio :: 1ª Linha - Card 2º Passo -->
                                <div class="row mb-3">

                                    <!-- Inicio :: 1ª Coluna - Card 1º Passo -->    
                                    <div class="col-lg">
                                        <form wire:submit.prevent="cadastrarPessoa" wire:confirm="Deseja realmente cadastrar e treinar esse rosto na foto?">   
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">Nome:</span>
                                                <input class="campo-filtro form-control" type="text" placeholder="Nova Pessoa" wire:model="nome_pessoa_cadastro" required>
                                            </div>      
                                            <div class="d-grid gap-2 col-4 mx-auto">                  
                                                <button type="submit" class="btn btn-primary" onclick="voltaInicio()"> Cadastrar & Treinar </button>
                                            </div>          
                                        </form>
                                    </div>
                                    <!-- Fim :: 1ª Coluna - Card 1º Passo -->  

                                </div>
                                <!-- Fim :: 1ª Linha - Card 2º Passo -->

                                <span class="text-secondary"> Atenção: Caso a imagem carregada contenha o rosto de uma pessoa que não esteja presente na lista abaixo, é necessário cadastrar essa pessoa no campo indicado acima. </span>

                                <hr class="my-3">

                                <!-- Inicio :: 2ª Linha - Card 2º Passo -->
                                <div class="row mb-3">

                                    <!-- Inicio :: 1ª Coluna - Card 2º Passo -->    
                                    <div class="col-lg">
                                        <div class="input-group">                   
                                            <span class="input-group-text">Buscar:</span>
                                            <input class="campo-filtro form-control" type="search" placeholder="Pesquise uma pessoa" wire:model.live="query_pessoas_cadastro" aria-label="Search">           
                                        </div> 
                                    </div>
                                    <!-- Fim :: 1ª Coluna - Card 2º Passo -->   

                                </div>
                                <!-- Fim :: 2ª Linha - Card 2º Passo -->

                                <!-- Inicio :: 3ª Linha - Card 2º Passo -->
                                <div class="row mb-3">
                            
                                    <!-- Inicio :: 1ª Coluna - Card 2º Passo -->    
                                    <div class="col-lg">
                                        <h6 class="text-center"> Código da pessoa selecionada:  {{ $this->id_pessoa_treinamento }} </h6>                               
                                    </div>
                                    <!-- Fim :: 1ª Coluna - Card 2º Passo -->   

                                </div>
                                <!-- Fim :: 3ª Linha - Card 2º Passo -->

                                <!-- Inicio :: 4ª Linha - Card 2º Passo -->
                                <div class="row mb-3">

                                    <!-- Inicio :: 1ª Coluna - Card 2º Passo -->   
                                    <div class="col-lg">
                                        <table class="table-pessoa">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="width: 5%;"></th>                            
                                                    <th scope="col" style="width: 20%;">Foto</th>
                                                    <th scope="col" style="width: 10%;">ID</th>
                                                    <th scope="col" style="width: 65%;">Nome</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($listaPessoas as $pessoa)
                                                    <tr class="list-item {{ $pessoa->id == $id_pessoa_treinamento ? 'selected' : '' }}"
                                                        wire:key="{{ $pessoa->id }}" 
                                                        wire:click="selecionarPessoa({{ $pessoa->id }})">

                                                        <td style="width: 5%;">
                                                            <input 
                                                                class="form-check-input" 
                                                                type="radio" 
                                                                name="rgPessoa" 
                                                                id="rgPessoa_{{ $pessoa->id }}" 
                                                                wire:model="id_pessoa_treinamento" 
                                                                value="{{ $pessoa->id }}"
                                                                @if($pessoa->id == $id_pessoa_treinamento) 
                                                                    checked 
                                                                @endif
                                                            > 
                                                        </td>

                                                        <td class="p-2" style="width: 20%;">
                                                            @if ($pessoa->faces->isNotEmpty())
                                                                <img class="imagem-pessoa" src="{{ asset('storage/' . $pessoa->faces[0]->url_face) }}" alt="{{ $pessoa->nome }}" width="120" height="120">
                                                            @else
                                                                <p>Sem foto disponível</p>
                                                            @endif
                                                        </td>

                                                        <td class="p-2" style="width: 10%;">
                                                            <label class="form-check-label" for="rgPessoa_{{ $pessoa->id }}">{{ $pessoa->id }}</label>
                                                        </td>

                                                        <td class="p-2" style="width: 65%;">
                                                            <label class="form-check-label" for="rgPessoa_{{ $pessoa->id }}">{{ $pessoa->name }}</label>
                                                        </td>

                                                    </tr>
                                                @endforeach 
                                            </tbody>
                                        </table>
                                                                            

                                        <!-- Script JavaScript para registrar os caminhos das imagens no console -->
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function () {
                                                const images = document.querySelectorAll('.imagem-pessoa');
                                                images.forEach(function (img) {
                                                    console.log('Caminho da imagem: ' + img.src);
                                                });
                                            });
                                        </script>

                                        {{ $listaPessoas->links() }}
                                    </div>
                                    <!-- Fim :: 1ª Coluna - Card 2º Passo -->   
                                
                                </div>
                                <!-- Fim :: 4ª Linha - Card 2º Passo -->

                                <!-- Inicio :: 5ª Linha - Card 2º Passo -->
                                <div class="row mb-3">

                                    <!-- Inicio :: 1ª Coluna - Card 2º Passo -->   
                                    <div class="col-lg">
                                        <form wire:submit.prevent="treinarPessoa" wire:confirm="Deseja realmente treinar esse rosto na foto?">
                                            <div class="d-grid gap-2 col-4 mx-auto">                  
                                                <button type="submit" class="btn btn-primary" onclick="voltaInicio()"> Treinar Selecionado </button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Fim :: 1ª Coluna - Card 2º Passo -->   
                                
                                </div>
                                <!-- Fim :: 5ª Linha - Card 2º Passo -->

                            </div>
                            <!-- Fim :: Conteudo - Card 2º Passo -->

                        </div>
                        <!-- Fim :: Card 2º Passo -->

                    </div>
                    <!-- Fim :: 2ª Coluna - Card Principal -->

                </div>
                <!-- Fim :: 1ª Linha - Card Principal -->

            </div>
            <!-- Fim :: Conteudo Card Principal -->

        </div>
        <!-- Fim :: Card Principal -->

    </div>
    <!-- Fim :: Formulario -->

</div>

<script>
    function voltaInicio() {
        var element = document.getElementById("inicio");
        if (element) {
            element.scrollIntoView({ behavior: "smooth" });
        }
    }
</script>
