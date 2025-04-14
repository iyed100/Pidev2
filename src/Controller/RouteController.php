<?php

namespace App\Controller;

use App\Entity\Route;
use App\Form\RouteType;
use App\Repository\RouteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route as RouteAnnotation;

#[RouteAnnotation('/route')]
class RouteController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RouteRepository $routeRepository
    ) {
    }

    #[RouteAnnotation('/', name: 'app_route_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $searchCriteria = [
            'depart' => $request->query->get('depart'),
            'arrivee' => $request->query->get('arrivee'),
            'distanceMin' => $request->query->get('distanceMin'),
            'distanceMax' => $request->query->get('distanceMax'),
            'transport' => $request->query->get('transport')
        ];

        // Remove empty criteria
        $searchCriteria = array_filter($searchCriteria, fn($value) => $value !== null && $value !== '');

        $routes = !empty($searchCriteria) 
            ? $this->routeRepository->findBySearchCriteria($searchCriteria)
            : $this->routeRepository->findAll();

        return $this->render('route/index.html.twig', [
            'routes' => $routes,
            'searchCriteria' => $searchCriteria
        ]);
    }

    #[RouteAnnotation('/new', name: 'app_route_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $route = new Route();
        $form = $this->createForm(RouteType::class, $route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->routeRepository->save($route, true);
            $this->addFlash('success', 'Route created successfully!');
            return $this->redirectToRoute('app_route_index');
        }

        return $this->render('route/new.html.twig', [
            'route' => $route,
            'form' => $form,
        ]);
    }

    #[RouteAnnotation('/{id}', name: 'app_route_show', methods: ['GET'])]
    public function show(Route $route): Response
    {
        return $this->render('route/show.html.twig', [
            'route' => $route,
        ]);
    }

    #[RouteAnnotation('/{id}/edit', name: 'app_route_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Route $route): Response
    {
        $form = $this->createForm(RouteType::class, $route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->routeRepository->save($route, true);
            $this->addFlash('success', 'Route updated successfully!');
            return $this->redirectToRoute('app_route_index');
        }

        return $this->render('route/edit.html.twig', [
            'route' => $route,
            'form' => $form,
        ]);
    }

    #[RouteAnnotation('/{id}', name: 'app_route_delete', methods: ['POST'])]
    public function delete(Request $request, Route $route): Response
    {
        if ($this->isCsrfTokenValid('delete'.$route->getId(), $request->request->get('_token'))) {
            $this->routeRepository->remove($route, true);
            $this->addFlash('success', 'Route deleted successfully!');
        }

        return $this->redirectToRoute('app_route_index');
    }

    
} 