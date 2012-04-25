<?php

namespace Acme\DesignBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\DesignBundle\Entity\Photo;
use Acme\DesignBundle\Entity\TypePhoto;
use Acme\DesignBundle\Entity\Object;
use Acme\DesignBundle\Form\TypePhotoType;

class TypePhotoController extends Controller
{

    public function managerAction()
    {
        $this->check_securuty();

        $photo_types = $this->getDoctrine()
            ->getRepository('AcmeDesignBundle:TypePhoto')
            ->findAll();

        $photo_type_to_create = new TypePhoto();
        $form = $this->createForm(new TypePhotoType(), $photo_type_to_create);

        return $this->render('AcmeDesignBundle:TypePhoto:manager.html.twig',
            array(
                'photo_types' => $photo_types,
                'form' => $form->createView()
            )
        );

    }


    public function createAction(Request $request)
    {
        $this->check_securuty();

        if ($request->getMethod() == 'POST') {

            $photo_type = new TypePhoto();
            $form = $this->createForm(new TypePhotoType(), $photo_type);

            $form->bindRequest($request);

            if ($form->isValid()) {
                // выполняем прочие действие, например, сохраняем задачу в базе данных
                $em = $this->getDoctrine()->getEntityManager();

                $em->persist($photo_type);
                $em->flush();

//                return $this->redirect($this->generateUrl('type_photo_manager'));
            } else {
                $this->get('session')->setFlash('notice', 'Форма заповнена невірно!');
            }

            $this->get('session')->setFlash('notice', 'Тип фото збережено!');

        } else {
            $this->get('session')->setFlash('notice', 'Не було прийнято запиту POST з форми');
        }

        return $this->redirect($this->generateUrl('type_photo_manager'));
    }


    public function editAction($type_photo_id)
    {
        $this->check_securuty();

        $request = $request = $this->get('request');
        $em = $this->getDoctrine()->getEntityManager();
        $type_photo = $em->getRepository("AcmeDesignBundle:TypePhoto")->find($type_photo_id);

        if (!$type_photo) {
            $this->get('session')->setFlash('notice', 'Не було знайдено типу фото з id = '.$type_photo_id);
            return $this->redirect($this->generateUrl('type_photo_manager'));
        }

        $form = $this->createForm(new TypePhotoType(), $type_photo);

        if ($request->getMethod() == 'POST') {

            $form->bindRequest($request);

            if ($form->isValid()) {
                // выполняем прочие действие, например, сохраняем задачу в базе данных
                $em = $this->getDoctrine()->getEntityManager();

                $em->persist($type_photo);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Тип фото відредаговано!');

                return $this->redirect($this->generateUrl('type_photo_manager'));
            } else {
                $this->get('session')->setFlash('notice', 'Форма заповнена невірно!');
            }

        }

        return $this->render('AcmeDesignBundle:TypePhoto:edit.html.twig',
            array(
                'form' => $form->createView(),
                'type_photo_id' => $type_photo->getId()
            )
        );
//        return $this->redirect($this->generateUrl('type_photo_manager'));
    }


    public function deleteAction($type_photo_id)
    {
        $this->check_securuty();

        $em = $this->getDoctrine()->getEntityManager();
        $type_photo = $em->getRepository("AcmeDesignBundle:TypePhoto")->find($type_photo_id);

        $request = $this->get('request');
        $upload_dir = Photo::upload_dir($request);

        foreach($type_photo->getPhotos() as $photo) {
//         echo $upload_dir.'/'.$photo->getPath().'<br>';
            unlink($upload_dir.'/'.$photo->getPath());
            unlink($upload_dir.'/thumb/'.$photo->getPath());
        }

        $em->remove($type_photo);
        $em->flush();

        return $this->redirect($this->generateUrl('type_photo_manager'));
    }


    protected function check_securuty()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw $this->createNotFoundException('Route not found');
//            return $this->redirect($this->generateUrl('main'));
        }
    }
}
