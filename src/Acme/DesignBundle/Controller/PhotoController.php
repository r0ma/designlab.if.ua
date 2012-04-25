<?php

namespace Acme\DesignBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Acme\DesignBundle\Entity\Photo;
use Acme\DesignBundle\Entity\Object;
use Acme\DesignBundle\Form\PhotoType;

class PhotoController extends Controller
{

    public function indexAction()
    {
        $photos = $this->getDoctrine()
            ->getRepository('AcmeDesignBundle:Photo')
            ->findAll();

        if (!$photos) {
            throw $this->createNotFoundException('Photos not found');
        }
        return $this->render('AcmeDesignBundle:Photo:index.html.twig', array('photos' => $photos));
    }

    public function photosObjectAction($id)
    {
        $object = $this->getDoctrine()
            ->getRepository('AcmeDesignBundle:Object')
            ->find($id);
        $photos = $object->getPhotos();

        return $this->render('AcmeDesignBundle:Photo:photos_object.html.twig', array('photos' => $photos));
    }

    public function photosToTypeAction($type_id)
    {
        $photos = $this->getDoctrine()
            ->getRepository('AcmeDesignBundle:Photo')
            ->getPhotosToType($type_id);
        if(count($photos) == 0) {
            throw $this->createNotFoundException('Photos in this type not found');
        }
        return $this->render('AcmeDesignBundle:Photo:photos_object.html.twig', array('photos' => $photos));
    }

    public function addAction(Request $request)
    {
        $this->check_securuty();

        $photo = new Photo();
        $form = $this->createForm(new PhotoType(), $photo);

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                // выполняем прочие действие, например, сохраняем задачу в базе данных
                $em = $this->getDoctrine()->getEntityManager();

                $request = $this->get('request');
                $photo->upload($request);

                $em->persist($photo);
                $em->flush();

                return $this->redirect($this->generateUrl('photo_add'));
            }
        }

        return $this->render('AcmeDesignBundle:Photo:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function deleteAction($photo_id)
    {
        $this->check_securuty();

        $em = $this->getDoctrine()->getEntityManager();
        $photo = $em->getRepository("AcmeDesignBundle:Photo")->find($photo_id);

        $request = $this->get('request');
        $upload_dir = Photo::upload_dir($request);

        unlink($upload_dir.'/'.$photo->getPath());
        unlink($upload_dir.'/thumb/'.$photo->getPath());

        $em->remove($photo);
        $em->flush();

        return $this->redirect($this->generateUrl('photo_list'));
    }


    protected function check_securuty()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw $this->createNotFoundException('Route not found');
        }
    }

}
