<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Información del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Actualice la información de su perfil y su dirección de correo electrónico.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div>
            <x-input-label for="photo" :value="__('Foto de Perfil')" />
            <div x-data="{ photoName: null, photoPreview: null }" class="col-span-6 sm:col-span-4">
                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    @if ($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}"
                            class="rounded-full h-20 w-20 object-cover">
                    @else
                        <div
                            class="rounded-full h-20 w-20 bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xl">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Seleccionar Nueva Foto') }}
                </x-secondary-button>

                <input type="file" id="photo" class="hidden" wire:model.live="photo" x-ref="photo" name="photo"
                    x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-input-error class="mt-2" :messages="$errors->get('photo')" />
            </div>
        </div>

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required
                autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- RUT -->
        <div x-data="{
            rut: '{{ old('rut', $user->rut) }}',
            error: '',
            formatRut() {
                let value = this.rut.replace(/[^0-9kK]/g, '').toUpperCase();
                if (value.length > 1) {
                    const dv = value.slice(-1);
                    let body = value.slice(0, -1);
                    body = body.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    this.rut = body + '-' + dv;
                } else {
                    this.rut = value;
                }
                this.validateRut();
            },
            validateRut() {
                let value = this.rut.replace(/[^0-9kK]/g, '').toUpperCase();
                if (value.length < 8) {
                    this.error = ''; // Demasiado corto para validar aún
                    return;
                }
                const body = value.slice(0, -1);
                const dv = value.slice(-1);
                let suma = 0;
                let multiplo = 2;
                for (let i = body.length - 1; i >= 0; i--) {
                    suma += multiplo * body.charAt(i);
                    multiplo = (multiplo + 1) % 8 || 2;
                }
                const calculado = 11 - (suma % 11);
                const dvCalculado = calculado === 11 ? '0' : (calculado === 10 ? 'K' : calculado.toString());
                
                if (dv !== dvCalculado) {
                    this.error = 'RUT inválido';
                    document.getElementById('rut').setCustomValidity('RUT inválido');
                } else {
                    this.error = '';
                    document.getElementById('rut').setCustomValidity('');
                }
            }
        }">
            <x-input-label for="rut" :value="__('RUT')" />
            <x-text-input id="rut" name="rut" type="text" class="mt-1 block w-full" 
                x-model="rut" 
                @input="formatRut()" 
                placeholder="12.345.678-9" 
                maxlength="12" />
            <p x-show="error" x-text="error" class="text-sm text-red-600 mt-1"></p>
            <x-input-error class="mt-2" :messages="$errors->get('rut')" />
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Teléfono')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)"
                placeholder="+56 9 1234 5678" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Address -->
        <div>
            <x-input-label for="address" :value="__('Dirección')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)"
                placeholder="Av. Siempre Viva 742" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Correo Electrónico')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Su dirección de correo electrónico no está verificada.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Haga clic aquí para re-enviar el correo de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Se ha enviado un nuevo enlace de verificación a su dirección de correo.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>