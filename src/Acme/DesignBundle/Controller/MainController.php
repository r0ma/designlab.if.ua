<?php

namespace Acme\DesignBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class MainController extends Controller
{

    public function indexAction()
    {
//        $request = $this->getRequest();
//        echo '<pre>'; var_dump($request); echo '</pre>'; die;
        return $this->render('AcmeDesignBundle:Main:index.html.twig');
    }
    
    public function mainMenuAction($route)
    {
        $main_menu = array(
            array('name'=>'Про нас', 'route'=>'about_us', 'routes'=>array('')),
            array('name'=>'Портфоліо', 'route'=>'object_list', 'routes'=>array('photos_object', 'photos_to_type')),
            array('name'=>'Послуги та ціни', 'route'=>'service_price', 'routes'=>array('')
//                , 'sub_menu'=>array(
//                array('name'=>'Дизайн-проект', 'route'=>'design'),
//                array('name'=>'3D візуалізація', 'route'=>'visualisation'),
//                array('name'=>'Ремонт', 'route'=>'repair'))
            ),
            array('name'=>'Контакти', 'route'=>'contact', 'routes'=>array('')),
        );

        foreach($main_menu as $index => $main_menu_item) {
            $main_menu[$index]['active'] = (in_array($route, $main_menu_item['routes']) or $main_menu_item['route']==$route) ? 'active' : '';

            if(isset($main_menu_item['sub_menu'])) {
                foreach($main_menu_item['sub_menu'] as $sub_index => $sub_menu_item) {
                    if($sub_menu_item['route'] == $route) {
                        $main_menu[$index]['sub_menu'][$sub_index]['active'] = 'active';
                        $main_menu[$index]['active'] = 'active';
                    } else {
                        $main_menu[$index]['sub_menu'][$sub_index]['active'] = '';
                    }
                }
            }
        }

//        echo "<pre>";
//        var_dump($main_menu);
//        echo "</pre>";
//        die;

        return $this->render('AcmeDesignBundle::main_menu.html.twig', array('main_menu' => $main_menu));
    }

    public function controlsGalleryAction()
    {
        return $this->render('AcmeDesignBundle::controls_gallery.html.twig');
    }

    public function aboutUsAction()
    {
        return $this->render('AcmeDesignBundle:Main:about_us.html.twig');
    }

    public function servicePriceAction()
    {
        return $this->render('AcmeDesignBundle:Main:service_price.html.twig');
    }

    public function contactsAction()
    {
        return $this->render('AcmeDesignBundle:Main:contacts.html.twig');
    }

}
