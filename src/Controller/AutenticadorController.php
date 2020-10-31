<?php

namespace Schond\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="autenticador_")
 */
class AutenticadorController extends ControllerBasico
{
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request)
    {
        return $this->json($this->getAutenticadorService()->login($request->request->all()));
    }

    /**
     * @Route("/logout", name="logout", methods={"POST"})
     */
    public function logout(Request $request)
    {
        $this->verificarAcesso($request);

        $id = $request->headers->get('id');
        return $this->json($this->getAutenticadorService()->logout($id));
    }

    /**
     * @Route("/login/verificar", name="verificar", methods={"GET"})
     */
    public function verificar(Request $request)
    {
        try {
            return $this->json($this->verificarAcesso($request));
        } catch (AccessDeniedHttpException $erro) {
            return $this->json(false);
        }
    }
}
