<?php

namespace App\Controller;

use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\GraphQL\Resolver\ProductListResolver;
use Symfony\Component\HttpFoundation\{JsonResponse,Request};
use App\GraphQL\Mutation\ProductMutation;
use GraphQL\Examples\Blog\Types;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function ProductList(): Response
    {

        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
    
    /**
     * @Route("/newproduct", name="newproduct")
     */
    public function newProduct(): Response
    {
        return $this->render('base.html.twig');
    }
   

    /**
     * @Route("/graphql", name="graphql")
     * 
    */
    public function addProduct(Request $request): JsonResponse
    {
        $name = $request->get('name');
        $price = $request->get('price');
        $slug = $request->get('slug');
        $description = $request->get('description');

        $productInputType = new ObjectType([
            'name' => 'ProductInput',
            'fields' => [
                'resolve' => function() {
                $user = [
                    'name' => Type::string(),
                    'price' => Type::float(),
                    'description' => Type::string(),
                    'slug' => Type::string(),
                ];
                return $user;
                }
            ]
        ]);

        $productType = new ObjectType([
            'name' => 'ProductInput',
            'fields' => [
                'resolve' => function() {
                $user = [
                    'name' => Type::string(),
                    'price' => Type::float(),
                    'description' => Type::string(),
                    'slug' => Type::string(),
                ];
                return $user;
                }
            ],
        ]);

        $queryType = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'createProduct' => [
                  'type' => Type::listOf($productType),
                  'args' => [
                      'input' => Type::listOf($productInputType),
                        'name' => Type::string(),
                        'price' => Type::float(),
                        'description' => Type::string(),
                        'slug' => Type::string(),
                        //   'type' => $productInputType
                      ],
                    'resolve' => function($args){
                        return $args['input'];
                    },
                ],
            ],
        ]);
        
        $schema = new Schema(
            [
                'Mutation' =>  $queryType,
            ]
            
        );
       
        // $input['input'] = ['name' => $name, 'price' => $price, 'slug' => $slug, 'description' => $description ];
        $input['input'] = "mutation{ createProduct(input: {name: \"Product 3\",description: \"Description for product 3\",price: 300.00,slug: \"Symfony-5\"}) }";
        // $variable = json_encode('{"query":
        //     "query{ echo(name: \"Hello GraphQL\") }" }');
        
        // $rawQuery = $request->getContent();
        // $query    = json_decode($variable);
        
        $result    = GraphQL::executeQuery($schema, $input['input']);
        $output    = $result->toArray();
        
        return new JsonResponse($output, 200);
        // return new JsonResponse(['status' => 'success']);
        // $input['input'] = ['name' => $name, 'price' => $price, 'slug' => $slug, 'description' => $description ];
        // $mutation->createProduct($input);
    }

}
