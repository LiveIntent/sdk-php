<?php

namespace LiveIntent\Services;

use Carbon\Carbon;

class TokenService extends BaseService
{
    /**
     * The client id.
     *
     * @var string
     */
    private $clientId;

    /**
     * The client secret.
     *
     * @var string
     */
    private $clientSecret;

    /**
     * The current access token.
     *
     * @var string
     */
    private $accessToken;

    /**
     * The current token type.
     *
     * @var string
     */
    private $tokenType = 'Bearer';

    /**
     * The expiration timestamp of the current token.
     *
     * @var \Carbon\Carbon
     */
    private $expiresAt;

    /**
     * The number of seconds before expiration to refresh tokens.
     *
     * @var int
     */
    private $bufferSeconds;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;

        $this->clientId = data_get($options, 'client_id');
        $this->clientSecret = data_get($options, 'client_secret');

        $this->stubCallbacks = collect();
    }

    /**
     * Get a valid access token for use with the api, obtaining
     * a new one via the provided credentials if necessary.
     *
     * @return string
     */
    public function token()
    {
        if ($pat = data_get($this->options, 'personal_access_token')) {
            return $pat;
        }

        if ($this->needsNewTokens()) {
            $this->obtainTokens();
        }

        return $this->accessToken;
    }

    /**
     * Get the token type of the current token.
     *
     * @return string
     */
    public function tokenType()
    {
        return $this->tokenType;
    }

    /**
     * Obtain a fresh set of tokens.
     *
     * @return \Illuminate\Http\Client\Response
     */
    public function obtainTokens()
    {
        $response = $this->pendingRequest()
            ->asForm()
            ->post('oauth/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials',
                'scope' => 'openid',
            ]);

        $payload = $response->throw()->json();

        $this->accessToken = $payload['access_token'];
        $this->tokenType = $payload['token_type'];
        $this->expiresAt = Carbon::now()->addSeconds($payload['expires_in'] - $this->bufferSeconds);

        return $response;
    }

    /**
     * Obtain a set of tokens that allow acting on behalf of another user.
     *
     * @param int $id
     * @return \Illuminate\Http\Client\Response
     */
    public function actAs(int $id)
    {
        $response = $this->pendingRequest()
            ->asForm()->withToken(
                $this->token()
            )->post('oauth/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'act_as',
                'scope' => 'openid',
                'act_as_user_id' => $id,
            ]);

        $payload = $response->throw()->json();

        $this->accessToken = $payload['access_token'];
        $this->tokenType = $payload['token_type'];
        $this->expiresAt = Carbon::now()->addSeconds($payload['expires_in'] - $this->bufferSeconds);

        return $response;
    }

    /**
     * Check if new tokens should be generated.
     *
     * @return bool
     */
    private function needsNewTokens()
    {
        if (! $this->accessToken) {
            return true;
        }

        return $this->expiresAt->isPast();
    }
}
