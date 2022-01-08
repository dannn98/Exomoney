<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/auth', name: 'auth.')]
class AuthController extends AbstractController
{
    private PsrHttpFactory $psrHttpFactory;

    /**
     * AuthController constructor
     *
     * @param PsrHttpFactory $psrHttpFactory
     */
    public function __construct(
        PsrHttpFactory $psrHttpFactory
    )
    {
        $this->psrHttpFactory = $psrHttpFactory;
    }

    /**
     * Login
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $psrRequest = $this->psrHttpFactory->createRequest($request);

        $result = $this->requestOauth([
            'username' => $data['email'],
            'password' => $data['password'],
            'grant_type' => 'password'
        ], $psrRequest);

        if ($result->getStatusCode() == '200') {
            $responseData = json_decode($result->getContent());

            return $this->respondWithToken($responseData);
        }

        return new JsonResponse(['message' => 'Logowanie nie powiodło się'], Response::HTTP_BAD_REQUEST, []);
    }

    /**
     * Refresh token
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    #[Route('/refresh-token', name: 'refresh-token', methods: ['POST'])]
    public function refreshToken(Request $request): JsonResponse
    {
        $refreshToken = $request->cookies->get('refresh_token');

        $psrRequest = $this->psrHttpFactory->createRequest($request);

        $result = $this->requestOauth([
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
            'scope' => ''
        ], $psrRequest);

        if ($result->getStatusCode() == '200') {
            $responseData = json_decode($result->getContent());

            return $this->respondWithToken($responseData);
        }

        return new JsonResponse(['message' => 'Refresh token failed'], Response::HTTP_UNAUTHORIZED, []);
    }

    protected function requestOauth(array $data, ServerRequestInterface $request): Response
    {
        $data = array_merge($data, [
            'client_id' => $this->getParameter('oauth2_client_id'),
            'client_secret' => $this->getParameter('oauth2_secret'),
        ]);

        return $this->forward($this->routeToControllerName(), ['serverRequest' => $request->withParsedBody($data)]);
    }

    protected function respondWithToken($responseData): JsonResponse
    {
        $response = new JsonResponse([
            'token_type' => $responseData->token_type,
            'access_token' => $responseData->access_token,
            'expires_in' => $responseData->expires_in,
        ], Response::HTTP_OK);
        $response->headers->setCookie(
            Cookie::create('refresh_token')
                ->withValue($responseData->refresh_token)
                ->withExpires(strtotime('tomorrow'))
                ->withDomain('localhost')
                ->withSecure(false)
        );

        return $response;
    }

    private function routeToControllerName(): mixed
    {
        $routes = $this->get('router')->getRouteCollection();
        return $routes->get('oauth2_token')->getDefaults()['_controller'];
    }
}
