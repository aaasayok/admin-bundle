<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/7/30
 * Time: 下午10:26
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Admin\Events;

use Admin\Services\Signature;
use FastD\Framework\Events\TemplateEvent;
use FastD\Http\JsonResponse;
use FastD\Http\Request;
use FastD\Http\Response;

class Login extends TemplateEvent
{
    public function loginAction()
    {
        return $this->render('Admin/Resources/views/login.twig');
    }

    public function signInAction(Request $request)
    {
        $account = $request->request->hasGet('_account', null);
        $passwrod = $request->request->hasGet('_password', null);
        if (empty($account) || empty($passwrod)) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'code' => 10086,
                    'msg' => 'Access denied.'
                ], Response::HTTP_FORBIDDEN);
            }
            return $this->redirect($this->generateUrl('dash_login'));
        }
        $managerRepository = $this->getConnection('read')->getRepository('Admin:Repository:Manager');
        $manager = $managerRepository->find(['OR' => ['username' => $account, 'email' => $account]]);
        if (false == $manager) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'code' => 10086,
                    'msg' => 'Access denied.'
                ], Response::HTTP_FORBIDDEN);
            }
            return $this->redirect($this->generateUrl('dash_login'));
        }
        $signature = new Signature();
        $sign = $signature->makeMd5Password($manager['username'], $passwrod, $manager['salt']);
        if ($sign !== $manager['pwd']) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse([
                    'code' => 10087,
                    'msg' => 'Manger authorization fail.'
                ], Response::HTTP_FORBIDDEN);
            }
            return $this->redirect($this->generateUrl('dash_login'));
        }
        unset($manager['pwd']);
        $request->setSession('manager', $manager);
        $redirectUrl = $request->request->hasGet('redirect_url', $this->generateUrl('dash_login'));
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['redirect_url' => $redirectUrl]);
        }
        return $this->redirect($redirectUrl);
    }
}