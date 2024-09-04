<div>
    
    <!-- Gerar token de API -->
    <x-form-section submit="createApiToken">
        <x-slot name="title">
            {{ __('Criar token de API') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Os tokens de API permitem que serviços de terceiros sejam autenticados em nosso aplicativo em seu nome.') }}
        </x-slot>

        <x-slot name="form">
            <!-- Nome do token -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('Token Name') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="createApiTokenForm.name" autofocus />
                <x-input-error for="name" class="mt-2" />
            </div>

            <!-- Permissões de token -->
            @if (Laravel\Jetstream\Jetstream::hasPermissions())
                <div class="col-span-6">
                    <x-label for="permissions" value="{{ __('Permissions') }}" />

                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach (Laravel\Jetstream\Jetstream::$permissions as $permission)
                            <label class="flex items-center">
                                <x-checkbox wire:model="createApiTokenForm.permissions" :value="$permission"/>
                                <span class="ms-2 text-sm text-gray-600">{{ $permission }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="created">
                {{ __('Criada.') }}
            </x-action-message>

            <x-button>
                {{ __('Criar') }}
            </x-button>
        </x-slot>
    </x-form-section>

    @if ($this->user->tokens->isNotEmpty())
        <x-section-border />

        <!-- Gerenciar tokens de API -->
        <div class="mt-10 sm:mt-0">
            <x-action-section>
                <x-slot name="title">
                    {{ __('Gerenciar tokens de API') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Você pode excluir qualquer um dos seus tokens existentes se eles não forem mais necessários.') }}
                </x-slot>

                <!-- Lista de tokens de API -->
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($this->user->tokens->sortBy('name') as $token)
                            <div class="flex items-center justify-between">
                                <div class="break-all">
                                    {{ $token->name }}
                                </div>

                                <div class="flex items-center ms-2">
                                    @if ($token->last_used_at)
                                        <div class="text-sm text-gray-400">
                                            {{ __('Usado pela última vez') }} {{ $token->last_used_at->diffForHumans() }}
                                        </div>
                                    @endif

                                    @if (Laravel\Jetstream\Jetstream::hasPermissions())
                                        <button class="cursor-pointer ms-6 text-sm text-gray-400 underline" wire:click="manageApiTokenPermissions({{ $token->id }})">
                                            {{ __('Permissões') }}
                                        </button>
                                    @endif

                                    <button class="cursor-pointer ms-6 text-sm text-red-500" wire:click="confirmApiTokenDeletion({{ $token->id }})">
                                        {{ __('Excluir') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-action-section>
        </div>
    @endif

    <!-- Modal de valor do token -->
    <x-dialog-modal wire:model.live="displayingToken">
        <x-slot name="title">
            {{ __('Token de API') }}
        </x-slot>

        <x-slot name="content">
            <div>
                {{ __('Copie seu novo token de API. Para sua segurança, ele não será exibido novamente.') }}
            </div>

            <x-input x-ref="plaintextToken" type="text" readonly :value="$plainTextToken"
                class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full break-all"
                autofocus autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                @showing-token-modal.window="setTimeout(() => $refs.plaintextToken.select(), 250)"
            />
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('displayingToken', false)" wire:loading.attr="disabled">
                {{ __('Fechar') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal de permissões de token de API -->
    <x-dialog-modal wire:model.live="managingApiTokenPermissions">
        <x-slot name="title">
            {{ __('Permissões de token de API') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach (Laravel\Jetstream\Jetstream::$permissions as $permission)
                    <label class="flex items-center">
                        <x-checkbox wire:model="updateApiTokenForm.permissions" :value="$permission"/>
                        <span class="ms-2 text-sm text-gray-600">{{ $permission }}</span>
                    </label>
                @endforeach
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('managingApiTokenPermissions', false)" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="updateApiToken" wire:loading.attr="disabled">
                {{ __('Salvar') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Modal de confirmação de exclusão de token -->
    <x-confirmation-modal wire:model.live="confirmingApiTokenDeletion">
        <x-slot name="title">
            {{ __('Excluir token de API') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Tem certeza de que deseja excluir este token de API?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingApiTokenDeletion')" wire:loading.attr="disabled">
                {{ __('Cancelar') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteApiToken" wire:loading.attr="disabled">
                {{ __('Excluir') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>
