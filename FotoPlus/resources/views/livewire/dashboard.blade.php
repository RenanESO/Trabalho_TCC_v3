<div>

    <!-- Inicio :: Menu -->
    <div id="menu">

        <h1 class="text-center mb-4">Escolha uma opção</h1>

            <!-- Inicio :: 1ª Linha - Menu -->
        <div class="row row-cols-1 row-cols-md-3 g-4">

            <!-- Inicio :: 1ª Coluna - Menu -->
            <div class="col-lg">
                <div class="card m-2" style="cursor: pointer;" onclick="location.href='{{ route('organize') }}'">
                    <img src="{{ asset('imagens/iconeOrganizar.png') }}" class="card-img-top" alt="card-Organizar">
                    <div class="card-body">
                        <h5 class="card-title">Organizar conjunto de fotos</h5>
                        <p class="card-text">
                            Organize suas fotos localizadas no Google Drive ou OneDrive pelos 
                            filtros de data e pessoas. Ajudando na organização de 
                            sua galeria.
                        </p>
                    </div>
                    <div class="card-footer d-grid gap-2">
                        <a href="{{ route('organize') }}" class="btn btn-primary">Selecionar</a>
                    </div>
                </div>
            </div>
            <!-- Fim :: 1ª Coluna - Menu -->

            <!-- Inicio :: 2ª Coluna - Menu -->
            <div class="col-lg">
                <div class="card m-2" style="cursor: pointer;" onclick="location.href='{{ route('duplicity') }}'">
                    <img src="{{ asset('imagens/iconeDuplicidade.png') }}" class="card-img-top" alt="card-Duplicidade">
                    <div class="card-body">
                        <h5 class="card-title">Encontrar duplicidade de fotos</h5>
                        <p class="card-text">
                            Encontre possíveis duplicidades de fotos localizadas em uma pasta 
                            específica no Google Drive ou OneDrive.
                        </p>
                    </div>
                    <div class="card-footer d-grid gap-2">
                        <a href="{{ route('duplicity') }}" class="btn btn-primary">Selecionar</a>
                    </div>
                </div>
            </div>
            <!-- Fim :: 1ª Coluna - Menu -->

            <!-- Inicio :: 3ª Coluna - Menu -->
            <div class="col-lg">
                <div class="card m-2" style="cursor: pointer;" onclick="location.href='{{ route('training') }}'">
                    <img src="{{ asset('imagens/iconeTreinamento.png') }}" class="card-img-top" alt="card-Treinamento">
                    <div class="card-body">
                        <h5 class="card-title">Treinamento IA</h5>
                        <p class="card-text">
                            Realize o treinamento manual de rosto para encontrar uma pessoa em específico 
                            em sua galeria de fotos localizada no Google Drive ou OneDrive.
                        </p>
                    </div>
                    <div class="card-footer d-grid gap-2">
                        <a href="{{ route('training') }}" class="btn btn-primary">Selecionar</a>
                    </div>
                </div>
            </div>
            <!-- Fim :: 1ª Coluna - Menu -->

        </div>
        <!-- Fim :: 1ª Linha - Menu -->
        
    </div>
    <!-- Fim :: Menu -->

</div>
