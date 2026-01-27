@extends('layouts.app')

@section('content')

<div style="max-width: 600px; margin: 80px auto; padding: 20px;">
    <div style="background-color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); overflow: hidden;">
        
        {{-- Card Header --}}
        <div style="background: linear-gradient(135deg, #0a2a43 0%, #051a2f 100%); color: white; padding: 30px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px;">Vérifiez votre email</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">Confirmez votre adresse email pour continuer</p>
        </div>

        {{-- Card Body --}}
        <div style="padding: 40px;">
            @if (session('resent'))
                <div style="background-color: #d1fae5; color: #065f46; padding: 15px; margin-bottom: 20px; border-radius: 6px; border-left: 4px solid #10b981;">
                    Un nouveau lien de vérification a été envoyé à votre adresse email.
                </div>
            @endif

            <div style="color: #0a2a43; margin-bottom: 20px; line-height: 1.6;">
                <p style="margin-bottom: 10px;">
                    Avant de continuer, veuillez vérifier votre email pour un lien de vérification.
                </p>
                <p style="margin: 0;">
                    Si vous n'avez pas reçu l'email,
                    <form method="POST" action="{{ route('verification.resend') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #3429d3; text-decoration: underline; cursor: pointer; font-size: inherit; font-weight: 600; padding: 0;">
                            cliquez ici pour en demander un autre
                        </button>.
                    </form>
                </p>
            </div>
        </div>
    </div>

    {{-- Back Link --}}
    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('login') }}" style="color: #3429d3; text-decoration: none;">
            Retour à la connexion
        </a>
    </div>
</div>

@endsection
