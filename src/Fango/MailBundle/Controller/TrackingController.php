<?php

namespace Fango\MailBundle\Controller;

use Fango\MainBundle\Entity\Mail;
use Fango\MainBundle\Http\TransparentPixelResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TrackingController
 * @author Farhad Safarov <http://ferhad.in>
 * @package Fango\MailBundle\Controller
 */
class TrackingController extends Controller
{
    /**
     * @param Request $request
     * @return TransparentPixelResponse
     */
    public function emailOpenAction(Request $request)
    {
        $uid = $request->query->get('uid');
        if (null !== $uid) {
            $em = $this->getDoctrine()->getManager();
            $email = $em->getRepository('FangoMainBundle:Mail')->findOneBy(['uid' => $uid]);

            if ($email instanceof Mail) {
                $email->setSecondIsOpened(true);
                $em->persist($email);
                $em->flush();
                $em->clear();
            }
        }

        return new TransparentPixelResponse();
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectAction(Request $request)
    {
        $uid = $request->query->get('uid');
        if (null !== $uid) {
            $em = $this->getDoctrine()->getManager();
            $email = $em->getRepository('FangoMainBundle:Mail')->findOneBy(['uid' => $uid]);

            if ($email instanceof Mail) {
                $email->setSecondLinkClicked(true);
                $em->persist($email);
                $em->flush();
                $em->clear();
            }
        }

        $url = $request->query->has('url') ? $request->query->get('url') : $this->generateUrl('fango_main_homepage');

        return $this->redirect($url);
    }
}
