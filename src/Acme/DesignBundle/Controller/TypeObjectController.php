<?php

namespace Acme\DesignBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\DesignBundle\Entity\Photo;
use Acme\DesignBundle\Entity\TypePhoto;
use Acme\DesignBundle\Entity\TypeObject;
use Acme\DesignBundle\Entity\Object;
use Acme\DesignBundle\Form\TypePhotoType;
use Acme\DesignBundle\Form\TypeObjectType;

class TypeObjectController extends Controller
{

    public function managerAction()
    {
        $this->check_securuty();

        $object_types = $this->getDoctrine()
            ->getRepository('AcmeDesignBundle:TypeObject')
            ->findAll();

        $object_type_to_create = new TypeObject();
        $form = $this->createForm(new TypeObjectType(), $object_type_to_create);

        return $this->render('AcmeDesignBundle:TypeObject:manager.html.twig',
            array(
                'object_types' => $object_types,
                'form' => $form->createView()
            )
        );

    }


    public function createAction(Request $request)
    {
        $this->check_securuty();

        if ($request->getMethod() == 'POST') {

            $object_type = new TypeObject();
            $form = $this->createForm(new TypeObjectType(), $object_type);

            $form->bindRequest($request);

            if ($form->isValid()) {
                // выполняем прочие действие, например, сохраняем задачу в базе данных
                $em = $this->getDoctrine()->getEntityManager();

                $em->persist($object_type);
                $em->flush();

                //                return $this->redirect($this->generateUrl('type_photo_manager'));
            } else {
                $this->get('session')->setFlash('notice', 'Форма заповнена невірно!');
            }

            $this->get('session')->setFlash('notice', 'Тип об’єкта збережено!');

        } else {
            $this->get('session')->setFlash('notice', 'Не було прийнято запиту POST з форми');
        }

        return $this->redirect($this->generateUrl('type_object_manager'));
    }


    public function editAction($type_object_id)
    {
        $this->check_securuty();

        $request = $request = $this->get('request');
        $em = $this->getDoctrine()->getEntityManager();
        $type_object = $em->getRepository("AcmeDesignBundle:TypeObject")->find($type_object_id);

        if (!$type_object) {
            $this->get('session')->setFlash('notice', 'Не було знайдено типу об’єкту з id = '.$type_object_id);
            return $this->redirect($this->generateUrl('type_object_manager'));
        }

        $form = $this->createForm(new TypeObjectType(), $type_object);

        if ($request->getMethod() == 'POST') {

            $form->bindRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getEntityManager();

                $em->persist($type_object);
                $em->flush();

                $this->get('session')->setFlash('notice', 'Тип об’єкту відредаговано!');

                return $this->redirect($this->generateUrl('type_object_manager'));
            } else {
                $this->get('session')->setFlash('notice', 'Форма заповнена невірно!');
            }

        }

        return $this->render('AcmeDesignBundle:TypeObject:edit.html.twig',
            array(
                'form' => $form->createView(),
                'type_object_id' => $type_object->getId()
            )
        );
        //        return $this->redirect($this->generateUrl('type_photo_manager'));
    }


    public function deleteAction($type_object_id)
    {
        $this->check_securuty();

        $em = $this->getDoctrine()->getEntityManager();
        $type_object = $em->getRepository("AcmeDesignBundle:TypeObject")->find($type_object_id);

        $request = $this->get('request');
        $upload_dir = Photo::upload_dir($request);

        foreach($type_object->getObjects() as $object) {
            foreach($object->getPhotos() as $photo) {
//                echo $upload_dir.'/'.$photo->getPath().'<br>';
                unlink($upload_dir.'/'.$photo->getPath());
                unlink($upload_dir.'/thumb/'.$photo->getPath());
            }
        }

        $em->remove($type_object);
        $em->flush();

        return $this->redirect($this->generateUrl('type_object_manager'));
    }


    protected function check_securuty()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw $this->createNotFoundException('Route not found');
            //            return $this->redirect($this->generateUrl('main'));
        }
    }
}
