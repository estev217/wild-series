<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use App\Service\Slugify;
use Doctrine\ORM\Query\Expr\From;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;



/**
 * @Route("/program")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="program_index", methods={"GET"})
     */
    public function index(ProgramRepository $programRepository): Response
    {
        return $this->render('program/index.html.twig', [
            'programs' => $programRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="program_new", methods={"GET","POST"})
     */
    public function new(Request $request, Slugify $slugify, ParameterBagInterface $parameterBag): Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);

            $entityManager->persist($program);
            $entityManager->flush();

            $mail = new PHPMailer(true);

                /*Uncomment next line to see SMTP debug after process*/
                /*$mail->SMTPDebug = SMTP::DEBUG_SERVER;*/

                /* Tells PHPMailer to use SMTP. */
                $mail->isSMTP();
                /* SMTP server address. */
                $mail->Host = 'smtp-mail.outlook.com';
                /* Use SMTP authentication. */
                $mail->SMTPAuth = true;
                /* SMTP authentication username. */
                $mail->Username = $this->getParameter('mail_from');
                /* SMTP authentication password. */
                $mail->Password = $this->getParameter('mail_password');
                /* Set the encryption system. */
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                /* Set the SMTP port. */
                $mail->Port = 587;

                $mail->setFrom($this->getParameter('mail_from'));
                $mail->addAddress($form['Receiver']->getData());

                $mail->isHTML(true);
                $mail->Subject = $form['title']->getData();
                $mail->Body = $form['summary']->getData();
                $mail->AltBody = 'There is a great disturbance in the Force.';

                /* Disable some SSL checks. */
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                $mail->send();
                echo 'Mail has been sent';

                /*->html($this->renderView('program/email/notification.html.twig', [
                    'program' => $program,
                ]));*/

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="program_show", methods={"GET"})
     */
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $program->getSeasons()
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="program_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Program $program, Slugify $slugify): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/edit.html.twig', [
            'program' => $program,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="program_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Program $program): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($program);
            $entityManager->flush();
        }

        return $this->redirectToRoute('program_index');
    }

}
