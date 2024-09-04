        <!-- Inicio :: Barra de Navegacao no Cabecalho -->
        <nav id="nav-container" class="navbar p-2 navbar-expand-md">

            <!-- Inicio :: Icone Logo -->
            <a class="navbar-brand" style="display: flex; align-items: center;" href="{{ route('welcome') }}">
                <div>
                    <img src="{{ asset('imagens/iconeNav.png') }}" width="35" height="35" alt="Logo"> 
                </div>
                <div class="mx-2">
                    <span> {{ __('FotoPlus') }} </span>
                </div> 
            </a>
            <!-- Fim :: Icone Logo -->

            <!-- Inicio :: Nome Usuario -->
            @auth
                <div id="bem-vindo">
                    <span> Bem vindo {{ Auth::user()->name }} #{{ Auth::user()->id }} </span>     
                </div>     
            @endauth
            <!-- Fim :: Nome Usuario -->

            <!-- Inicio :: Botão para NavBar (Resolução Baixa) -->
            <button class="navbar-toggler mx-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-links" aria-controls="navbar-links" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button> 
            <!-- Fim :: Botão para NavBar (Resolução Baixa) -->

            <!-- Inicio :: Itens do NavBar -->
            <div id="navbar-links" class="collapse navbar-collapse d-lg-flex align-items-center justify-content-end">
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="#sobre"> {{ __('Sobre Nós') }} </a>
                        </li>
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}"> {{ __('Registrar') }} </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a id="nav-link-entrar" class="nav-link" href="{{ route('login') }}"> {{ __('Entrar') }} </a>
                        </li>        
                    @endguest

                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}"> {{ __('Painel') }} </a>
                        </li>
                        <li class="nav-item"> 
                            <a class="nav-link" href="{{ route('profile.show') }}"> {{ __('Configuração') }} </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('help') }}"> {{ __('Ajuda') }} </a>
                        </li>
                        <li class="nav-item">                      
                            <form action="{{ route('logout') }}" method="POST"> 
                                @csrf 
                                <a id="nav-link-sair" href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); this.closest('form').submit();"> 
                                    {{ __('Sair') }}  
                                </a>   
                            </form>
                        </li>                                
                    @endauth
                </ul>
            </div>  
            <!-- Fim :: Itens do NavBar -->
            
        </nav>
        <!-- Fim :: Barra de Navegacao no Cabecalho -->