<?php

namespace FSC\HateoasBundle\Tests\Functional\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use FSC\HateoasBundle\Model\RouteAwareFormView;
use FSC\HateoasBundle\Tests\Functional\TestBundle\Model\PostsCollection;

class PostController extends Controller
{
    public function listPostsAction(Request $request)
    {
        $postsPager = $this->get('test.provider.post')->getPostsPager();

        $linksAwareWrapper = $this->get('fsc_hateoas.factory.links_aware_wrapper')->create($postsPager);
        $postsCollection = new PostsCollection($linksAwareWrapper);

        return new Response($this->get('serializer')->serialize($postsCollection, $request->get('_format')));
    }

    public function getPostAction(Request $request, $id)
    {
        $post = $this->get('test.provider.post')->getPost($id);

        return new Response($this->get('serializer')->serialize($post, $request->get('_format')));
    }

    public function getCreatePostFormAction(Request $request)
    {
        $form = $this->get('form.factory')->createNamed('post', 'test_post_create');
        $formView = $form->createView();
        $routeAwareFormView = new RouteAwareFormView($formView, 'POST', 'api_post_create');
        $linksAwareWrapper = $this->get('fsc_hateoas.factory.links_aware_wrapper')->create($routeAwareFormView);

        $this->get('serializer')->getSerializationVisitor('xml')->setDefaultRootName('form');

        return new Response($this->get('serializer')->serialize($linksAwareWrapper, $request->get('_format')));
    }
}
