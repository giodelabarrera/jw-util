<?php
/**
 * Created by PhpStorm.
 * User: giorgio
 * Date: 24/9/17
 * Time: 22:11
 */

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MicrofonoController extends Controller
{
    /**
     *
     *
     * @Route("/", name="admin_microfono_generar_turnos")
     * @Method({"GET", "POST"})
     */
    public function generarTurnosAction(Request $request)
    {
        // @TODO: debe de salir de bd
        // @TODO: tipos, asamblea, visita super, conmemoracion
        $eventos = [
            [
                'tipo' => 'Asamblea',
                'fecha' => new \DateTime('2018-01-07'),
            ],
            [
                'tipo' => 'Asamblea',
                'fecha' => new \DateTime('2018-04-21'),
            ],
            [
                'tipo' => 'Visita del superintendente',
                'fecha' => new \DateTime('2017-10-10'),
            ],
            [
                'tipo' => 'Visita del superintendente',
                'fecha' => new \DateTime('2017-10-10'),
            ],
            [
                'tipo' => 'Visita del superintendente',
                'fecha' => new \DateTime('2017-10-11'),
            ],
            [
                'tipo' => 'Visita del superintendente',
                'fecha' => new \DateTime('2017-10-12'),
            ],
            [
                'tipo' => 'Visita del superintendente',
                'fecha' => new \DateTime('2017-10-13'),
            ],
            [
                'tipo' => 'Visita del superintendente',
                'fecha' => new \DateTime('2017-10-14'),
            ],
            [
                'tipo' => 'Visita del superintendente',
                'fecha' => new \DateTime('2017-10-15'),
            ],
        ];


        $formData['fecha_inicio'] = new \DateTime('2017-10-02');
        $formData['fecha_fin'] = new \DateTime('2018-04-08');
//        $formData['fecha_inicio_ronda'] = new \DateTime('2018-04-08');
        $formData['fecha_inicio_ronda'] = null;

        // numero de hermanos total: 16
        // numero de hermanos por asignacion: 2
        // numero de semanas para completar una ronda: 16/2


        // tratamiento

        $dtInicio = $formData['fecha_inicio'];
        $dtInicio->modify('Monday this week');

        $dtFin = $formData['fecha_fin'];
        $dtFin = $dtFin->modify('Monday this week');

        $intervalo = \DateInterval::createFromDateString('1 week');
        $periodo = new \DatePeriod($dtInicio, $intervalo, $dtFin);


        // hermanos
        $em = $this->getDoctrine()->getManager();

        $hermanos = $em->getRepository('AppBundle:Hermano')
            ->createQueryBuilder('h')
            ->join('h.privilegios', 'p')->addSelect('p')
            ->where('p.slug = :slug')
            ->setParameter('slug', 'microfono')
            ->getQuery()
            ->getResult()
        ;
        $hermanosAsignados = [];


        $semanas = [];
        $contAsignado = 0;
        $numAsignadosRotar = 2;
        foreach ($periodo as $diaSemanaInicio) {

            $semana = [];

            $diaSemanaFin = clone $diaSemanaInicio;
            $diaSemanaFin->modify('Sunday this week');

            // comprueba si es semana de evento





            /*$esSemanaAsamblea = false;
            $semana['asamblea'] = false;
            // controla asamblea
            foreach ($formData['asambleas'] as $diaAsamblea) {
                if ($diaSemanaInicio <= $diaAsamblea && $diaAsamblea <= $diaSemanaFin) {
                    $esSemanaAsamblea = true;
                    $semana['asamblea'] = true;
                    continue;
                }
            }

            $esSemanaVisitaSuper = false;
            $semana['visita_super'] = false;
            // controla asamblea
            foreach ($formData['visitas_superintendente'] as $diaVisita) {
                if ($diaSemanaInicio <= $diaVisita && $diaVisita <= $diaSemanaFin) {
                    $esSemanaVisitaSuper = true;
                    $semana['visita_super'] = true;
                    continue;
                }
            }*/

            // @TODO: conmemoracion
            /*$esSemanaVisitaSuper = false;
            $semana['visita_super'] = false;
            // controla asamblea
            foreach ($formData['visitas_superintendente'] as $diaVisita) {
                if ($diaSemanaInicio <= $diaVisita && $diaVisita <= $diaSemanaFin) {
                    $esSemanaVisitaSuper = true;
                    $semana['visita_super'] = true;
                    continue;
                }
            }*/

            $semana['dia_semana_inicio'] = $diaSemanaInicio;
            $semana['dia_semana_fin'] = $diaSemanaFin;



            $semana['asignados'] = [];


            // lista de hermanos

            // array de hermanos usados

            $hermano1 = null;
            $hermano2 = null;
            $hermano1Asignado = false;
            $hermano2Asignado = false;

            while ($hermano1Asignado == false) {
                $posicion = mt_rand(0, count($hermanos));
                $hermano = $hermanos[$posicion];
                if (!key_exists($hermano->getId(), $hermanosAsignados)) {
                    $hermano1 = $hermano;
                    $hermanosAsignados[$hermano->getId()] = $hermano;
                    $hermano1Asignado = true;
                }
            }

            while ($hermano2Asignado == false) {
                $posicion = mt_rand(0, count($hermanos));
                $hermano = $hermanos[$posicion];
                if (!key_exists($hermano->getId(), $hermanosAsignados)) {
                    $hermano2 = $hermano;
                    $hermanosAsignados[$hermano->getId()] = $hermano;
                    $hermano2Asignado = true;
                }
            }




            // coge hermano randome de lista de hermano
            // este hermano existe en array hermanos usados?
            // si
            //vuelve a darme otro
            // no
            // agrega a array de hermanos
            // si numero de array de hermanos usados es igual a lista de hermanos
            // entonces vacia array de hermanos usados

//            $asignados[$contAsignado];


            /*$semanas[] = $semana;

            $esSemanaEvento = ($esSemanaAsamblea || $esSemanaVisitaSuper);

            // si es semana con evento
            if (!$esSemanaEvento) {
                $contAsignado++;
                if ($contAsignado >= $numAsignadosRotar) $contAsignado = 0;
            }*/
        }


//        dump();
        die();

        return $this->render('admin/programa/microfono.html.twig', array(
        ));
    }
}