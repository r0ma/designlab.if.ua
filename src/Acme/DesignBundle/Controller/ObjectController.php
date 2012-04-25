<?php

namespace Acme\DesignBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\DesignBundle\Entity\Photo;
use Acme\DesignBundle\Entity\Object;
use Acme\DesignBundle\Form\ObjectType;

class ObjectController extends Controller
{

    public function indexAction() {
        $objects = $this->getDoctrine()
            ->getRepository('AcmeDesignBundle:Object')
            ->findAll();

        $photo_types = $this->getDoctrine()
            ->getRepository('AcmeDesignBundle:TypePhoto')
            ->getNotEmptyPhotoTypes();
//            ->findAll();

        if (count($objects) == 0) {
            throw $this->createNotFoundException('Objects not found');
        }
        return $this->render('AcmeDesignBundle:Object:index.html.twig', array(
            'objects' => $objects,
            'photo_types' => $photo_types)
        );
    }

    public function addAction(Request $request) {
        $this->check_securuty();

        $object = new Object();
        $form = $this->createForm(new ObjectType(), $object);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                // выполняем прочие действие, например, сохраняем задачу в базе данных
                $em = $this->getDoctrine()->getEntityManager();

                $object->upload();

                $em->persist($object);
                $em->flush();

                return $this->redirect($this->generateUrl('photo_add'));
            }
        }

        return $this->render('AcmeDesignBundle:Object:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function deleteAction($object_id) {
        $this->check_securuty();

        $em = $this->getDoctrine()->getEntityManager();

        $object = $em->getRepository("AcmeDesignBundle:Object")->find($object_id);
        $photos = $object->getPhotos();

        $request = $this->get('request');
        $upload_dir = Photo::upload_dir($request);

        unlink($upload_dir.'/../prew_project/'.$object->getPath());
        foreach($photos as $photo) {
//            echo $upload_dir.'/'.$photo->getPath().'<br>';
            unlink($upload_dir.'/'.$photo->getPath());
            unlink($upload_dir.'/thumb/'.$photo->getPath());
        }

        $em->remove($object);
        $em->flush();

        return $this->redirect($this->generateUrl('object_list'));
    }

    protected function check_securuty()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw $this->createNotFoundException('Route not found');
        }
    }

}
