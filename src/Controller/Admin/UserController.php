<?php


namespace App\Controller\Admin;


use App\Entity\Attachemnt;
use App\Entity\User;
use App\Form\Admin\User\AddUserFormType;
use App\Form\Admin\User\EditUserFormType;
use App\Form\ChangePasswordType;
use App\Form\RtgImageType;
use App\Repository\AttachemntRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class UserController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/users", name="admin_users")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $repository = $entityManager->getRepository(User::class);

        return $this->render('admin/users/list.html.twig', [
            'users' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/users/images/remove/{id}", name="user_images_remopve")
     */
    public function imagesRemove(int $id, AttachemntRepository $attachemntRepository, EntityManagerInterface  $entityManager)
    {
        $attach = $attachemntRepository->findOneBy(['id' => $id]);
        $userId = $attach->getUser()->getId();

        $entityManager->remove($attach);
        $entityManager->flush();

        $this->addFlash('success', 'Usunięto zdjęcie');

       return $this->redirectToRoute('user_images', [
           'id' => $userId
       ]);
    }

    /**
     * @Route("/users/images/{id}", name="user_images")
     */
    public function images(
        int $id,
        Request $request,
        EntityManagerInterface $entityManager,
        AttachemntRepository $attachemntRepository,
        UserRepository $userRepository
    )
    {
        $attach = new Attachemnt();

        $form = $this->createForm(RtgImageType::class, $attach);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $attach->setUser($userRepository->findOneBy(['id' => $id]));
            $entityManager->persist($attach);
            $entityManager->flush();
            $this->addFlash('success', 'Dodano nowe zdjęcie');

        }

        return $this->render('admin/users/images.html.twig', [
            'form' => $form->createView(),
            'files' => $attachemntRepository->findBy(['user' => $id])
        ]);
    }

    /**
     * @Route("/users/change/{id}", name="admin_change_pass")
     */
    public function changePassword(
        User $user,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response
    {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $input = $request->request->get('change_password');

            if ($passwordEncoder->isPasswordValid($user, $input['currentPassword']) === false) {
                $this->addFlash('danger', 'Obecne hasło jest nie poprawne');
                return $this->redirectToRoute('admin_change_pass', ['id' => $user->getId()]);
            }

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $input['plainPassword']['first']
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            if ($this->getUser() !== null) {
                $this->addFlash('success', 'Hasło zostało zmienione');
                return $this->redirectToRoute('admin_change_pass', ['id' => $user->getId()]);
            }
        }

        return $this->render('admin/users/change.html.twig', [
            'form' => $form->createView(),
        ]);

        }

    /**
     * @Route("/users/create", name="admin_user_add")
     */
    public function add(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        UserAuthenticator $authenticator
    ): Response
    {
        $user = new User();
        $form = $this->createForm(AddUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $password = bin2hex(random_bytes(10));
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $password
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('testprojectuser999@gmail.com', 'Gabinet Stomatologiczny'))
                        ->to($user->getEmail())
                        ->subject('Witamy na naszej stronie.')
                        ->htmlTemplate('registration/confirmation_email.html.twig'),
                    $password
                );
            // do anything else you need here, like send an email

            if ($this->getUser() !== null) {
                $this->addFlash('success', 'Dodano nowego użytkownika i wysłano email');
                return $this->redirectToRoute('admin_users');
            } else {

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            }
        }

        return $this->render('admin/users/add.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/users/reset/{id}", name="admin_user_reset_password")
     */
    public function resetPassword(
        User $user,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response
    {
        // encode the plain password
        $password = bin2hex(random_bytes(10));
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $password
            )
        );

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('testprojectuser999@gmail.com', 'Gabinet Stomatologiczny'))
                ->to($user->getEmail())
                ->subject('Wygenerowano nowe hasło.')
                ->htmlTemplate('registration/reset_password.html.twig'),
            $password
        );
        // do anything else you need here, like send an email


        $this->addFlash('success', 'Zresetowano hasło i wysłano email');
        return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
    }



    /**
     * @Route("/users/{id}", name="admin_user_edit")
     */
    public function edit(User $user, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(EditUserFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($user);

            $roles = $user->getRoles();
            if ($roles[0] == null) {
                $user->setRoles([]);
            }


            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Dane użytkownika zaktualizowane');

            return $this->redirectToRoute('admin_user_edit', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('admin/users/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

}