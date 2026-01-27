@extends('layouts.app')

@section('content')

<div style="max-width: 600px; margin: 80px auto; padding: 20px;">
    <div style="background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); overflow: hidden;">
        
        {{-- Card Header --}}
        <div style="background: linear-gradient(135deg, #0a2a43 0%, #051a2f 100%); color: white; padding: 30px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">Réinitialiser le mot de passe</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Entrez votre nouveau mot de passe</p>
        </div>

        {{-- Card Body --}}
        <div style="padding: 40px;">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email --}}
                <div style="margin-bottom: 25px;">
                    <label for="email" style="display: block; font-weight: 600; margin-bottom: 8px; color: #0a2a43;">
                        Email
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ $email ?? old('email') }}" 
                        required 
                        autocomplete="email" 
                        autofocus
                        style="width: 100%; padding: 12px; border: 1px solid @error('email')#ef4444@else#e5e7eb@enderror; border-radius: 6px; font-size: 14px;"
                    >
                    @error('email')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 6px;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div style="margin-bottom: 25px;">
                    <label for="password" style="display: block; font-weight: 600; margin-bottom: 8px; color: #0a2a43;">
                        Nouveau mot de passe
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="new-password"
                        style="width: 100%; padding: 12px; border: 1px solid @error('password')#ef4444@else#e5e7eb@enderror; border-radius: 6px; font-size: 14px;"
                    >
                    @error('password')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 6px;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div style="margin-bottom: 25px;">
                    <label for="password-confirm" style="display: block; font-weight: 600; margin-bottom: 8px; color: #0a2a43;">
                        Confirmer le mot de passe
                    </label>
                    <input 
                        id="password-confirm" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;"
                    >
                </div>

                {{-- Reset Button --}}
                <button 
                    type="submit" 
                    style="width: 100%; padding: 12px; background-color: #3429d3; color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s ease;"
                    onmouseover="this.style.backgroundColor='#2318c0'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(52, 41, 211, 0.3)';"
                    onmouseout="this.style.backgroundColor='#3429d3'; this.style.transform='translateY(0)'; this.style.boxShadow='none';"
                >
                    Réinitialiser le mot de passe
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
            </div>
        </div>
    </div>
</div>
@endsection
