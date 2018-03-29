<?php
/**
 * Created by PhpStorm.
 * User: giorgio
 * Date: 9/8/17
 * Time: 17:55
 */

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createAdminSidebarMenu(array $options)
    {
        $menu = $this->factory->createItem('admin_sidebar');

        $menu->setChildrenAttribute('class', 'nav-main');

        // dashboard
        $menu->addChild('dashboard', [
            'label' => 'Dashboard',
            'route' => 'admin_dashboard',
            'attributes' => ['icon' => 'si si-speedometer'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);

        // user
        $menu->addChild('heading_user', [
            'label' => 'Usuario',
            'attributes' => ['class' => 'nav-main-heading'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('user', [
            'label' => 'Usuario',
            'route' => 'admin_user_index',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);

        // content
        $menu->addChild('heading_content', [
            'label' => 'Contenido',
            'attributes' => ['class' => 'nav-main-heading'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('hermano', [
            'label' => 'Hermano',
            'route' => 'admin_hermano_index',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('privilegio', [
            'label' => 'Privilegio',
            'route' => 'admin_privilegio_index',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('asignacion', [
            'label' => 'Asignación',
            'route' => 'admin_asignacion_index',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);

        // programación
        $menu->addChild('heading_programacion', [
            'label' => 'Programación',
            'attributes' => ['class' => 'nav-main-heading'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('programacion_microfono', [
            'label' => 'Micrófono',
            'route' => 'admin_microfono_generar_turnos',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('programacion_sonido', [
            'label' => 'Sonido',
            'uri' => '#',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);

        // turnos
        $menu->addChild('heading_turnos', [
            'label' => 'Turnos',
            'attributes' => ['class' => 'nav-main-heading'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('turnos_microfono', [
            'label' => 'Micrófono',
            'uri' => '#',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('turnos_sonido', [
            'label' => 'Sonido',
            'uri' => '#',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);

        return $menu;
    }
}