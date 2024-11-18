<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\GoogleToken;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Oauth2;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class GoogleAuthenticate
{
    public function handle($request, Closure $next)
    {
        $user_id = Auth::id();

        //dd($user_id);

        if (!$user_id) {
            return $next($request);
        }

        $client = $this->createGoogleClient();

        // Verifica se já existe um token de acesso
        $googleToken = GoogleToken::where('user_id', $user_id)->first();
        //dd($googleToken);

        if ($googleToken && $googleToken['access_token']) {
            $googleTokenArray = $googleToken->toArray();
            $client->setAccessToken($googleTokenArray);

            if ($client->isAccessTokenExpired()) {

                if ($googleTokenArray['refresh_token'] != '') {

                    // Tenta renovar o token com o refresh token
                    $newToken = $client->fetchAccessTokenWithRefreshToken($googleTokenArray['refresh_token']);

                    // Atualiza o token no banco de dados
                    $googleToken->access_token = isset($newToken['access_token']) ? $newToken['access_token'] : '';
                    $googleToken->refresh_token = isset($newToken['refresh_token']) ? $newToken['refresh_token'] : '';
                    $googleToken->expires_in = isset($newToken['expires_in']) ? $newToken['expires_in'] : 0;
                    $googleToken->save();
                } else {
                    // Se não houver refresh token, redireciona para o fluxo de autenticação
                    return $this->redirectToGoogle($client);
                }

            }

            // Captura o e-mail do usuário logado e armazena na sessão
            $oauth2 = new Oauth2($client);
            $userInfo = $oauth2->userinfo->get();
            $email = $userInfo->email;
            Session::put('google_user_email', $email); // Salva o e-mail na sessão

            return $next($request);
        }

        // Se o request contém o código de autorização, troca por um token de acesso
        if ($request->has('code')) {
            $client->setApprovalPrompt('force');
            $client->setPrompt('select_account');
            $newToken = $client->fetchAccessTokenWithAuthCode($request->input('code'));

            GoogleToken::updateOrCreate(
                ['user_id' => $user_id],
                [
                    'access_token' => $newToken['access_token'],
                    'expires_in' => $newToken['expires_in'] ?? null,
                    'refresh_token' => $newToken['refresh_token'] ?? null,
                    'scope' => $newToken['scope'] ?? null,
                    'token_type' => $newToken['token_type'] ?? null,
                    'created' => $newToken['created'] ?? null,
                ]
            );

            return redirect()->to($request->url());
        }

        // Caso não haja token e nem código de autorização, redireciona para o Google
        return $this->redirectToGoogle($client);
    }

    private function createGoogleClient()
    {
        $client = new Client();
        
        $client->setAuthConfig(storage_path('app/client_secret_497125052021-qheru49cjtj88353ta3d5bq6vf0ffk0o.apps.googleusercontent.com.json'));
        $client->setRedirectUri(route('dashboard')); 
        $client->setAccessType('offline');
        $client->setPrompt('select_account'); // Isso força o Google a pedir que o usuário selecione a conta
        $client->setApprovalPrompt('force'); //    
        $guzzleClient = new \GuzzleHttp\Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false]]);
        $client->setHttpClient($guzzleClient);
        $client->addScope('email');
        $client->addScope(Drive::DRIVE);
        
        return $client;
    }

    // Gera a URL de autenticação do Google e redireciona o usuário
    protected function redirectToGoogle($client)
    {
        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

}
