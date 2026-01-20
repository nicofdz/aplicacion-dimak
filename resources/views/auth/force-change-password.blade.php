<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Para continuar, necesitamos que actualices tu contraseña y completes la información de tu perfil.') }}
    </div>

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf

        <div class="space-y-4">
            <!-- RUT -->
            <div x-data="{
                rut: '{{ old('rut', auth()->user()->rut) }}',
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
                <x-text-input id="rut" name="rut" type="text" class="mt-1 block w-full" x-model="rut"
                    @input="formatRut()" placeholder="12.345.678-9" maxlength="12" required />
                <p x-show="error" x-text="error" class="text-sm text-red-600 mt-1"></p>
                <x-input-error class="mt-2" :messages="$errors->get('rut')" />
            </div>

            <!-- Phone -->
            <div>
                <x-input-label for="phone" :value="__('Teléfono')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', auth()->user()->phone)" placeholder="+56 9 1234 5678" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Address -->
            <div>
                <x-input-label for="address" :value="__('Dirección')" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', auth()->user()->address)" placeholder="Av. Siempre Viva 742" required />
                <x-input-error :messages="$errors->get('address')" class="mt-2" />
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Nueva Contraseña')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Guardar y Continuar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>