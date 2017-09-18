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

        // content
        $menu->addChild('heading_content', [
            'label' => 'Contenido',
            'attributes' => ['class' => 'nav-main-heading'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('user', [
            'label' => 'User',
            'route' => 'admin_user_index',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('departamento', [
            'label' => 'Departamento',
            'route' => 'admin_departamento_index',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('asignacion', [
            'label' => 'Asignación',
            'route' => 'admin_asignacion_index',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        // programa
        $menu->addChild('heading_programa', [
            'label' => 'Programa',
            'attributes' => ['class' => 'nav-main-heading'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('microfono', [
            'label' => 'Micrófono',
            'uri' => '#',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('sonido', [
            'label' => 'Sonido',
            'uri' => '#',
            'attributes' => ['icon' => 'fa fa-user'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        /*$menu->addChild('usertype', [
            'label' => 'Type of user',
            'route' => 'admin_usertype_index',
            'attributes' => ['icon' => 'si si-moustache'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('country', [
            'label' => 'Country',
            'route' => 'admin_country_index',
            'attributes' => ['icon' => 'fa fa-flag-o'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);

        // archive
        $menu->addChild('heading_archive', [
            'label' => 'Archive',
            'attributes' => ['class' => 'nav-main-heading'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('archive', [
            'label' => 'Archive',
            'route' => 'admin_archive_index',
            'attributes' => ['icon' => 'si si-drawer'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('download', [
            'label' => 'Download',
            'route' => 'admin_download_index',
            'attributes' => ['icon' => 'fa fa-download'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('language', [
            'label' => 'Language',
            'route' => 'admin_language_index',
            'attributes' => ['icon' => 'fa fa-language'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('category', [
            'label' => 'Category',
            'route' => 'admin_category_index',
            'attributes' => ['icon' => 'fa fa-list-ul'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('documenttype', [
            'label' => 'Type of document',
            'route' => 'admin_documenttype_index',
            'attributes' => ['icon' => 'fa fa-list-ul'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('filetype', [
            'label' => 'Type of file',
            'route' => 'admin_filetype_index',
            'attributes' => ['icon' => 'fa fa-list-ul'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);

        // content
        $menu->addChild('heading_content', [
            'label' => 'Content',
            'attributes' => ['class' => 'nav-main-heading'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('configpage', [
            'label' => 'Config of page',
            'route' => 'admin_configpage_index',
             'attributes' => ['icon' => 'si si-wrench'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('slide', [
            'label' => 'Slide',
            'route' => 'admin_slide_index',
            'attributes' => ['icon' => 'fa fa-sliders'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);
        $menu->addChild('chatmessage', [
            'label' => 'Message of chat',
            'route' => 'admin_chatmessage_index',
            'attributes' => ['icon' => 'fa fa-comment-o'],
            'labelAttributes' => ['span_container' => true, 'class' => 'sidebar-mini-hide'],
        ]);*/

        return $menu;
    }
}