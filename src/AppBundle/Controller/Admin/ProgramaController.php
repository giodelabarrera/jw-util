<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Departamento;
use AppBundle\Form\Type\DatePickerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Programa controller.
 *
 * @Route("admin/programa")
 */
class ProgramaController extends Controller
{
    /**
     * @Route("/microfono", name="admin_programa_microfono")
     * @Method("GET")
     */
    public function microfonoAction(Request $request)
    {
        // form data

        $formData['asambleas'] = [
            new \DateTime('2018-01-07'),
            new \DateTime('2018-04-21'),
        ];
        $formData['visitas_superintendente'] = [
            new \DateTime('2017-10-10'),
            new \DateTime('2017-10-11'),
            new \DateTime('2017-10-12'),
            new \DateTime('2017-10-13'),
            new \DateTime('2017-10-14'),
            new \DateTime('2017-10-15'),
        ];
//        $formData['conmemoracion'] = ;
        $formData['fecha_inicio'] = new \DateTime('2017-10-02');
        $formData['fecha_fin'] = new \DateTime('2018-04-08');
        $formData['ultimos_usuarios'] = [
        ];


        // tratamiento

        $dtInicio = $formData['fecha_inicio'];
        $dtInicio->modify('Monday this week');

        $dtFin = $formData['fecha_fin'];
        $dtFin = $dtFin->modify('Monday this week');

        $intervalo = \DateInterval::createFromDateString('1 week');
        $periodo = new \DatePeriod($dtInicio, $intervalo, $dtFin);

        $semanas = [];
        $contAsignado = 0;
        $numAsignadosRotar = 2;
        foreach ($periodo as $diaSemanaInicio) {

            $semana = [];

            $diaSemanaFin = clone $diaSemanaInicio;
            $diaSemanaFin->modify('Sunday this week');

            $esSemanaAsamblea = false;
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
            }

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

//            $asignados[$contAsignado];


            $semanas[] = $semana;

            $esSemanaEvento = ($esSemanaAsamblea || $esSemanaVisitaSuper);

            // si es semana con evento
            if (!$esSemanaEvento) {
                $contAsignado++;
                if ($contAsignado >= $numAsignadosRotar) $contAsignado = 0;
            }
        }


        dump($semanas);
        die();

        return $this->render('admin/programa/microfono.html.twig', array(
        ));
    }

    /**
     * @Route("/sonido", name="admin_programa_sonido")
     * @Method("GET")
     */
    public function sonidoAction(Request $request)
    {
        // form data

        $formData['asambleas'] = [
        ];
        $formData['visita_superintendente'] = [
        ];
        $formData['conmemoracion'] = [
        ];
        $formData['fecha_fin'] = new \DateTime('2018-03-04');
        $formData['usuarios'] = [
        ];

        return $this->render('admin/programa/sonido.html.twig', array(
        ));
    }


}
