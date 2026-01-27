@extends('layouts.app')

@section('content')

<div style="max-width: 600px; margin: 80px auto; padding: 20px;">
    <div style="background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); overflow: hidden;">
        
        {{-- Card Header --}}
        <div style="background: linear-gradient(135deg, #0a2a43 0%, #051a2f 100%); color: white; padding: 30px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">Connexion</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Accédez à votre compte DataCenter</p>
        </div>

        {{-- Card Body --}}
        <div style="padding: 40px;">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div style="margin-bottom: 25px;">
                    <label for="email" style="display: block; font-weight: 600; margin-bottom: 8px; color: #0a2a43;">
                        Email
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email" 
                        autofocus
                        style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('email') ? '#ef4444' : '#e5e7eb' }}; border-radius: 6px; font-size: 14px; transition: border-color 0.3s ease;"
                        onfocus="this.style.borderColor='#3429d3'; this.style.boxShadow='0 0 0 3px rgba(52, 41, 211, 0.1)';"
                        onblur="this.style.boxShadow='none';"
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
                        Mot de passe
                    </label>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        style="width: 100%; padding: 12px; border: 1px solid {{ $errors->has('password') ? '#ef4444' : '#e5e7eb' }}; border-radius: 6px; font-size: 14px; transition: border-color 0.3s ease;"
                        onfocus="this.style.borderColor='#3429d3'; this.style.boxShadow='0 0 0 3px rgba(52, 41, 211, 0.1)';"
                        onblur="this.style.boxShadow='none';"
                    >
                    @error('password')
                        <div style="color: #ef4444; font-size: 12px; margin-top: 6px;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div style="margin-bottom: 25px; display: flex; align-items: center;">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember" 
                        {{ old('remember') ? 'checked' : '' }}
                        style="width: 18px; height: 18px; cursor: pointer; accent-color: #3429d3;"
                    >
                    <label for="remember" style="margin-left: 8px; cursor: pointer; color: #6b7280;">
                        Se souvenir de moi
                    </label>
                </div>

                {{-- Login Button --}}
                <button 
                    type="submit" 
                    style="width: 100%; padding: 12px; background-color: #3429d3; color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s ease; margin-bottom: 20px;"
                    onmouseover="this.style.backgroundColor='#2318c0'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(52, 41, 211, 0.3)';"
                    onmouseout="this.style.backgroundColor='#3429d3'; this.style.transform='translateY(0)'; this.style.boxShadow='none';"
                >
                    Se connecter
                </button>

                {{-- Forgot Password Link --}}
                @if (Route::has('password.request'))
                    <div style="text-align: center;">
                        <a href="{{ route('password.request') }}" style="color: #3429d3; text-decoration: none; font-size: 14px;">
                            Mot de passe oublié?
                        </a>
                    </div>
                @endif
            </form>

            {{-- Divider --}}
            <div style="margin: 30px 0; text-align: center; position: relative;">
                <div style="border-top: 1px solid #e5e7eb;"></div>
                <span style="position: absolute; left: 50%; transform: translateX(-50%) translateY(-12px); background: white; padding: 0 10px; color: #6b7280; font-size: 12px;">OU</span>
            </div>

            {{-- Account Request Link --}}
            <div style="text-align: center; padding-top: 20px;">
                <p style="margin: 0 0 10px 0; color: #6b7280; font-size: 14px;">
                    Vous n'avez pas encore accès?
                </p>
                <a href="{{ route('account-request.create') }}" style="display: inline-block; padding: 12px 24px; background-color: #10b981; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; transition: all 0.3s ease;"
                    onmouseover="this.style.backgroundColor='#059669'; this.style.transform='translateY(-2px)';"
                    onmouseout="this.style.backgroundColor='#10b981'; this.style.transform='translateY(0)';"
                >
                    📝 Demander l'accès
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
